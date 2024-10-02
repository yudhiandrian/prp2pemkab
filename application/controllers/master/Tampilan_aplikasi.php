<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tampilan_aplikasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function index()
    {
        if ($this->akses['akses'] == 'Y') {
            $data = [
                "menu_active" => "master_data",
                "submenu_active" => "tampilan-aplikasi"
            ];
            $this->load->view('master/tampilan_aplikasi/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load()
    {
        $data = [
            "row_tampilan" => $this->mquery->select_id('tbl_tampilan', ['id_data' => 1])
        ];
        $this->load->view('master/tampilan_aplikasi/load', $data);
    }

    public function form()
    {
        if ($this->akses['tambah'] == 'Y' or $this->akses['ubah'] == 'Y') {
            $opsi = htmlspecialchars($this->input->post('opsi', TRUE));
            if ($opsi == "add") {
                $this->load->view('master/tampilan_aplikasi/form_add');
            } elseif ($opsi == "edit") {
                $id = htmlspecialchars($this->input->post('id', TRUE));
                $data = [
                    'row_tampilan' => $this->mquery->select_id('tbl_tampilan', ['id_data' => 1])
                ];
                $this->load->view('master/tampilan_aplikasi/form_edit', $data);
            } elseif ($opsi == "gambar") {
                $id = htmlspecialchars($this->input->post('id', TRUE));
                $data = [
                    'target' => $id,
                    'row_tampilan' => $this->mquery->select_id('tbl_tampilan', ['id_data' => 1])
                ];
                $this->load->view('master/tampilan_aplikasi/form_foto', $data);
            } else {
                $this->load->view('blocked');
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'title' => form_error('title')
        ];
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function edit()
    {
        if ($this->akses['ubah'] == 'Y') {
            $this->_rule_form();
            if ($this->form_validation->run() == false) {
                $this->_send_error();
            } else {
                $post = $this->input->post(null, TRUE);
                $array =  [
                    'title' => htmlspecialchars($post['title']),
                    'copyright' => htmlspecialchars($post['copyright']),
                    'judul1' => htmlspecialchars($post['judul1']),
                    'judul2' => htmlspecialchars($post['judul2']),
                    'sub1' => htmlspecialchars($post['sub1']),
                    'sub2' => htmlspecialchars($post['sub2']),
                    'bagian1' => htmlspecialchars($post['bagian1']),
                    'bagian2' => htmlspecialchars($post['bagian2']),
                    'link' => htmlspecialchars($post['link']),
                    'koordinat' => htmlspecialchars($post['koordinat']),
                    'zoom' => htmlspecialchars($post['zoom'])
                ];
                $temp = $this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
                $string = ['tbl_tampilan' => ['old' => $temp, 'new' => $array]];
                $log = simpan_log("update tbl_tampilan", json_encode($string));
                $res = $this->mquery->update_data('tbl_tampilan', $array, ['id_data' => 1], $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            $data = ['status' => FALSE, 'pesan' => 'blocked'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
    
    function edit_foto()
    {
        $post = $this->input->post(null, TRUE);
        $target = htmlspecialchars($post['target']);
            $config['upload_path']      = "./uploads/";
            $config['allowed_types']    = 'jpg|png|jpeg';
            $config['file_name']        = $target . '-' . date("Ymd-His");
            $config['max_size']         = 512;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('foto')) {
                $errors = [
                    'foto' => $this->upload->display_errors()
                ];
                $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            } else {
                $gbr = $this->upload->data();
                $update =  [
                    $target => $gbr['file_name']
                ];
                $temp = $this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
                $string = ['tbl_tampilan' => $update];
                $log = simpan_log("tbl_tampilan", json_encode($string));
                $res = $this->mquery->update_data('tbl_tampilan', $update, ['id_data' => 1], $log);
                if ($res > 0) {
                    if (($res > 0) && ($temp[$target] != "no-image.png")) {
                        if (file_exists(FCPATH . "uploads/" . $temp[$target])) {
                            unlink(FCPATH . "uploads/" . $temp[$target]);
                        }
                    }
                }
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
    }
}
