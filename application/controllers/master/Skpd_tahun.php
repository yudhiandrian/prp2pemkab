<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Skpd_tahun extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model', 'users');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function index($tahun=NULL)
    {
        if ($this->akses['akses'] == 'Y') {
            if($tahun==NULL){$tahun=date('Y');}
            $data = [
                "menu_active" => "master_data",
                "submenu_active" => "skpd-tahun",
                "tahun" => $tahun
            ];
            $this->load->view('master/skpd_tahun/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load()
    {
        if ($this->akses['akses'] == 'Y') {
            $tahun = htmlspecialchars($this->input->post('tahun', TRUE));
            $result = $this->mquery->select_by("data_skpd_tahun", ['tahun'=>$tahun], "id_skpd ASC");
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                if ($this->akses['ubah'] == 'Y') 
                    {$edit = action_edit($r['id_data']);}
                else{$edit = "-";}

                if ($this->akses['hapus'] == 'Y') 
                    {
                        $cek_data = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd'=>$r['id_skpd']]);
                        if($cek_data!=0){$delete = "-";}else{
                        $delete = action_delete($r['id_data']);}
                    }
                else{$delete = "-";}

                $row = [
                    'no' => $no,
                    'nama_skpd' => $r['nama_skpd'],
                    'aksi' => $edit . ' ' . $delete
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function form()
    {
        if ($this->akses['tambah'] == 'Y' or $this->akses['ubah'] == 'Y') {
            $opsi = htmlspecialchars($this->input->post('opsi', TRUE));
            if ($opsi == "add") {
                $tahun = htmlspecialchars($this->input->post('tahun', TRUE));
                $data = [
                    'tahun' => $tahun,
                    'data_skpd' => $this->mquery->select_data('data_skpd')
                ];
                $this->load->view('master/skpd_tahun/form_add', $data);
            } elseif ($opsi == "edit") {
                $id = htmlspecialchars($this->input->post('id', TRUE));
                $data_skpd_tahun=$this->mquery->select_id('data_skpd_tahun', ['id_data' => $id]);
                $data = [
                    'result_skpd' => $this->mquery->select_id('data_skpd', ['id_skpd' => $data_skpd_tahun['id_skpd']]),
                    'data_skpd_tahun' => $data_skpd_tahun
                ];
                $this->load->view('master/skpd_tahun/form_edit', $data);
            } else {
                $this->load->view('blocked');
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('nama_skpd', 'Nama SKPD', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'tahun' => form_error('tahun'),
            'nama_skpd' => form_error('nama_skpd')
        ];
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function add()
    {
        if ($this->akses['tambah'] == 'Y') {
            $this->_rule_form();
            if ($this->form_validation->run() == false) {
                $this->_send_error();
            } else {
                $post = $this->input->post(null, TRUE);
                $id_skpd=htmlspecialchars($post['nama_skpd']);
                $temp = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
                $array =  [
                    'id_skpd' => $id_skpd,
                    'nama_skpd' => $temp['nama_skpd'],
                    'tahun' => htmlspecialchars($post['tahun'])
                ];
                $string = ['data_skpd_tahun' => $array];
                $log = simpan_log("insert data_skpd_tahun", json_encode($string));
                $res = $this->mquery->insert_data('data_skpd_tahun', $array, $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            $data = ['status' => FALSE, 'pesan' => 'blocked'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    function edit()
    {
        if ($this->akses['ubah'] == 'Y') {
            $this->_rule_form();
            if ($this->form_validation->run() == false) {
                $this->_send_error();
            } else {
                $post = $this->input->post(null, TRUE);
                $id_data = htmlspecialchars($post['id_data']);
                $array =  [
                    'nama_skpd' => htmlspecialchars($post['nama_skpd'])
                ];
                $temp = $this->mquery->select_id('data_skpd_tahun', ['id_data' => $id_data]);
                $string = ['data_skpd_tahun' => ['old' => $temp, 'new' => $array]];
                $log = simpan_log("update data_skpd_tahun", json_encode($string));
                $res = $this->mquery->update_data('data_skpd_tahun', $array, ['id_data' => $id_data], $log);
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        } else {
            $data = ['status' => FALSE, 'pesan' => 'blocked'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function delete()
    {
        if ($this->akses['hapus'] == 'Y') {
            $id = htmlspecialchars($this->input->post('id', TRUE));
            $temp = $this->mquery->select_id('data_skpd_tahun', ['id_data' => $id]);
            $string = ['data_skpd_tahun' => $temp];
            $log = simpan_log("delete data_skpd_tahun", json_encode($string));
            $res = $this->mquery->delete_data('data_skpd_tahun', ['id_data' => $id], $log);
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }

}
