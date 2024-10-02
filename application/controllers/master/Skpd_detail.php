<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Skpd_detail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model', 'users');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function index($skpd_parent)
    {
        if ($this->akses['akses'] == 'Y') {
            $row_skpd = $this->mquery->select_id("data_skpd", ['id_skpd' => $skpd_parent]);
            $data = [
                "menu_active" => "master_data",
                "submenu_active" => "data-skpd",
                "skpd_parent" => $skpd_parent,
                "row_skpd" => $row_skpd
            ];
            $this->load->view('master/skpd_detail/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load()
    {
        if ($this->akses['akses'] == 'Y') {
            $skpd_parent = htmlspecialchars($this->input->post('skpd_parent', TRUE));
            $result = $this->mquery->select_by("data_skpd", ['skpd_parent' => $skpd_parent], "id_skpd ASC");
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $no++;
                if ($this->akses['ubah'] == 'Y') 
                    {$edit = action_edit($r['id_skpd']);}
                else{$edit = "-";}

                if ($this->akses['hapus'] == 'Y') 
                    {$delete = action_delete($r['id_skpd']);}
                else{$delete = "-";}

                $row = [
                    'no' => $no,
                    'nama_skpd' => $r['nama_skpd'],
                    'kd_urusan' => $r['kd_urusan'],
                    'kd_bidang' => $r['kd_bidang'],
                    'kd_unit' => $r['kd_unit'],
                    'kd_sub' => $r['kd_sub'],
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
            $skpd_parent = htmlspecialchars($this->input->post('skpd_parent', TRUE));
            if ($opsi == "add") {
                $row_skpd = $this->mquery->select_id("data_skpd", ['id_skpd' => $skpd_parent]);
                $data = [
                    'row_skpd' => $row_skpd,
                    'skpd_parent' => $skpd_parent
                ];
                $this->load->view('master/skpd_detail/form_add', $data);
            } elseif ($opsi == "edit") {
                $id = htmlspecialchars($this->input->post('id', TRUE));
                $data = [
                    'data_skpd' => $this->mquery->select_id('data_skpd', ['id_skpd' => $id])
                ];
                $this->load->view('master/skpd_detail/form_edit', $data);
            } else {
                $this->load->view('blocked');
            }
        } else {
            redirect(site_url('blocked'));
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('nama_unit', 'Nama Unit', 'required|trim');
        $this->form_validation->set_rules('kd_urusan', 'Kode Urusan', 'required|trim');
        $this->form_validation->set_rules('kd_bidang', 'Kode Bidang', 'required|trim');
        $this->form_validation->set_rules('kd_unit', 'Kode Unit', 'required|trim');
        $this->form_validation->set_rules('kd_sub', 'Kode Sub', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error($kode)
    {
        if($kode==1)
        {
            $errors = [
                'nama_unit' => form_error('nama_unit'),
                'kd_urusan' => form_error('kd_urusan'),
                'kd_bidang' => form_error('kd_bidang'),
                'kd_unit' => form_error('kd_unit'),
                'kd_sub' => form_error('kd_sub')
            ];
        }
        else
        {
            $errors = [
                'nama_unit' => form_error('nama_unit'),
                'kd_urusan' => 'Kode Urusan Sudah Ada',
                'kd_bidang' => 'Kode Bidang Sudah Ada',
                'kd_unit' => 'Kode Unit Sudah Ada',
                'kd_sub' => 'Kode Sub Sudah Ada'
            ];
        }
        
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function add()
    {
        if ($this->akses['tambah'] == 'Y') {
            $this->_rule_form();
            if ($this->form_validation->run() == false) {
                $this->_send_error(1);
            } else {
                $post = $this->input->post(null, TRUE);
                $kd_urusan=htmlspecialchars($post['kd_urusan']);
                $kd_bidang=htmlspecialchars($post['kd_bidang']);
                $kd_unit=htmlspecialchars($post['kd_unit']);
                $kd_sub=htmlspecialchars($post['kd_sub']);
                $cek_skpd = $this->mquery->count_data('data_skpd', ['kd_urusan' => $kd_urusan, 'kd_bidang' => $kd_bidang, 'kd_unit' => $kd_unit, 'kd_sub' => $kd_sub]);
                
                if($cek_skpd>=1){$this->_send_error(2);}
                else
                {
                    $array =  [
                        'nama_skpd' => htmlspecialchars($post['nama_unit']),
                        'kd_urusan' => $kd_urusan,
                        'kd_bidang' => $kd_bidang,
                        'kd_unit' => $kd_unit,
                        'kd_sub' => $kd_sub,
                        'skpd_parent' => htmlspecialchars($post['id_skpd'])
                    ];
                    $string = ['data_skpd' => $array];
                    $log = simpan_log("insert jenis data_skpd", json_encode($string));
                    $res = $this->mquery->insert_data('data_skpd', $array, $log);
                    $data = ['status' => TRUE, 'notif' => $res];
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
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
                $this->_send_error(1);
            } else {
                $post = $this->input->post(null, TRUE);
                $id = htmlspecialchars($post['id_skpd']);
                $kd_urusan=htmlspecialchars($post['kd_urusan']);
                $kd_bidang=htmlspecialchars($post['kd_bidang']);
                $kd_unit=htmlspecialchars($post['kd_unit']);
                $kd_sub=htmlspecialchars($post['kd_sub']);

                $temp = $this->mquery->select_id('data_skpd', ['id_skpd' => $id]);
                if($temp['kd_urusan']==$kd_urusan AND $temp['kd_bidang']==$kd_bidang AND $temp['kd_unit']==$kd_unit AND $temp['kd_sub']==$kd_sub)
                    { $cek_skpd=0;}
                else
                    {$cek_skpd = $this->mquery->count_data('data_skpd', ['kd_urusan' => $kd_urusan, 'kd_bidang' => $kd_bidang, 'kd_unit' => $kd_unit, 'kd_sub' => $kd_sub]);}

                if($cek_skpd>=1){$this->_send_error(2);}
                else
                {
                    $array =  [
                        'nama_skpd' => htmlspecialchars($post['nama_unit']),
                        'kd_urusan' => $kd_urusan,
                        'kd_bidang' => $kd_bidang,
                        'kd_unit' => $kd_unit,
                        'kd_sub' => $kd_sub
                    ];
                
                    $string = ['data_skpd' => ['old' => $temp, 'new' => $array]];
                    $log = simpan_log("update data_skpd", json_encode($string));
                    $res = $this->mquery->update_data('data_skpd', $array, ['id_skpd' => $id], $log);
                    $data = ['status' => TRUE, 'notif' => $res];
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            } 
        }
        else {
            $data = ['status' => FALSE, 'pesan' => 'blocked'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function delete()
    {
        if ($this->akses['hapus'] == 'Y') {
            $id = htmlspecialchars($this->input->post('id', TRUE));
            $temp = $this->mquery->select_id('data_skpd', ['id_skpd' => $id]);
            $string = ['data_skpd' => $temp];
            $log = simpan_log("delete data_skpd", json_encode($string));
            $res = $this->mquery->delete_data('data_skpd', ['id_skpd' => $id], $log);
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            redirect(site_url('blocked'));
        }
    }

}