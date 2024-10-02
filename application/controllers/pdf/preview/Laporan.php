<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_fungsi', 'fungsi');
        $this->akses = is_logged_in();
        $this->user = is_logged_in();
    }

    public function jumlah_laporan_keuangan($tahun)
    {
        $tahun_now=date('Y');
        if($tahun==$tahun_now){$priode=fungsi_bulan(date('m'), $tahun_now);}
        else{$priode=fungsi_bulan(12, $tahun);}
        $row_skpd = $this->mquery->select_by("data_skpd_tahun", ['tahun'=>$tahun], "id_skpd ASC");
        $no = 0;
        $data = [
            "row_skpd" => $row_skpd,
            "tahun" => $tahun,
            "priode" => $priode
        ];
        $this->load->view('laporan/preview/jumlah_laporan_keuangan', $data);
    }

    public function data_lra_opd($tahun)
    {
        $tahun_now=date('Y');
        if($tahun==$tahun_now){$priode=fungsi_bulan(date('m'), $tahun_now);}
        else{$priode=fungsi_bulan(12, $tahun);}

        if ($this->user['is_skpd'] == 'Y') {
            $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
            $row_skpd = $this->mquery->select_by("data_skpd_tahun", ['id_skpd'=>$user['id_skpd'], 'tahun'=>$tahun]);
        } else {
            $row_skpd = $this->mquery->select_by("data_skpd_tahun", ['tahun'=>$tahun], "id_skpd ASC");
        }

        $no = 0;
        $data = [
            "row_skpd" => $row_skpd,
            "tahun" => $tahun,
            "priode" => $priode
        ];
        $this->load->view('laporan/preview/data_lra_opd', $data);
    }

    
    public function detail_rekap($tahun, $bulan)
    {
        $tahun_now=date('Y');
        if($tahun==$tahun_now){$priode=fungsi_bulan($bulan, $tahun_now);}
        else{$priode=fungsi_bulan($bulan, $tahun);}
        $data = [
            "tahun" => $tahun,
            "bulan" => $bulan,
            "priode" => $priode
        ];
        $this->load->view('laporan/preview/detail_rekap', $data);
    }

    public function detail_rekap2($tahun, $bulan)
    {
        $tahun_now=date('Y');
        if($tahun==$tahun_now){$priode=fungsi_bulan($bulan, $tahun_now);}
        else{$priode=fungsi_bulan($bulan, $tahun);}
        $no = 0;
        $data = [
            "tahun" => $tahun,
            "bulan" => $bulan,
            "priode" => $priode
        ];
        $this->load->view('laporan/preview/detail_rekap2', $data);
    }

    public function detail_lra($tahun, $encrypt_id)
    {
        $tahun_now=date('Y');
        $id_skpd = decrypt_url($encrypt_id);
        $result = $this->mquery->select_by('log_upload', ['id_skpd' => $id_skpd, 'tahun'=> $tahun, 'st_data'=> 2], "bulan ASC");
        $row_skpd = $this->mquery->select_id("data_skpd_tahun", ['id_skpd' => $id_skpd, 'tahun'=>$tahun]);
        $data = [
            "row_skpd" => $row_skpd,
            "result" => $result,
            "tahun" => $tahun,
            "encrypt_id" => $encrypt_id
        ];
        $this->load->view('laporan/preview/detail_lra', $data);
    }

    public function detail2_lra($tahun, $bulan, $encrypt_id)
    {
        $tahun_now=date('Y');
        $id_skpd = decrypt_url($encrypt_id);
        $result = $this->mquery->select_by('data_realisasi_detail_skpd', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $bulan], "kode_rekening ASC");
        $row_skpd = $this->mquery->select_id("data_skpd_tahun", ['id_skpd' => $id_skpd, 'tahun'=>$tahun], "id_skpd ASC");
        $data = [
            "row_skpd" => $row_skpd,
            "result" => $result,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "encrypt_id" => $encrypt_id
        ];
        $this->load->view('laporan/preview/detail2_lra', $data);
    }

}
