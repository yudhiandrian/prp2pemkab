<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pemkab_legacy
{
    private $s;
    public function __construct() { $ci =& get_instance(); $ci->load->library('Pemkab_service'); $this->s=$ci->pemkab_service; }
    private function mapped($table,$row,$id) {
        $m=$this->s->db->get_where('pk_legacy_map',['source_table'=>$table,'source_id'=>$row[$id]])->row_array();
        if ($m && $m['source_checksum']!==hash('sha256',Pemkab_rules::json($row))) throw new RuntimeException('Sumber '.$table.' #'.$row[$id].' berubah setelah migrasi; perlu tinjauan.'); return $m;
    }
    private function remember($table,$row,$id,$uuid) {
        $this->s->db->insert('pk_legacy_map',['source_table'=>$table,'source_id'=>$row[$id],'target_uuid'=>$uuid,'source_checksum'=>hash('sha256',Pemkab_rules::json($row))]);
    }
    public function run($year,$decisions=[],$apply=false) {
        $s=$this->s; $year=Pemkab_rules::integer($year,2000,2100); $s->installation();
        $actor=['id_user'=>0,'role'=>'admin','id_opd_pemkab'=>null];
        // Dry run is SELECT/file checks only. Apply recomputes the complete plan under the write lock.
        $fn=function() use($s,$year,$decisions,$apply,$actor) {
            $report=['tahun'=>$year,'mode'=>$apply?'apply':'dry-run','source_count'=>0,'eligible'=>0,'already_mapped'=>0,'migrated'=>0,'source_pagu'=>0,'eligible_pagu'=>0,'eligible_nilai'=>0,'eligible_keuangan'=>0,'photos_verified'=>0,'exceptions'=>[],'items'=>[]];
            $activities=$s->db->order_by('id_kontrak')->get_where('ta_kontrak',['tahun'=>$year])->result_array();
            foreach ($activities as $k) {
                $report['source_count']++; $report['source_pagu']+=(float)$k['pagu']; $id=$k['id_kontrak'];
                $item=['source_id'=>$id,'keperluan'=>$k['keperluan'],'problems'=>[]];
                $mapped=$this->mapped('ta_kontrak',$k,'id_kontrak');
                $decision=$decisions['activities'][(string)$id]??null;
                if (!$decision || !in_array($decision['st_kontrak']??'', ['B','P','S'],true) || !in_array($decision['status_1']??'', ['Y','N'],true) || !in_array($decision['status_3']??'', ['Y','N'],true)) $item['problems'][]='Konfirmasi status kontrak dan arti flag BKP/infrastruktur diperlukan.';
                $opd=$s->db->get_where('data_skpd',['id_skpd'=>$k['kd_keg']])->row_array();
                if ($opd) $this->mapped('data_skpd',$opd,'id_skpd');
                if (!$opd) $item['problems'][]='ID OPD asal tidak ditemukan; pemetaan manual diperlukan.';
                $ot=$opd?$s->db->get_where('data_skpd_tahun',['id_skpd'=>$opd['id_skpd'],'tahun'=>$year])->result_array():[];
                if (count($ot)!==1) $item['problems'][]='Snapshot OPD tahun asal tidak ada atau ambigu.';
                $fisik=$s->db->order_by('id_kegiatan_detail')->get_where('data_kegiatan_detail',['id_kegiatan'=>$id])->result_array();
                $uang=$s->db->order_by('id_real')->get_where('data_kontrak_real',['id_kontrak'=>$id])->result_array();
                $photos=[]; $total=0;
                try {
                    Pemkab_rules::text(html_entity_decode($k['keperluan'],ENT_QUOTES,'UTF-8'),255); Pemkab_rules::money($k['pagu']); Pemkab_rules::money($k['nilai']);
                    if ((float)$k['nilai']>(float)$k['pagu']) throw new InvalidArgumentException('Kontrak melebihi pagu.');
                    if ($k['tgl_kontrak'] && $k['tgl_kontrak']!=='0000-00-00') Pemkab_rules::date($k['tgl_kontrak']);
                } catch(Throwable $e) {$item['problems'][]=$e->getMessage();}
                foreach ($uang as $u) {
                    if (!$this->mapped('data_kontrak_real',$u,'id_real') && $mapped) throw new RuntimeException('Transaksi sumber baru setelah migrasi; perlu tinjauan ulang.');
                    try { Pemkab_rules::date($u['tgl_realisasi'],$year); Pemkab_rules::money($u['nilai']); $total+=(float)$u['nilai']; }
                    catch(Throwable $e) { $item['problems'][]='Keuangan #'.$u['id_real'].': '.$e->getMessage(); }
                }
                if ($total>(float)$k['nilai']) $item['problems'][]='Jumlah keuangan melebihi kontrak.';
                foreach ($fisik as $d) {
                    if (!$this->mapped('data_kegiatan_detail',$d,'id_kegiatan_detail') && $mapped) throw new RuntimeException('Catatan fisik sumber baru setelah migrasi; perlu tinjauan ulang.');
                    try { Pemkab_rules::date($decisions['physical_dates'][(string)$d['id_kegiatan_detail']]??'', $year); Pemkab_rules::money($d['realisasi']); if ((float)$d['realisasi']>100) throw new InvalidArgumentException('Fisik melebihi 100%.'); }
                    catch(Throwable $e) { $item['problems'][]='Fisik #'.$d['id_kegiatan_detail'].': perlu tanggal aktual terverifikasi dan realisasi 0–100.'; }
                    $docs=$s->db->order_by('id_dokumentasi')->get_where('kegiatan_dokumentasi',['id_kegiatan_detail'=>$d['id_kegiatan_detail']])->result_array();
                    foreach ($docs as $doc) {
                        $docMap=$this->mapped('kegiatan_dokumentasi',$doc,'id_dokumentasi');
                        if (!$docMap && $mapped) throw new RuntimeException('Dokumentasi sumber baru setelah migrasi; perlu tinjauan ulang.');
                        $file=FCPATH.'uploads/dokumentasi/'.basename($doc['file_dokumentasi']);
                        $mime=is_file($file)?(new finfo(FILEINFO_MIME_TYPE))->file($file):'';
                        if (!in_array($mime,['image/jpeg','image/png','image/webp'],true) || !getimagesize($file) || filesize($file)>$s->cfg['max_photo_bytes']) $item['problems'][]='Foto #'.$doc['id_dokumentasi'].' hilang/tidak valid/melebihi 5 MB.';
                        else {
                            if ($docMap) {
                                $destDoc=$s->db->get_where('infrastruktur_dokumentasi',['source_uuid'=>$docMap['target_uuid']])->row_array();
                                if (!$destDoc || hash_file('sha256',$file)!==$destDoc['sha256']) throw new RuntimeException('Isi berkas sumber berubah setelah migrasi.');
                            }
                            $report['photos_verified']++; $photos[$d['id_kegiatan_detail']][]=['row'=>$doc,'path'=>$file,'sha256'=>hash_file('sha256',$file),'mime'=>$mime]; }
                    }
                }
                if ($mapped) { $report['already_mapped']++; $item['status']='already_mapped'; $report['items'][]=$item; continue; }
                if ($item['problems']) { $item['status']='pending'; $report['exceptions'][]=$item; $report['items'][]=$item; continue; }
                $report['eligible']++; $report['eligible_pagu']+=(float)$k['pagu']; $report['eligible_nilai']+=(float)$k['nilai']; $report['eligible_keuangan']+=$total; $item['status']='eligible';
                if ($apply) {
                    $om=$this->mapped('data_skpd',$opd,'id_skpd');
                    if ($om) {
                        $newOpd=$s->db->get_where('pemkab_opd',['source_uuid'=>$om['target_uuid']])->row_array();
                        if (!$newOpd) throw new RuntimeException('Peta OPD rusak.'); $oid=$newOpd['id_opd_pemkab'];
                        $s->saveOpd($actor,['id'=>$oid,'tahun'=>$year,'nama_opd'=>$ot[0]['nama_skpd']]);
                    } else {
                        // Name collision requires explicit review instead of silently pairing existing OPD.
                        if ($s->db->get_where('pemkab_opd',['nama_opd'=>$ot[0]['nama_skpd']])->row_array()) throw new RuntimeException('Nama OPD tujuan sudah ada tanpa peta migrasi.');
                        $oid=$s->saveOpd($actor,['tahun'=>$year,'nama_opd'=>$ot[0]['nama_skpd']]); $o=$s->db->get_where('pemkab_opd',['id_opd_pemkab'=>$oid])->row_array(); $this->remember('data_skpd',$opd,'id_skpd',$o['source_uuid']);
                    }
                    $kid=$s->saveActivity($actor,['tahun'=>$year,'id_opd_pemkab'=>$oid,'keperluan'=>html_entity_decode($k['keperluan'],ENT_QUOTES,'UTF-8'),'pagu'=>$k['pagu'],'nilai'=>$k['nilai'],'tgl_kontrak'=>$k['tgl_kontrak']==='0000-00-00'?'':$k['tgl_kontrak'],'waktu'=>$k['waktu'],'lokasi_pekerjaan'=>$k['lokasi_pekerjaan'],'koordinat'=>$k['koordinat'],'st_kontrak'=>$decision['st_kontrak'],'status_1'=>$decision['status_1'],'status_3'=>$decision['status_3']]);
                    $new=$s->db->get_where('infrastruktur_wilayah',['id_kegiatan'=>$kid])->row_array(); $this->remember('ta_kontrak',$k,'id_kontrak',$new['source_uuid']);
                    foreach ($uang as $u) {
                        $uid=$s->saveRealization($actor,'keuangan',['id_kegiatan'=>$kid,'nilai'=>$u['nilai'],'tanggal'=>$u['tgl_realisasi'],'keterangan'=>$u['keterangan']]);
                        $n=$s->db->get_where('infrastruktur_keuangan',['id_keuangan'=>$uid])->row_array(); $this->remember('data_kontrak_real',$u,'id_real',$n['source_uuid']);
                    }
                    foreach ($fisik as $d) {
                        $did=$s->saveRealization($actor,'fisik',['id_kegiatan'=>$kid,'nilai'=>$d['realisasi'],'tanggal'=>$decisions['physical_dates'][(string)$d['id_kegiatan_detail']],'keterangan'=>$d['keterangan_target']]);
                        $n=$s->db->get_where('infrastruktur_detail',['id_detail'=>$did])->row_array(); $this->remember('data_kegiatan_detail',$d,'id_kegiatan_detail',$n['source_uuid']);
                        foreach (($photos[$d['id_kegiatan_detail']]??[]) as $photo) {
                            $uuid=Pemkab_rules::uuid(); $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$photo['mime']]; $name=$uuid.'.'.$ext; $dest=$s->storage().'/'.$name;
                            if (!copy($photo['path'],$dest) || hash_file('sha256',$dest)!==$photo['sha256']) throw new RuntimeException('Salinan foto/checksum gagal.');
                            $s->db->insert('infrastruktur_dokumentasi',['source_uuid'=>$uuid,'id_detail'=>$did,'file_dokumentasi'=>$name,'sha256'=>$photo['sha256'],'mime'=>$photo['mime'],'ukuran'=>filesize($dest),'tanggal'=>null,'keterangan'=>'Migrasi foto lama; tanggal kejadian tidak tersedia.']);
                            $this->remember('kegiatan_dokumentasi',$photo['row'],'id_dokumentasi',$uuid);
                        }
                    }
                    $s->queueActivity($kid,$actor); $report['migrated']++; $item['status']='migrated';
                }
                $report['items'][]=$item;
            }
            // Orphans are surfaced, never silently discarded.
            foreach ([['data_kontrak_real','ta_kontrak','id_kontrak','id_kontrak'],['data_kegiatan_detail','ta_kontrak','id_kegiatan','id_kontrak'],['kegiatan_dokumentasi','data_kegiatan_detail','id_kegiatan_detail','id_kegiatan_detail']] as $rel) {
                list($child,$parent,$fk,$pk)=$rel;
                $n=$s->db->query("SELECT COUNT(*) n FROM $child c LEFT JOIN $parent p ON c.$fk=p.$pk WHERE p.$pk IS NULL")->row_array(); $report['orphans'][$child]=(int)$n['n'];
            }
            if ($apply) {
                $s->db->insert('pk_migration_runs',['tahun'=>$year,'status'=>'completed','report'=>Pemkab_rules::json($report),'created_at'=>gmdate('Y-m-d H:i:s')]);
                $s->audit($actor,'legacy_migration',null,['tahun'=>$year,'migrated'=>$report['migrated']]);
            }
            return $report;
        };
        return $apply?$s->transaction($fn):$fn();
    }
}
