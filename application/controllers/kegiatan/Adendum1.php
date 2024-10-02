<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Adendum1 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kegiatan_model', 'kegiatan');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function form()
    {
        $opsi = $this->input->post('opsi', TRUE);
        if ($opsi == "add") {
            $id_kontrak = $this->input->post('id_kontrak', TRUE);
            $data = [
                'kontrak' => $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak])
            ];
            $this->load->view('kegiatan/detail/form_adendum', $data);
        } else {
            $this->load->view('blocked');
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('adendum', 'Adendum', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'adendum' => form_error('adendum')
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
            $adendum = htmlspecialchars($post['adendum']);
            if($adendum==0){$st_adendum=0;}else{$st_adendum=1;}
            $array =  [
                'adendum' => $adendum,
                'keterangan' => htmlspecialchars($post['keterangan']),
                'st_adendum' => $st_adendum
            ];
            $temp = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak]);
            $string = ['ta_kontrak' => ['old' => $temp, 'new' => $array]];
            $log = simpan_log("edit ta_kontrak", json_encode($string));
            $res = $this->mquery->update_data('ta_kontrak', $array, ['id_kontrak' => $id_kontrak], $log);

            $sum_kontrak_real = $this->mquery->sum_data('data_kontrak_real', 'nilai', ['id_kontrak' => $id_kontrak]);
            $realisasi = $sum_kontrak_real['nilai'];

            $kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak]);

            if ($kontrak['nilai'] == 0) {$persen_real = 0;
            } else {
                if ($kontrak['adendum'] == 0) {$persen_real = hitung_persen($realisasi, $kontrak['nilai'], 2);}
                else {$persen_real = hitung_persen($realisasi, $kontrak['adendum'], 2);}
                $update_realisasi =  [
                    'realisasi' => $realisasi,
                    'persen_realisasi' => $persen_real
                ];
                $res = $this->db->update('ta_kontrak', $update_realisasi, ['id_kontrak' => $id_kontrak]);
            }

            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
}
