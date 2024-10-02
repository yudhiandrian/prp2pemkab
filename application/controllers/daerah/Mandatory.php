<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mandatory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_fungsi', 'fungsi');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function index()
    {
        if ($this->akses['akses'] == 'Y') {
            $data = [
                "menu_active" => "postur_apbd",
                "submenu_active" => "mandatory"
            ];
            $this->load->view('daerah/mandatory/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load()
    {
        if ($this->akses['akses'] == 'Y') {
            $tahun = $this->input->post('tahun');
            $result = $this->mquery->select_by("ta_kabupaten", ['id_kabupaten' => 34]);
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $encrypt_id = encrypt_url($r['id_kabupaten']);
                $cek_data = $this->mquery->count_data('tbl_mandatory', ['id_kabupaten' => $r['id_kabupaten'], 'tahun' => $tahun]);
                
                if($cek_data!=0)
                {
                    $status_apbd = $this->mquery->max_data_where('tbl_mandatory', 'st_apbd', ['id_kabupaten' => $r['id_kabupaten'], 'tahun' => $tahun]);
                    $st_apbd = $status_apbd['st_apbd'];
                    $row_mandatory = $this->mquery->select_id('tbl_mandatory', ['id_kabupaten' => $r['id_kabupaten'], 'tahun' => $tahun, 'st_apbd' => $st_apbd]);
                    $pendidikan=$row_mandatory['pendidikan'];
                    $persen_pendidikan=$row_mandatory['persen_pendidikan'];
                    $kesehatan=$row_mandatory['kesehatan'];
                    $persen_kesehatan=$row_mandatory['persen_kesehatan'];
                    $infrastruktur=$row_mandatory['infrastruktur'];
                    $persen_infrestruktur=$row_mandatory['persen_infrestruktur'];
                    $pengawasan=$row_mandatory['pengawasan'];
                    $persen_pengawasan=$row_mandatory['persen_pengawasan'];
                }
                else
                {
                    $pendidikan=0;
                    $persen_pendidikan=0;
                    $kesehatan=0;
                    $persen_kesehatan=0;
                    $infrastruktur=0;
                    $persen_infrestruktur=0;
                    $pengawasan=0;
                    $persen_pengawasan=0;
                }
                
                $nama_kabupaten = "<a href=" . base_url("mandatory/detail/" . $tahun . '/' . $encrypt_id) . ">LIHAT DETAIL</a>";
                $no++;
                $row = [
                    'no' => $no,
                    'nama_kabupaten' => $nama_kabupaten,
                    'pendidikan' => "Rp. ".format_rupiah($pendidikan)."<br>".$persen_pendidikan." %",
                    'kesehatan' => "Rp. ".format_rupiah($kesehatan)."<br>".$persen_kesehatan." %",
                    'infrastruktur' => "Rp. ".format_rupiah($infrastruktur)."<br>".$persen_infrestruktur." %",
                    'pengawasan' => "Rp. ".format_rupiah($pengawasan)."<br>".$persen_pengawasan." %"
                ];
                $data[] = $row;
            }
            $output['data'] = $data;
            echo json_encode($output);
        } else {
            $data = ['status' => FALSE, 'pesan' => 'blocked'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }


    public function detail($tahun, $encrypt_id)
    {
        $id_kabupaten = decrypt_url($encrypt_id);
        $row_kabupaten = $this->mquery->select_id('ta_kabupaten', ['id_kabupaten' => $id_kabupaten]);
        $data = [
            "menu_active" => "postur_apbd",
            "submenu_active" => "mandatory",
            "row_kabupaten" => $row_kabupaten,
            "tahun" => $tahun
        ];
        $this->load->view('daerah/mandatory/view_detail', $data);
    }

    public function load_anggaran()
    {
        $id_kabupaten = $this->input->post('id');
        $tahun = $this->input->post('tahun');
        //$id_kabupaten = 1;
        $result = $this->mquery->select_by('tbl_mandatory', ['id_kabupaten' => $id_kabupaten, 'tahun' => $tahun], "tahun ASC");
        $data = [];
        $no = 0;
        $total = 0;
        foreach ($result as $r) {
            $no++;
            if ($r['st_apbd'] == 1) {
                $keterangan = "APBD";
            } else {
                $keterangan = "P APBD";
            }
            
            if ($this->akses['ubah'] == 'Y') 
                {$edit = action_edit(encrypt_url($r['id_mandatory']));}
            else{$edit = "-";}

            if ($this->akses['hapus'] == 'Y') 
                {$delete = action_delete(encrypt_url($r['id_mandatory']));}
            else{$delete = "-";}
            
            $row = [
                'no' => $no,
                'tahun' => $r['tahun'],
                'pendidikan' => "Rp. ".format_rupiah($r['pendidikan'])."<br>".$r['persen_pendidikan']." %",
                'kesehatan' => "Rp. ".format_rupiah($r['kesehatan'])."<br>".$r['persen_kesehatan']." %",
                'infrastruktur' => "Rp. ".format_rupiah($r['infrastruktur'])."<br>".$r['persen_infrestruktur']." %",
                'pengawasan' => "Rp. ".format_rupiah($r['pengawasan'])."<br>".$r['persen_pengawasan']." %",
                'keterangan' => $keterangan,
                'opsi' => $edit . ' ' . $delete
            ];
            $data[] = $row;
        }
        $output['data'] = $data;
        echo json_encode($output);
    }

    public function form()
    {
        $opsi = htmlspecialchars($this->input->post('opsi', TRUE));
        $id_kabupaten = htmlspecialchars($this->input->post('id_kabupaten', TRUE));
        $tahun = $this->input->post('tahun');
        if ($opsi == "add") {
            $data = [
                "id_kabupaten" => $id_kabupaten,
                "tahun" => $tahun
            ];
            $this->load->view('daerah/mandatory/form_add', $data);
        } elseif ($opsi == "edit") {
            $encrypt_id = htmlspecialchars($this->input->post('id', TRUE));
            $id_no = decrypt_url($encrypt_id);
            $data = [
                "tbl_mandatory" => $this->mquery->select_id('tbl_mandatory', ['id_mandatory' => $id_no])
            ];
            $this->load->view('daerah/mandatory/form_edit', $data);
        } else {
            $this->load->view('blocked');
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim');
        $this->form_validation->set_rules('pendidikan', 'Pendidikan', 'required|trim');
        $this->form_validation->set_rules('kesehatan', 'Kesehatan', 'required|trim');
        $this->form_validation->set_rules('infrastruktur', 'Infrastruktur', 'required|trim');
        $this->form_validation->set_rules('pengawasan', 'Penanganan Covid-19', 'required|trim');
        $this->form_validation->set_rules('persen_pendidikan', 'Persen Pendidikan', 'required|trim');
        $this->form_validation->set_rules('persen_kesehatan', 'Persen Kesehatan', 'required|trim');
        $this->form_validation->set_rules('persen_infrestruktur', 'Persen Infrastruktur', 'required|trim');
        $this->form_validation->set_rules('persen_pengawasan', 'Persen Penanganan Covid-19', 'required|trim');
        $this->form_validation->set_rules('st_apbd', 'Status Anggaran', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'tahun' => form_error('tahun'),
            'pendidikan' => form_error('pendidikan'),
            'kesehatan' => form_error('kesehatan'),
            'infrastruktur' => form_error('infrastruktur'),
            'pengawasan' => form_error('pengawasan'),
            'persen_pendidikan' => form_error('persen_pendidikan'),
            'persen_kesehatan' => form_error('persen_kesehatan'),
            'persen_infrestruktur' => form_error('persen_infrestruktur'),
            'persen_pengawasan' => form_error('persen_pengawasan'),
            'st_apbd' => form_error('st_apbd')
        ];
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function add()
    {
        $this->_rule_form();
        if ($this->form_validation->run() == false) {
            $this->_send_error('default');
        } else {
            $post = $this->input->post(null, TRUE);
            $array =  [
                'id_kabupaten' => htmlspecialchars($post['id_kabupaten']),
                'tahun' => htmlspecialchars($post['tahun']),
                'pendidikan' => htmlspecialchars($post['pendidikan']),
                'kesehatan' => htmlspecialchars($post['kesehatan']),
                'infrastruktur' => htmlspecialchars($post['infrastruktur']),
                'pengawasan' => htmlspecialchars($post['pengawasan']),
                'persen_pendidikan' => htmlspecialchars($post['persen_pendidikan']),
                'persen_kesehatan' => htmlspecialchars($post['persen_kesehatan']),
                'persen_infrestruktur' => htmlspecialchars($post['persen_infrestruktur']),
                'persen_pengawasan' => htmlspecialchars($post['persen_pengawasan']),
                'st_apbd' => htmlspecialchars($post['st_apbd'])
            ];
            $string = ['tbl_mandatory' => $array];
            $log = simpan_log("new tbl_mandatory", json_encode($string));
            $res = $this->mquery->insert_data('tbl_mandatory', $array, $log);
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    function edit()
    {
        $this->_rule_form();
        if ($this->form_validation->run() == false) {
            $this->_send_error('default');
        } else {
            $post = $this->input->post(null, TRUE);
            $id_no = htmlspecialchars($post['id_no']);
            $array =  [
                'tahun' => htmlspecialchars($post['tahun']),
                'pendidikan' => htmlspecialchars($post['pendidikan']),
                'kesehatan' => htmlspecialchars($post['kesehatan']),
                'infrastruktur' => htmlspecialchars($post['infrastruktur']),
                'pengawasan' => htmlspecialchars($post['pengawasan']),
                'persen_pendidikan' => htmlspecialchars($post['persen_pendidikan']),
                'persen_kesehatan' => htmlspecialchars($post['persen_kesehatan']),
                'persen_infrestruktur' => htmlspecialchars($post['persen_infrestruktur']),
                'persen_pengawasan' => htmlspecialchars($post['persen_pengawasan']),
                'st_apbd' => htmlspecialchars($post['st_apbd'])
            ];
            $temp = $this->mquery->select_id('tbl_mandatory', ['id_mandatory' => $id_no]);
            $string = ['tbl_mandatory' => ['old' => $temp, 'new' => $array]];
            $log = simpan_log("update tbl_mandatory", json_encode($string));
            $res = $this->mquery->update_data('tbl_mandatory', $array, ['id_mandatory' => $id_no], $log);
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function delete()
    {
        $encrypt_id = htmlspecialchars($this->input->post('id', TRUE));
        $id_no = decrypt_url($encrypt_id);
        $temp = $this->mquery->select_id('tbl_mandatory', ['id_mandatory' => $id_no]);
        $string = ['tbl_mandatory' => $temp];
        $log = simpan_log("delete tbl_mandatory", json_encode($string));
        $res = $this->mquery->delete_data('tbl_mandatory', ['id_mandatory' => $id_no], $log);
        $data = ['status' => TRUE, 'notif' => $res];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
