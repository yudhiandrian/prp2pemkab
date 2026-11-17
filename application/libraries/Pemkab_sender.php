<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pemkab_sender
{
    private $s;
    public function __construct() { $ci =& get_instance(); $ci->load->library('Pemkab_service'); $this->s=$ci->pemkab_service; }
    public function http($method,$path,$body=null) {
        $cfg=$this->s->cfg; $base=rtrim($cfg['base_url'],'/'); $u=parse_url($base);
        if (!$cfg['enabled']) throw new RuntimeException('Sinkronisasi belum diaktifkan.');
        if (!$u || ($u['scheme']??'')!=='https' || empty($u['host']) || isset($u['user']) || isset($u['pass']) || isset($u['query']) || isset($u['fragment']) || !$cfg['token']) throw new RuntimeException('URL HTTPS dan token API wajib dikonfigurasi.');
        if (!function_exists('curl_init')) throw new RuntimeException('Ekstensi PHP cURL diperlukan untuk sinkronisasi.');
        $c=curl_init($base.$path); $response='';
        curl_setopt_array($c,[CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_RETURNTRANSFER=>false,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_TIMEOUT=>$cfg['timeout'],CURLOPT_FOLLOWLOCATION=>false,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2,CURLOPT_PROTOCOLS=>CURLPROTO_HTTPS,CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$cfg['token'],'Content-Type: application/json','Accept: application/json'],CURLOPT_WRITEFUNCTION=>function($ch,$chunk) use(&$response) { if (strlen($response)+strlen($chunk)>1024*1024) return 0; $response.=$chunk; return strlen($chunk); }]);
        if ($body!==null) curl_setopt($c,CURLOPT_POSTFIELDS,$body);
        $ok=curl_exec($c); $code=(int)curl_getinfo($c,CURLINFO_HTTP_CODE); curl_close($c);
        if ($ok===false) return [0,[]];
        $data=json_decode($response,true); return [$code,is_array($data)?$data:[]];
    }
    public function claim() {
        $s=$this->s;
        return $s->transaction(function() use($s) {
            $now=gmdate('Y-m-d H:i:s');
            $s->db->query("UPDATE pk_outbox SET status='retry',lease_token=NULL,lease_until=NULL WHERE status='leased' AND lease_until<?",[$now]);
            $e=$s->db->query("SELECT e.* FROM pk_outbox e WHERE e.status IN ('pending','retry') AND e.next_attempt<=? AND NOT EXISTS (SELECT 1 FROM pk_outbox p WHERE p.source_uuid=e.source_uuid AND p.source_version<e.source_version AND p.status<>'accepted') ORDER BY e.id LIMIT 1 FOR UPDATE",[$now])->row_array();
            if (!$e) return null;
            $entity=$s->db->get_where('pk_sync_entities',['source_uuid'=>$e['source_uuid']])->row_array();
            if (!$e['request_body']) {
                $req=['schema_version'=>'1.0','installation_id'=>$s->installation()['installation_id'],'event_id'=>$e['event_id'],'entity_type'=>$e['entity_type'],'source_uuid'=>$e['source_uuid'],'source_version'=>(int)$e['source_version'],'expected_source_version'=>(int)$e['source_version']-1,'expected_remote_version'=>(int)$entity['remote_version'],'payload_sha256'=>$e['checksum'],'payload_json'=>$e['payload']];
                $e['request_body']=Pemkab_rules::json($req);
            }
            $e['lease_token']=Pemkab_rules::uuid();
            // Lease exceeds worst case upload/response time for this attempt.
            $snap=json_decode($e['payload'],true); $photos=0;
            foreach (($snap['fisik']??[]) as $d) $photos+=count($d['dokumentasi']??[]);
            $ttl=max(180,($photos+1)*($s->cfg['timeout']+15)+60);
            $s->db->update('pk_outbox',['status'=>'leased','lease_until'=>gmdate('Y-m-d H:i:s',time()+$ttl),'lease_token'=>$e['lease_token'],'request_body'=>$e['request_body'],'attempts'=>(int)$e['attempts']+1],['id'=>$e['id']]);
            $e['attempts']++; return $e;
        });
    }
    private function files($e,$transport) {
        $snap=json_decode($e['payload'],true);
        foreach (($snap['fisik']??[]) as $d) foreach (($d['dokumentasi']??[]) as $photo) {
            if ($photo['deleted_at']!==null || $d['deleted_at']!==null || $snap['kegiatan']['deleted_at']!==null) continue;
            $doc=$this->s->db->get_where('infrastruktur_dokumentasi',['source_uuid'=>$photo['source_uuid']])->row_array();
            if (!$doc) throw new RuntimeException('Metadata berkas lokal hilang.');
            $file=$this->s->storage().'/'.basename($doc['file_dokumentasi']);
            if (!is_file($file) || hash_file('sha256',$file)!==$photo['sha256']) throw new RuntimeException('Berkas lokal hilang atau checksum berubah.');
            $body=Pemkab_rules::json(['installation_id'=>$this->s->installation()['installation_id'],'source_uuid'=>$photo['source_uuid'],'sha256'=>$photo['sha256'],'mime'=>$photo['mime'],'content_base64'=>base64_encode(file_get_contents($file))]);
            list($code,$reply)=$transport('POST','/files',$body);
            if ($code<200 || $code>=300 || ($reply['source_uuid']??'')!==$photo['source_uuid'] || ($reply['sha256']??'')!==$photo['sha256']) return [$code===0?0:($code>=400?$code:502),[]];
        }
        return null;
    }
    public function run($limit=20,$transport=null) {
        if ($transport===null) { if (!$this->s->cfg['enabled']) throw new RuntimeException('Sinkronisasi belum diaktifkan.'); $transport=[$this,'http']; }
        $stats=['accepted'=>0,'retry'=>0,'conflict'=>0,'rejected'=>0];
        for ($i=0;$i<$limit;$i++) {
            $e=$this->claim(); if (!$e) break;
            try { $fileError=$this->files($e,$transport); list($code,$r)=$fileError?:$transport('POST','/events',$e['request_body']); }
            catch (Throwable $err) { $code=0; $r=[]; }
            $expected=json_decode($e['request_body'],true);
            $entity=$this->s->db->get_where('pk_sync_entities',['source_uuid'=>$e['source_uuid']])->row_array();
            $ok=$code>=200 && $code<300 && in_array($r['status']??'',['accepted','duplicate'],true)
                && ($r['event_id']??'')===$e['event_id'] && ($r['source_uuid']??'')===$e['source_uuid']
                && ($r['installation_id']??'')===$this->s->installation()['installation_id']
                && (int)($r['source_version']??0)===(int)$e['source_version'] && ($r['payload_sha256']??'')===$e['checksum']
                && is_scalar($r['remote_id']??null) && strlen((string)$r['remote_id'])>0 && strlen((string)$r['remote_id'])<=100
                && filter_var($r['remote_version']??null,FILTER_VALIDATE_INT)!==false && (int)$r['remote_version']>(int)$expected['expected_remote_version']
                && (!$entity['remote_id'] || (string)$r['remote_id']===(string)$entity['remote_id']);
            $state=$ok?'accepted':($code===409?'conflict':(in_array($code,[400,401,403,404,413,415,422],true)?'rejected':'retry'));
            $message=$ok?'Diterima pusat':($code===0?'Jaringan, konfigurasi pengirim, atau berkas lokal gagal.':('HTTP '.$code.($code>=200 && $code<300?' — receipt tidak valid.':'')));
            $s=$this->s;
            $s->transaction(function() use($s,$e,$state,$code,$r,$message) {
                $live=$s->db->get_where('pk_outbox',['id'=>$e['id'],'status'=>'leased','lease_token'=>$e['lease_token']])->row_array();
                if (!$live) return;
                $delay=min(3600,15*pow(2,min(8,(int)$e['attempts']-1)))+random_int(0,15);
                $s->db->update('pk_outbox',['status'=>$state,'lease_token'=>null,'lease_until'=>null,'next_attempt'=>gmdate('Y-m-d H:i:s',time()+$delay),'last_error'=>$message,'receipt'=>$state==='accepted'?Pemkab_rules::json($r):null],['id'=>$e['id']]);
                if ($state==='accepted') $s->db->update('pk_sync_entities',['ack_source_version'=>$e['source_version'],'remote_id'=>(string)$r['remote_id'],'remote_version'=>(int)$r['remote_version']],['source_uuid'=>$e['source_uuid']]);
                $s->db->insert('pk_sync_attempts',['event_id'=>$e['event_id'],'attempted_at'=>gmdate('Y-m-d H:i:s'),'http_status'=>$code,'outcome'=>$state,'message'=>$message]);
            });
            $stats[$state]++;
        }
        return $stats;
    }
    public function references($transport=null) {
        $s=$this->s; $transport=$transport?:[$this,'http']; list($code,$r)=$transport('GET','/references',null);
        if ($code!==200 || !is_array($r['items']??null) || empty($r['version'])) throw new RuntimeException('Referensi pusat gagal diambil.');
        return $s->transaction(function() use($s,$r) {
            foreach ($r['items'] as $item) {
                if (!in_array($item['jenis']??'', ['psd','phtc'],true)) throw new InvalidArgumentException('Jenis referensi tidak valid.');
                $a=['jenis'=>$item['jenis'],'kode'=>Pemkab_rules::text($item['kode'],50),'nama'=>Pemkab_rules::text($item['nama'],180),'version'=>Pemkab_rules::text($r['version'],80),'aktif'=>($item['aktif']??'Y')==='N'?'N':'Y'];
                $s->db->replace('pk_references',$a);
            }
            return count($r['items']);
        });
    }
    public function reconcile($year,$transport=null) {
        $s=$this->s; $transport=$transport?:[$this,'http']; $inst=$s->installation();
        $local=$s->db->query("SELECT e.source_uuid,e.entity_type,e.ack_source_version,e.remote_id,e.remote_version,o.checksum FROM pk_sync_entities e LEFT JOIN pk_outbox o ON o.source_uuid=e.source_uuid AND o.source_version=e.ack_source_version WHERE e.ack_source_version>0 ORDER BY e.source_uuid")->result_array();
        list($code,$r)=$transport('POST','/reconcile',Pemkab_rules::json(['installation_id'=>$inst['installation_id'],'tahun'=>(int)$year,'entities'=>$local]));
        if ($code!==200 || !is_array($r['differences']??null)) throw new RuntimeException('Rekonsiliasi pusat gagal.');
        return ['entities'=>count($local),'differences'=>$r['differences']];
    }
}
