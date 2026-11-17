<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__.'/Pemkab_rules.php';
class Pemkab_service
{
    public $ci, $db, $cfg;
    public function __construct() {
        $this->ci =& get_instance(); $this->db=$this->ci->db;
        $this->ci->config->load('pemkab_sync'); $this->cfg=$this->ci->config->item('pemkab_sync');
    }
    public function installation() {
        if (!$this->db->table_exists('pk_installation')) throw new RuntimeException('Jalankan instalasi modul melalui CLI terlebih dahulu.');
        $r=$this->db->get_where('pk_installation',['id'=>1])->row_array();
        if (!$r) throw new RuntimeException('Identitas daerah belum dipasang.'); return $r;
    }
    public function actor($id) {
        $r=$this->db->select('a.*,u.nama_user,u.is_active')->from('pk_user_access a')->join('users u','u.id_user=a.id_user')->where('a.id_user',(int)$id)->get()->row_array();
        if (!$r || $r['is_active']!=='Y' || !in_array($r['role'],['admin','opd','viewer'],true)) throw new DomainException('Akun tidak memiliki akses modul atau tidak aktif.'); return $r;
    }
    public function admin($actor) { if ($actor['role']!=='admin') throw new DomainException('Hanya admin daerah yang dapat melakukan tindakan ini.'); }
    public function writeAccess($actor) { if (!in_array($actor['role'],['admin','opd'],true)) throw new DomainException('Akses baca saja.'); }
    public function opdAccess($actor,$id,$tahun=null) {
        if ($actor['role']==='opd' && (int)$actor['id_opd_pemkab']!==(int)$id) throw new DomainException('OPD di luar kewenangan akun.');
        $o=$this->db->get_where('pemkab_opd',['id_opd_pemkab'=>(int)$id,'id_kabupaten'=>$this->installation()['id_kabupaten']])->row_array();
        if (!$o || $o['status_aktif']!=='Y') throw new DomainException('OPD tidak tersedia.');
        if ($tahun!==null && !$this->db->get_where('pemkab_opd_tahun',['id_opd_pemkab'=>$id,'tahun'=>$tahun,'status_aktif'=>'Y'])->row_array()) throw new InvalidArgumentException('OPD belum diaktifkan untuk tahun ini.');
        return $o;
    }
    public function activity($actor,$id,$includeDeleted=false) {
        $installation=$this->installation();
        $this->db->select('k.*,m.id_opd_pemkab')->from('infrastruktur_wilayah k')->join('pemkab_kegiatan_opd m','m.id_kegiatan=k.id_kegiatan')->where('k.id_kegiatan',(int)$id)->where('k.id_kabupaten',$installation['id_kabupaten']);
        if (!$includeDeleted) $this->db->where('k.deleted_at',null);
        $r=$this->db->get()->row_array(); if (!$r) throw new DomainException('Kegiatan tidak ditemukan.');
        $this->opdAccess($actor,$r['id_opd_pemkab']); return $r;
    }
    public function transaction($fn) {
        $this->db->trans_begin();
        try {
            // Serializes edits, snapshots and migrations across this installation.
            $this->db->query('SELECT id FROM pk_installation WHERE id=1 FOR UPDATE');
            $r=$fn(); if (!$this->db->trans_status()) throw new RuntimeException('Penyimpanan database gagal.');
            $this->db->trans_commit(); return $r;
        } catch (Throwable $e) { $this->db->trans_rollback(); throw $e; }
    }
    public function audit($actor,$action,$uuid,$detail=[]) {
        $this->db->insert('pk_audit',['id_user'=>(int)$actor['id_user'],'action'=>$action,'source_uuid'=>$uuid,'detail'=>Pemkab_rules::json($detail),'created_at'=>gmdate('Y-m-d H:i:s')]);
    }
    public function queue($type,$uuid,$data,$actor) {
        $old=$this->db->get_where('pk_sync_entities',['source_uuid'=>$uuid])->row_array();
        $v=$old ? (int)$old['source_version']+1 : 1;
        if ($old) $this->db->update('pk_sync_entities',['source_version'=>$v],['source_uuid'=>$uuid]);
        else $this->db->insert('pk_sync_entities',['source_uuid'=>$uuid,'source_version'=>$v,'entity_type'=>$type]);
        $payload=Pemkab_rules::json($data); $now=gmdate('Y-m-d H:i:s');
        $this->db->insert('pk_outbox',['event_id'=>Pemkab_rules::uuid(),'source_uuid'=>$uuid,'source_version'=>$v,'entity_type'=>$type,'payload'=>$payload,'checksum'=>hash('sha256',$payload),'status'=>$this->installation()['approval_mode']==='auto'?'pending':'awaiting','next_attempt'=>$now,'created_at'=>$now]);
        $this->audit($actor,'save_'.$type,$uuid,['source_version'=>$v,'checksum'=>hash('sha256',$payload)]);
    }
    public function snapshot($id) {
        $k=$this->db->get_where('infrastruktur_wilayah',['id_kegiatan'=>$id])->row_array();
        $m=$this->db->get_where('pemkab_kegiatan_opd',['id_kegiatan'=>$id])->row_array();
        $o=$this->db->get_where('pemkab_opd',['id_opd_pemkab'=>$m['id_opd_pemkab']])->row_array();
        $ot=$this->db->get_where('pemkab_opd_tahun',['id_opd_pemkab'=>$m['id_opd_pemkab'],'tahun'=>$k['tahun']])->row_array();
        $fisik=$this->db->where('id_kegiatan',$id)->order_by('id_detail')->get('infrastruktur_detail')->result_array();
        $uang=$this->db->where('id_kegiatan',$id)->order_by('id_keuangan')->get('infrastruktur_keuangan')->result_array();
        foreach ($fisik as &$d) {
            $d['dokumentasi']=$this->db->where('id_detail',$d['id_detail'])->order_by('id_dokumentasi')->get('infrastruktur_dokumentasi')->result_array();
            foreach ($d['dokumentasi'] as &$photo) unset($photo['file_dokumentasi']); unset($photo);
        } unset($d);
        return ['kegiatan'=>$k,'opd'=>$o,'opd_tahun'=>$ot,'fisik'=>$fisik,'keuangan'=>$uang];
    }
    public function queueActivity($id,$actor) {
        $p=$this->snapshot($id); $this->queue('kegiatan',$p['kegiatan']['source_uuid'],$p,$actor);
    }
    public function saveOpd($actor,$p) {
        $this->admin($actor);
        return $this->transaction(function() use($actor,$p) {
            $id=(int)($p['id']??0); $year=Pemkab_rules::integer($p['tahun'],2000,2100);
            $name=Pemkab_rules::text($p['nama_opd'],180); $active=($p['status_aktif']??'Y')==='N'?'N':'Y';
            $old=$id ? $this->opdAccess($actor,$id) : null;
            $duplicate=$this->db->get_where('pemkab_opd',['id_kabupaten'=>$this->installation()['id_kabupaten'],'nama_opd'=>$name])->row_array();
            if ($duplicate && (int)$duplicate['id_opd_pemkab']!==$id) throw new InvalidArgumentException('Nama OPD sudah ada.');
            if ($old) $this->db->update('pemkab_opd',['nama_opd'=>$name],['id_opd_pemkab'=>$id]);
            else { $this->db->insert('pemkab_opd',['source_uuid'=>Pemkab_rules::uuid(),'id_kabupaten'=>$this->installation()['id_kabupaten'],'nama_opd'=>$name,'created_at'=>gmdate('Y-m-d H:i:s')]); $id=$this->db->insert_id(); }
            $ot=['id_opd_pemkab'=>$id,'tahun'=>$year,'nama_opd'=>$name,'status_aktif'=>$active];
            $existing=$this->db->get_where('pemkab_opd_tahun',['id_opd_pemkab'=>$id,'tahun'=>$year])->row_array();
            if ($existing) $this->db->update('pemkab_opd_tahun',$ot,['id_opd_tahun'=>$existing['id_opd_tahun']]); else $this->db->insert('pemkab_opd_tahun',$ot);
            $o=$this->db->get_where('pemkab_opd',['id_opd_pemkab'=>$id])->row_array();
            $this->queue('opd',$o['source_uuid'],['opd'=>$o,'tahun'=>$this->db->order_by('tahun')->get_where('pemkab_opd_tahun',['id_opd_pemkab'=>$id])->result_array()],$actor); return $id;
        });
    }
    public function saveActivity($actor,$p) {
        $this->writeAccess($actor);
        return $this->transaction(function() use($actor,$p) {
            $id=(int)($p['id']??0); $old=$id ? $this->activity($actor,$id) : null;
            $year=Pemkab_rules::integer($p['tahun'],2000,2100); $opd=Pemkab_rules::integer($p['id_opd_pemkab'],1,PHP_INT_MAX);
            $this->opdAccess($actor,$opd,$year);
            if ($old && (int)$old['tahun']!==$year) throw new InvalidArgumentException('Tahun kegiatan yang sudah tersimpan tidak dapat diubah.');
            if ($old && $actor['role']==='opd' && (int)$old['id_opd_pemkab']!==$opd) throw new DomainException('Pemindahan OPD harus melalui admin.');
            $a=['tahun'=>$year,'keperluan'=>Pemkab_rules::text($p['keperluan'],255),'pagu'=>Pemkab_rules::money($p['pagu']),'nilai'=>Pemkab_rules::money($p['nilai']),'tgl_kontrak'=>Pemkab_rules::date($p['tgl_kontrak']??'',null,true),'waktu'=>Pemkab_rules::text($p['waktu']??'',150,false),'lokasi_pekerjaan'=>Pemkab_rules::text($p['lokasi_pekerjaan']??'',150,false),'koordinat'=>Pemkab_rules::text($p['koordinat']??'',75,false),'st_kontrak'=>$p['st_kontrak']??'B'];
            if (!in_array($a['st_kontrak'],['B','P','S'],true)) throw new InvalidArgumentException('Status kontrak tidak valid.');
            if (Pemkab_rules::cents($a['nilai'])>Pemkab_rules::cents($a['pagu'])) throw new InvalidArgumentException('Nilai kontrak melebihi pagu.');
            foreach (['status_1','status_3','tkd'] as $f) $a[$f]=isset($p[$f]) && $p[$f]==='Y'?'Y':'N';
            foreach (['psd','phtc'] as $f) {
                $a[$f]=empty($p[$f])?null:Pemkab_rules::text($p[$f],50);
                if ($a[$f]!==null && !$this->db->get_where('pk_references',['jenis'=>$f,'kode'=>$a[$f],'aktif'=>'Y'])->row_array()) throw new InvalidArgumentException('Kode '.$f.' belum ada pada referensi pusat.');
            }
            if ($id) {
                $total=$this->db->select_sum('nilai')->get_where('infrastruktur_keuangan',['id_kegiatan'=>$id,'deleted_at'=>null])->row_array();
                if (Pemkab_rules::cents($total['nilai']?:'0')>Pemkab_rules::cents($a['nilai'])) throw new InvalidArgumentException('Nilai kontrak lebih kecil dari realisasi tercatat.');
                $this->db->update('infrastruktur_wilayah',$a,['id_kegiatan'=>$id]);
                $this->db->update('pemkab_kegiatan_opd',['id_opd_pemkab'=>$opd],['id_kegiatan'=>$id]);
            } else {
                $a+=['source_uuid'=>Pemkab_rules::uuid(),'id_kabupaten'=>$this->installation()['id_kabupaten'],'created_at'=>gmdate('Y-m-d H:i:s')];
                $this->db->insert('infrastruktur_wilayah',$a); $id=$this->db->insert_id();
                $this->db->insert('pemkab_kegiatan_opd',['id_kegiatan'=>$id,'id_opd_pemkab'=>$opd]);
            }
            $this->queueActivity($id,$actor); return $id;
        });
    }
    public function saveRealization($actor,$kind,$p) {
        $this->writeAccess($actor);
        return $this->transaction(function() use($actor,$kind,$p) {
            $k=$this->activity($actor,(int)$p['id_kegiatan']); $id=(int)($p['id']??0);
            $table=$kind==='fisik'?'infrastruktur_detail':'infrastruktur_keuangan'; $pk=$kind==='fisik'?'id_detail':'id_keuangan';
            if ($id && !$this->db->get_where($table,[$pk=>$id,'id_kegiatan'=>$k['id_kegiatan'],'deleted_at'=>null])->row_array()) throw new DomainException('Realisasi tidak ditemukan pada kegiatan.');
            $a=['keterangan'=>Pemkab_rules::text($p['keterangan']??'',500,false)];
            $date=Pemkab_rules::date($p['tanggal'], $k['tahun']);
            if ($kind==='fisik') {
                $v=Pemkab_rules::money($p['nilai']); if ((float)$v>100) throw new InvalidArgumentException('Fisik harus 0–100%.');
                $a+=['tanggal'=>$date,'realisasi'=>$v];
            } else {
                $a+=['tahun'=>$k['tahun'],'tgl_realisasi'=>$date,'nilai'=>Pemkab_rules::money($p['nilai'])];
                $sum=$this->db->select_sum('nilai')->where('id_keuangan !=',$id)->get_where($table,['id_kegiatan'=>$k['id_kegiatan'],'deleted_at'=>null])->row_array();
                if (Pemkab_rules::cents($sum['nilai']?:'0')+Pemkab_rules::cents($a['nilai'])>Pemkab_rules::cents($k['nilai'])) throw new InvalidArgumentException('Total realisasi melebihi nilai kontrak.');
            }
            if ($id) $this->db->update($table,$a,[$pk=>$id]);
            else { $a+=['id_kegiatan'=>$k['id_kegiatan'],'source_uuid'=>Pemkab_rules::uuid()]; $this->db->insert($table,$a); $id=$this->db->insert_id(); }
            $this->queueActivity($k['id_kegiatan'],$actor); return $id;
        });
    }
    public function remove($actor,$kind,$id) {
        $this->writeAccess($actor);
        if ($kind==='finance') {
            $this->admin($actor); return $this->transaction(function() use($actor,$id) {
                $row=$this->db->get_where('pk_finance',['id'=>$id,'deleted_at'=>null])->row_array();
                if (!$row) throw new DomainException('Data keuangan tidak ditemukan.');
                $row['deleted_at']=gmdate('Y-m-d H:i:s'); $this->db->update('pk_finance',['deleted_at'=>$row['deleted_at']],['id'=>$id]);
                $this->queue('keuangan_daerah',$row['source_uuid'],$row,$actor);
            });
        }
        return $this->transaction(function() use($actor,$kind,$id) {
            $tables=['kegiatan'=>['infrastruktur_wilayah','id_kegiatan'],'fisik'=>['infrastruktur_detail','id_detail'],'keuangan'=>['infrastruktur_keuangan','id_keuangan'],'foto'=>['infrastruktur_dokumentasi','id_dokumentasi']];
            if (!isset($tables[$kind])) throw new InvalidArgumentException('Jenis data tidak valid.');
            list($t,$pk)=$tables[$kind]; $r=$this->db->get_where($t,[$pk=>$id,'deleted_at'=>null])->row_array();
            if (!$r) throw new DomainException('Data tidak ditemukan.');
            if ($kind==='foto') { $d=$this->db->get_where('infrastruktur_detail',['id_detail'=>$r['id_detail'],'deleted_at'=>null])->row_array(); if (!$d) throw new DomainException('Detail tidak tersedia.'); $kid=$d['id_kegiatan']; }
            else $kid=$r['id_kegiatan'];
            $this->activity($actor,$kid); $this->db->update($t,['deleted_at'=>gmdate('Y-m-d H:i:s')],[$pk=>$id]);
            $this->queueActivity($kid,$actor); $this->audit($actor,'delete_'.$kind,$r['source_uuid']);
        });
    }
    public function savePhoto($actor,$detail,$file,$p) {
        $this->writeAccess($actor);
        if (!isset($file['error']) || $file['error']!==UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) throw new InvalidArgumentException('Unggah foto gagal.');
        return $this->transaction(function() use($actor,$detail,$file,$p) {
            $d=$this->db->get_where('infrastruktur_detail',['id_detail'=>$detail,'deleted_at'=>null])->row_array();
            if (!$d) throw new DomainException('Detail fisik tidak ditemukan.'); $k=$this->activity($actor,$d['id_kegiatan']);
            $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
            $types=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
            if (!isset($types[$mime]) || filesize($file['tmp_name'])>$this->cfg['max_photo_bytes'] || !getimagesize($file['tmp_name'])) throw new InvalidArgumentException('Foto harus JPEG/PNG/WebP, maksimal 5 MB.');
            $sha=hash_file('sha256',$file['tmp_name']); $name=$sha.'.'.$types[$mime]; $path=$this->storage();
            if (!is_file($path.'/'.$name) && !move_uploaded_file($file['tmp_name'],$path.'/'.$name)) throw new RuntimeException('Berkas foto gagal disimpan.');
            // Content addressed blobs are retained for retries/old approved snapshots.
            $a=['source_uuid'=>Pemkab_rules::uuid(),'id_detail'=>$detail,'file_dokumentasi'=>$name,'sha256'=>$sha,'mime'=>$mime,'ukuran'=>filesize($path.'/'.$name),'tanggal'=>Pemkab_rules::date($p['tanggal']??'',null,true),'keterangan'=>Pemkab_rules::text($p['keterangan']??'',500,false)];
            // Same file may occur in several details; filename deliberately uses a UUID copy.
            $a['file_dokumentasi']=$a['source_uuid'].'.'.$types[$mime];
            if (!copy($path.'/'.$name,$path.'/'.$a['file_dokumentasi'])) throw new RuntimeException('Salinan foto gagal.');
            $this->db->insert('infrastruktur_dokumentasi',$a); $this->queueActivity($k['id_kegiatan'],$actor);
        });
    }
    public function storage() {
        $path=$this->cfg['storage_path'];
        if (!is_dir($path) && !mkdir($path,0700,true)) throw new RuntimeException('Direktori penyimpanan tidak dapat dibuat.');
        $real=realpath($path); $web=realpath(FCPATH);
        $documentRoot=!empty($_SERVER['DOCUMENT_ROOT'])?realpath($_SERVER['DOCUMENT_ROOT']):false;
        if (!$real || !$web || $real===$web || strpos($real,$web.DIRECTORY_SEPARATOR)===0 || ($documentRoot && ($real===$documentRoot || strpos($real,$documentRoot.DIRECTORY_SEPARATOR)===0))) throw new RuntimeException('Direktori foto harus di luar direktori web aplikasi.'); return $real;
    }
    public function rows($actor,$year,$month,$opd=0,$filter='') {
        $year=Pemkab_rules::integer($year,2000,2100); $month=Pemkab_rules::integer($month,1,12);
        $start=sprintf('%04d-01-01',$year); $end=(new DateTimeImmutable(sprintf('%04d-%02d-01',$year,$month)))->modify('+1 month')->format('Y-m-d');
        $q="SELECT k.*,m.id_opd_pemkab,COALESCE(ot.nama_opd,o.nama_opd) nama_opd,
          COALESCE((SELECT SUM(u.nilai) FROM infrastruktur_keuangan u WHERE u.id_kegiatan=k.id_kegiatan AND u.deleted_at IS NULL AND u.tgl_realisasi>=? AND u.tgl_realisasi<?),0) realisasi_periode,
          COALESCE((SELECT f.realisasi FROM infrastruktur_detail f WHERE f.id_kegiatan=k.id_kegiatan AND f.deleted_at IS NULL AND f.tanggal>=? AND f.tanggal<? ORDER BY f.tanggal DESC,f.id_detail DESC LIMIT 1),0) fisik_periode
          FROM infrastruktur_wilayah k JOIN pemkab_kegiatan_opd m ON m.id_kegiatan=k.id_kegiatan JOIN pemkab_opd o ON o.id_opd_pemkab=m.id_opd_pemkab
          LEFT JOIN pemkab_opd_tahun ot ON ot.id_opd_pemkab=o.id_opd_pemkab AND ot.tahun=k.tahun
          WHERE k.id_kabupaten=? AND k.tahun=? AND k.deleted_at IS NULL";
        $bind=[$start,$end,$start,$end,$this->installation()['id_kabupaten'],$year];
        if ($actor['role']==='opd') { $q.=' AND m.id_opd_pemkab=?'; $bind[]=$actor['id_opd_pemkab']; }
        if ($opd) { $q.=' AND m.id_opd_pemkab=?'; $bind[]=(int)$opd; }
        $filters=['bkp'=>"k.status_1='Y'",'infrastruktur'=>"k.status_3='Y'",'tkd'=>"k.tkd='Y'",'psd'=>"k.psd IS NOT NULL",'phtc'=>"k.phtc IS NOT NULL"];
        if (isset($filters[$filter])) $q.=' AND '.$filters[$filter]; return $this->db->query($q.' ORDER BY k.keperluan,k.id_kegiatan',$bind)->result_array();
    }
    public function saveFinance($actor,$p) {
        $this->admin($actor);
        return $this->transaction(function() use($actor,$p) {
            $kind=$p['jenis']; if (!in_array($kind,['anggaran','apbd','target','lra','mandatory','tkdd','kawasan'],true)) throw new InvalidArgumentException('Kategori tidak valid.');
            $id=(int)($p['id']??0); $old=$id?$this->db->get_where('pk_finance',['id'=>$id])->row_array():null;
            if ($id && !$old) throw new DomainException('Data tidak ditemukan.');
            $a=['jenis'=>$kind,'tahun'=>Pemkab_rules::integer($p['tahun'],2000,2100),'bulan'=>Pemkab_rules::integer($p['bulan'],0,12),'status_apbd'=>$p['status_apbd'],'kode'=>Pemkab_rules::text($p['kode'],50),'nama'=>Pemkab_rules::text($p['nama'],180),'nilai'=>Pemkab_rules::money($p['nilai']),'deleted_at'=>null];
            if (!in_array($a['status_apbd'],['murni','perubahan'],true)) throw new InvalidArgumentException('Status APBD tidak valid.');
            if (in_array($kind,['target','lra'],true) && !$a['bulan']) throw new InvalidArgumentException('Bulan wajib diisi.');
            $dupe=$this->db->get_where('pk_finance',array_intersect_key($a,array_flip(['jenis','tahun','bulan','status_apbd','kode'])))->row_array();
            if ($dupe && (int)$dupe['id']!==$id) throw new InvalidArgumentException('Periode/kode sudah ada; ubah baris yang tersedia.');
            if ($id) $this->db->update('pk_finance',$a,['id'=>$id]); else { $a['source_uuid']=Pemkab_rules::uuid(); $this->db->insert('pk_finance',$a); $id=$this->db->insert_id(); }
            $row=$this->db->get_where('pk_finance',['id'=>$id])->row_array(); $this->queue('keuangan_daerah',$row['source_uuid'],$row,$actor); return $id;
        });
    }
    public function approve($actor,$id) {
        $this->admin($actor); return $this->transaction(function() use($actor,$id) {
            $e=$this->db->get_where('pk_outbox',['id'=>$id,'status'=>'awaiting'])->row_array(); if (!$e) throw new InvalidArgumentException('Versi tidak menunggu persetujuan.');
            $this->db->update('pk_outbox',['status'=>'pending','approved_by'=>$actor['id_user'],'approved_at'=>gmdate('Y-m-d H:i:s')],['id'=>$id]);
            $this->audit($actor,'approve',$e['source_uuid'],['event_id'=>$e['event_id'],'checksum'=>$e['checksum']]);
        });
    }
}
