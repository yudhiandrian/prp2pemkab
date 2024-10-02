<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Koor_kegiatan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kegiatan_model', 'kegiatan');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function koordinat($tahun)
    {
        $data = [
            "tahun" => $tahun
        ];
        $this->load->view('kegiatan/koordinat', $data);
    }

    public function koordinat_skpd($tahun, $id_skpd)
    {
        $data = [
            "tahun" => $tahun,
            "id_skpd" => $id_skpd
        ];
        $this->load->view('kegiatan/koordinat_skpd', $data);
    }

    public function koordinat_detail($id_kontrak)
    {
        $data = [
            "id_kontrak" => $id_kontrak
        ];
        $this->load->view('kegiatan/koordinat_detail', $data);
    }

    public function form()
    {
        $opsi = $this->input->post('opsi', TRUE);
        if ($opsi == "add") {
            $id_kontrak = $this->input->post('id_kontrak', TRUE);
            $data = [
                'kontrak' => $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak])
            ];
            $this->load->view('kegiatan/detail/form_add_koordinat', $data);
        } else {
            $this->load->view('blocked');
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('koordinat', 'Koordinat', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'Koordinat' => form_error('Koordinat')
        ];
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function edit()
    {
        $this->_rule_form();
        if ($this->form_validation->run() == false) {
            $this->_send_error();
        } else {
            $post = $this->input->post(null, TRUE);
            $id_kontrak = htmlspecialchars($post['id_kontrak']);
            $array =  [
                'koordinat' => htmlspecialchars($post['koordinat']),
                'lokasi_pekerjaan' => htmlspecialchars($post['lokasi_pekerjaan'])
            ];
            $temp = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak]);
            $string = ['ta_kontrak' => ['old' => $temp, 'new' => $array]];
            $log = simpan_log("edit ta_kontrak", json_encode($string));
            $res = $this->mquery->update_data('ta_kontrak', $array, ['id_kontrak' => $id_kontrak], $log);
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

}
