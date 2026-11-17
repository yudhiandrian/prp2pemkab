<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pemkab_cli extends CI_Controller
{
    public function __construct() { parent::__construct(); if (!$this->input->is_cli_request()) show_404(); $this->db->db_debug=false; $this->load->library('Pemkab_service'); }
    private function result($fn) { try { echo Pemkab_rules::json($fn()).PHP_EOL; } catch(Throwable $e) { fwrite(STDERR,$e->getMessage().PHP_EOL); exit(1); } }
    public function install($kab=null,$nama=null,$admin=null) {
        $this->result(function() use($kab,$nama,$admin) {
            $kab=Pemkab_rules::integer($kab,1,33); $nama=Pemkab_rules::text($nama,180); $admin=Pemkab_rules::integer($admin,1,PHP_INT_MAX);
            $u=$this->db->get_where('users',['id_user'=>$admin,'is_active'=>'Y','role_admin'=>'master','skpd'=>'N'])->row_array();
            if (!$u) throw new InvalidArgumentException('ID admin harus akun master aktif dan bukan akun OPD.');
            // Do not reinterpret a pre-existing Sumut/other schema as this module.
            foreach (['pemkab_opd'=>'source_uuid','pemkab_opd_tahun'=>'id_opd_tahun','infrastruktur_wilayah'=>'source_uuid','infrastruktur_detail'=>'source_uuid','infrastruktur_keuangan'=>'source_uuid','infrastruktur_dokumentasi'=>'sha256'] as $table=>$field) {
                if ($this->db->table_exists($table) && !$this->db->field_exists($field,$table)) throw new RuntimeException('Tabel '.$table.' sudah ada dengan skema berbeda; gunakan database Pemkab/salinan yang sesuai.');
            }
            $sql=file_get_contents(FCPATH.'sql/migrations/20261117_pemkab_terintegrasi.sql');
            $sql=preg_replace('/^\s*--.*$/m','',$sql);
            foreach (explode(';',$sql) as $q) if (trim($q) && !$this->db->query($q)) throw new RuntimeException('Pembuatan skema modul gagal.');
            $old=$this->db->get_where('pk_installation',['id'=>1])->row_array();
            if ($old && ((int)$old['id_kabupaten']!==$kab || $old['nama_daerah']!==$nama)) throw new RuntimeException('Identitas instalasi sudah ada; tidak dapat diganti.');
            if (!$old) $this->db->insert('pk_installation',['id'=>1,'installation_id'=>Pemkab_rules::uuid(),'id_kabupaten'=>$kab,'nama_daerah'=>$nama,'created_at'=>gmdate('Y-m-d H:i:s')]);
            $this->db->replace('pk_user_access',['id_user'=>$admin,'role'=>'admin','id_opd_pemkab'=>null]);
            $this->pemkab_service->storage();
            return ['status'=>'installed','installation'=>$this->pemkab_service->installation(),'url'=>site_url('pemkab-baru')];
        });
    }
    public function grant($id=null,$role=null,$opd=null) {
        $this->result(function() use($id,$role,$opd) {
            $this->pemkab_service->installation(); $id=Pemkab_rules::integer($id,1,PHP_INT_MAX);
            if (!in_array($role,['admin','opd','viewer'],true) || !$this->db->get_where('users',['id_user'=>$id,'is_active'=>'Y'])->row_array()) throw new InvalidArgumentException('Akun/role tidak valid.');
            if ($role==='opd') $this->pemkab_service->opdAccess(['role'=>'admin'],Pemkab_rules::integer($opd,1,PHP_INT_MAX));
            $this->db->replace('pk_user_access',['id_user'=>$id,'role'=>$role,'id_opd_pemkab'=>$role==='opd'?(int)$opd:null]);
            $this->pemkab_service->audit(['id_user'=>0],'grant_access',null,['id_user'=>$id,'role'=>$role,'opd'=>$opd]); return ['status'=>'granted'];
        });
    }
    public function policy($mode=null) {
        $this->result(function() use($mode) {
            if (!in_array($mode,['manual','auto'],true)) throw new InvalidArgumentException('Gunakan manual atau auto.');
            $this->pemkab_service->transaction(function() use($mode) { $this->db->update('pk_installation',['approval_mode'=>$mode],['id'=>1]); $this->pemkab_service->audit(['id_user'=>0],'policy',null,['mode'=>$mode]); });
            return ['mode'=>$mode,'note'=>'Hanya perubahan baru yang mengikuti mode ini; persetujuan versi lama tetap berlaku.'];
        });
    }
    public function send($limit=20) { $this->result(function() use($limit) { $this->load->library('Pemkab_sender'); return $this->pemkab_sender->run(Pemkab_rules::integer($limit,1,100)); }); }
    public function references() { $this->result(function() { $this->load->library('Pemkab_sender'); return ['updated'=>$this->pemkab_sender->references()]; }); }
    public function reconcile($year=null) { $this->result(function() use($year) { $this->load->library('Pemkab_sender'); return $this->pemkab_sender->reconcile(Pemkab_rules::integer($year,2000,2100)); }); }
    public function legacy($year=null,$mode='dry-run',$decisionFile=null) {
        $this->result(function() use($year,$mode,$decisionFile) {
            if (!in_array($mode,['dry-run','apply'],true)) throw new InvalidArgumentException('Mode dry-run atau apply.');
            $decisions=$decisionFile?json_decode(file_get_contents($decisionFile),true,512,JSON_THROW_ON_ERROR):[];
            $this->load->library('Pemkab_legacy'); return $this->pemkab_legacy->run($year,$decisions,$mode==='apply');
        });
    }
    public function retry($event=null) {
        $this->result(function() use($event) {
            return $this->pemkab_service->transaction(function() use($event) {
                $e=$this->db->get_where('pk_outbox',['event_id'=>$event])->row_array();
                if (!$e || !in_array($e['status'],['retry','rejected'],true)) throw new InvalidArgumentException('Hanya gagal/rejected yang dapat dicoba ulang; konflik perlu rekonsiliasi di pusat.');
                $this->db->update('pk_outbox',['status'=>'pending','next_attempt'=>gmdate('Y-m-d H:i:s')],['id'=>$e['id']]); return ['status'=>'pending'];
            });
        });
    }
}
