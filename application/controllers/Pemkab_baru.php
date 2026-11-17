<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pemkab_baru extends CI_Controller
{
    private $s,$actor;
    public function __construct() {
        parent::__construct(); $this->db->db_debug=false;
        if (!$this->session->userdata('user_id') || $this->session->userdata('apps')!=='prp2sumut' || (int)$this->session->userdata('timeout')<time()) { redirect('login'); exit; }
        $this->load->library('Pemkab_service'); $this->s=$this->pemkab_service;
        try { $this->s->installation(); $this->actor=$this->s->actor($this->session->userdata('user_id')); }
        catch(Throwable $e) { show_error(htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8'),403); exit; }
        $this->session->set_userdata('timeout',time()+900);
        if (!$this->session->userdata('pk_csrf')) $this->session->set_userdata('pk_csrf',bin2hex(random_bytes(32)));
    }
    private function post() {
        if ($this->input->method()!=='post' || !is_string($this->input->post('pk_csrf')) || !hash_equals($this->session->userdata('pk_csrf'),$this->input->post('pk_csrf'))) { show_error('Permintaan tidak valid. Muat ulang halaman.',403); exit; }
        return $this->input->post(null,false);
    }
    private function page($view,$data=[]) {
        $data+=['inst'=>$this->s->installation(),'actor'=>$this->actor,'csrf'=>$this->session->userdata('pk_csrf'),'message'=>$this->session->flashdata('pk_message'),'view'=>$view];
        $this->load->view('pemkab_baru/layout',$data);
    }
    public function index() {
        $year=(int)($this->input->get('tahun')?:date('Y')); $month=(int)($this->input->get('bulan')?:date('n')); $opd=(int)$this->input->get('opd'); $filter=(string)$this->input->get('filter');
        try {
            $rows=$this->s->rows($this->actor,$year,$month,$opd,$filter); $opds=$this->opds();
            $this->page('index',compact('rows','opds','year','month','opd','filter')+['rekap'=>Pemkab_rules::rekap($rows)]);
        } catch(Throwable $e) { show_error(htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8'),422); }
    }
    private function opds() {
        if ($this->actor['role']==='opd') $this->db->where('id_opd_pemkab',$this->actor['id_opd_pemkab']);
        return $this->db->order_by('nama_opd')->get('pemkab_opd')->result_array();
    }
    public function form($id=0) {
        try { $this->s->writeAccess($this->actor); $k=$id?$this->s->activity($this->actor,$id):['id_kegiatan'=>0,'tahun'=>date('Y'),'st_kontrak'=>'B','pagu'=>'0','nilai'=>'0','status_1'=>'N','status_3'=>'Y','tkd'=>'N'];
            $this->page('form',['k'=>$k,'opds'=>$this->opds(),'references'=>$this->db->order_by('nama')->get_where('pk_references',['aktif'=>'Y'])->result_array()]);
        } catch(Throwable $e) { show_error(htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8'),403); }
    }
    public function detail($id) {
        try {
            $k=$this->s->activity($this->actor,$id); $snap=$this->s->snapshot($id);
            $this->page('detail',['k'=>$k,'snap'=>$snap]);
        } catch(Throwable $e) { show_error(htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8'),403); }
    }
    public function save($kind) {
        $p=$this->post(); $back='pemkab-baru';
        try {
            switch ($kind) {
                case 'kegiatan': $id=$this->s->saveActivity($this->actor,$p); $back.='/detail/'.$id; break;
                case 'opd': $this->s->saveOpd($this->actor,$p); $back.='/opd'; break;
                case 'fisik': case 'keuangan': $this->s->saveRealization($this->actor,$kind,$p); $back.='/detail/'.(int)$p['id_kegiatan']; break;
                case 'foto': $this->s->savePhoto($this->actor,(int)$p['id_detail'],$_FILES['foto']??[],$p); $back.='/detail/'.(int)$p['id_kegiatan']; break;
                case 'finance': $this->s->saveFinance($this->actor,$p); $back.='/finance'; break;
                default: throw new InvalidArgumentException('Tindakan tidak valid.');
            }
            $this->session->set_flashdata('pk_message','Data tersimpan. Perubahan tercatat dalam antrean sesuai kebijakan persetujuan.');
        } catch(Throwable $e) { $this->session->set_flashdata('pk_message','Gagal: '.$e->getMessage()); $this->session->set_flashdata('pk_old_input',$p); if ($kind==='kegiatan') $back='pemkab-baru/form/'.(int)($p['id']??0); else if (in_array($kind,['fisik','keuangan','foto'],true)) $back='pemkab-baru/detail/'.(int)($p['id_kegiatan']??0); else if ($kind==='opd') $back='pemkab-baru/opd'; else if ($kind==='finance') $back='pemkab-baru/finance'; }
        redirect($back);
    }
    public function remove($kind,$id) {
        $this->post(); try { $this->s->remove($this->actor,$kind,(int)$id); $msg='Data ditandai dihapus; riwayat dan berkas tetap disimpan untuk sinkronisasi.'; } catch(Throwable $e) { $msg='Gagal: '.$e->getMessage(); }
        $this->session->set_flashdata('pk_message',$msg); redirect('pemkab-baru');
    }
    public function file($id) {
        try {
            $d=$this->db->get_where('infrastruktur_dokumentasi',['id_dokumentasi'=>(int)$id,'deleted_at'=>null])->row_array(); if (!$d) throw new DomainException('Foto tidak ditemukan.');
            $f=$this->db->get_where('infrastruktur_detail',['id_detail'=>$d['id_detail'],'deleted_at'=>null])->row_array(); if (!$f) throw new DomainException('Detail tidak tersedia.'); $this->s->activity($this->actor,$f['id_kegiatan']);
            $path=$this->s->storage().'/'.basename($d['file_dokumentasi']); if (!is_file($path)) throw new RuntimeException('Berkas tidak ditemukan.');
            header('Content-Type: '.$d['mime']); header('X-Content-Type-Options: nosniff'); header('Cache-Control: private, no-store'); readfile($path);
        } catch(Throwable $e) { show_error(htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8'),403); }
    }
    public function opd() { $this->page('opd',['opds'=>$this->opds(),'snapshots'=>$this->db->order_by('tahun','DESC')->get('pemkab_opd_tahun')->result_array()]); }
    public function finance() {
        if ($this->actor['role']==='opd') { show_error('Rekap keuangan daerah hanya untuk admin/pemantau daerah.',403); return; }
        $year=(int)($this->input->get('tahun')?:date('Y'));
        $this->page('finance',['year'=>$year,'rows'=>$this->db->order_by('jenis,tahun,bulan,kode')->get_where('pk_finance',['tahun'=>$year,'deleted_at'=>null])->result_array()]);
    }
    public function sync() {
        try { $this->s->admin($this->actor); $this->page('sync',['events'=>$this->db->order_by('id','DESC')->limit(200)->get('pk_outbox')->result_array(),'attempts'=>$this->db->order_by('id','DESC')->limit(30)->get('pk_sync_attempts')->result_array()]); }
        catch(Throwable $e) { show_error('Hanya admin daerah.',403); }
    }
    public function review($id) {
        try { $this->s->admin($this->actor); $e=$this->db->get_where('pk_outbox',['id'=>(int)$id])->row_array(); if (!$e) throw new DomainException('Versi tidak ditemukan.'); $this->page('review',['event'=>$e]); }
        catch(Throwable $e) { show_error('Versi tidak dapat dibuka.',403); }
    }
    public function approve($id) {
        $this->post(); try { $this->s->approve($this->actor,(int)$id); $msg='Versi disetujui dan masuk antrean kirim.'; } catch(Throwable $e) { $msg=$e->getMessage(); }
        $this->session->set_flashdata('pk_message',$msg); redirect('pemkab-baru/sync');
    }
    public function export() {
        $rows=$this->s->rows($this->actor,$this->input->get('tahun')?:date('Y'),$this->input->get('bulan')?:date('n'),(int)$this->input->get('opd'),(string)$this->input->get('filter'));
        header('Content-Type: text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename="kegiatan-pemkab.csv"'); $f=fopen('php://output','w');
        fputcsv($f,['OPD','Tahun','Kegiatan','Pagu','Kontrak','Realisasi Keuangan','Fisik (%)']);
        foreach ($rows as $r) { $row=[$r['nama_opd'],$r['tahun'],$r['keperluan'],$r['pagu'],$r['nilai'],$r['realisasi_periode'],$r['fisik_periode']]; foreach ($row as &$v) if (preg_match('/^[=+@\-\t\r]/',(string)$v)) $v="'".$v; unset($v); fputcsv($f,$row); } fclose($f);
    }
}
