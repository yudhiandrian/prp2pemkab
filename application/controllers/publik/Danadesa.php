<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Danadesa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model', 'master');
        $this->load->model('Danadesa_model', 'danadesa');
        $this->load->model('M_fungsi', 'fungsi');
    }

    public function index($tahun)
    {
        $id_kabupaten=34;
        $result_kabupaten_id = $this->mquery->select_id('ta_kabupaten',['id_kabupaten' => $id_kabupaten]);
        $data = [
            "menu_active" => "dana_desa",
            "submenu_active" => null,
            "tahun_data" => $tahun,
            "tahun" => $tahun,
            "result_kabupaten_nama" => $result_kabupaten_id['nama_kabupaten'],
            "id_kabupaten" => $result_kabupaten_id['id_kabupaten'],
            "result_bulan" => $this->mquery->select_data('bulan', 'id_bulan ASC')
        ];
        $this->load->view('publik/danadesa/view', $data);
    }

}
