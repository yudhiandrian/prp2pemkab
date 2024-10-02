<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontrak extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kegiatan_model', 'kegiatan');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function load()
    {
        $id_kontrak = $this->input->post('id_kontrak');
        $ta_kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak]);
        //$no_kontrak = $this->input->post('no_kontrak');
        $result = $this->mquery->select_by('data_kontrak_real', ['id_kontrak' => $id_kontrak]);
        $data = [];
        $no = 0;
        $tahun_now=date('Y');
        foreach ($result as $r) {
            $no++;
            if($tahun_now==$ta_kontrak['tahun'])
                {
                    if ($this->akses['ubah'] == 'Y') 
                        {$edit = "<button id='tombol-ubah-kontrak' data-id='" . $r['id_real'] . "' data-toggle='modal' data-target='#modal-form-action' class='btn btn-icon btn-round btn-success btn-sm' title='UBAH'><i class='fa fa-edit'></i> </button>";}
                    else{$edit = "-";}

                    if ($this->akses['hapus'] == 'Y') 
                        {$delete = "<button id='tombol-hapus-kontrak' data-id='" . $r['id_real'] . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";}
                    else{$delete = "-";}
                }
                else
                {
                    if ($this->akses['ubah_1'] == 'Y') 
                        {$edit = "<button id='tombol-ubah-kontrak' data-id='" . $r['id_real'] . "' data-toggle='modal' data-target='#modal-form-action' class='btn btn-icon btn-round btn-success btn-sm' title='UBAH'><i class='fa fa-edit'></i> </button>";}
                    else{$edit = "-";}

                    if ($this->akses['hapus_1'] == 'Y') 
                        {$delete = "<button id='tombol-hapus-kontrak' data-id='" . $r['id_real'] . "' class='btn btn-icon btn-round btn-danger btn-sm' title='HAPUS'><i class='fa fa-trash'></i></button>";}
                    else{$delete = "-";}
                }

                $tgl_realisasi=$r['tgl_realisasi'];
                if($tgl_realisasi==""){$tampilan_tgl="-";}
                else{$tampilan_tgl=format_tanggal($r['tgl_realisasi']);}

            
            $row = [
                'no' => $no,
                'tgl_realisasi' => $tampilan_tgl,
                'nilai' => format_rupiah($r['nilai']),
                'keterangan' => $r['keterangan'],
                'aksi' => $edit . ' ' . $delete
            ];
            $data[] = $row;
        }
        $output['data'] = $data;
        echo json_encode($output);
    }

    public function form()
    {
        $opsi = $this->input->post('opsi', TRUE);
        if ($opsi == "add") {
            $id_kontrak = $this->input->post('id_kontrak', TRUE);
            $data = [
                'kontrak' => $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak])
            ];
            $this->load->view('kegiatan/detail/form_add_kontrak', $data);
        } elseif ($opsi == "edit") {
            $id_real = htmlspecialchars($this->input->post('id', TRUE));
            $data_real = $this->mquery->select_id('data_kontrak_real', ['id_real' => $id_real]);
            $data = [
                'data_real' => $data_real,
                'kontrak' => $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $data_real['id_kontrak']])
            ];
            $this->load->view('kegiatan/detail/form_edit_kontrak', $data);
        } else {
            $this->load->view('blocked');
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('id_kontrak', 'Nomor kontrak', 'required|trim');
        $this->form_validation->set_rules('nilai', 'Realisasi kontrak', 'required|trim');
        $this->form_validation->set_rules('tgl_realisasi', 'Tanggal Realisasi', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'id_kontrak' => form_error('id_kontrak'),
            'tgl_realisasi' => form_error('tgl_realisasi'),
            'nilai' => form_error('nilai')
        ];
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function add()
    {
        $this->_rule_form();
        if ($this->form_validation->run() == false) {
            $this->_send_error();
        } else {
            $post = $this->input->post(null, TRUE);
            $id_kontrak = htmlspecialchars($post['id_kontrak']);
            $kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak]);
            $array =  [
                'tahun' => htmlspecialchars($post['tahun']),
                'id_kontrak' => $id_kontrak,
                'no_kontrak' => htmlspecialchars($post['no_kontrak']),
                'nilai' => input_rupiah($post['nilai']),
                'kd_keg' => $kontrak['kd_keg'],
                'keterangan' => htmlspecialchars($post['keterangan']),
                'tgl_realisasi' => tanggal_database($post['tgl_realisasi'])
            ];

            $string = ['data_kontrak_real' => $array];
            $log = simpan_log("insert real kontrak", json_encode($string));
            $res = $this->mquery->insert_data('data_kontrak_real', $array, $log);

            // $sum_kontrak_real = $this->mquery->sum_data('data_kontrak_real', 'nilai', ['id_kontrak' => $id_kontrak]);
            // $realisasi = $sum_kontrak_real['nilai'];

            // if ($kontrak['nilai'] == 0) {
            //     $persen_real = 0;
            // } else {
            //     if ($kontrak['adendum'] == 0) {$persen_real = hitung_persen($realisasi, $kontrak['nilai'], 2);}
            //     else {$persen_real = hitung_persen($realisasi, $kontrak['adendum'], 2);}
            //     $update_realisasi =  [
            //         'realisasi' => $realisasi,
            //         'persen_realisasi' => $persen_real
            //     ];
            //     $res = $this->db->update('ta_kontrak', $update_realisasi, ['id_kontrak' => $id_kontrak]);
            // }
            $res=1;
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    function edit()
    {
        $this->_rule_form();
        if ($this->form_validation->run() == false) {
            $this->_send_error();
        } else {
            $post = $this->input->post(null, TRUE);
            $id_real = htmlspecialchars($post['id_real']);
            $array =  [
                'no_kontrak' => htmlspecialchars($post['no_kontrak']),
                'nilai' => input_rupiah($post['nilai']),
                'keterangan' => htmlspecialchars($post['keterangan']),
                'tgl_realisasi' => tanggal_database($post['tgl_realisasi'])
            ];
            $temp = $this->mquery->select_id('data_kontrak_real', ['id_real' => $id_real]);
            $string = ['data_kontrak_real' => ['old' => $temp, 'new' => $array]];
            $log = simpan_log("edit real kontrak", json_encode($string));
            $res = $this->mquery->update_data('data_kontrak_real', $array, ['id_real' => $id_real], $log);

            $sum_kontrak_real = $this->mquery->sum_data('data_kontrak_real', 'nilai', ['id_kontrak' => $temp['id_kontrak']]);
            $realisasi = $sum_kontrak_real['nilai'];

            $kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $temp['id_kontrak']]);

            if ($kontrak['nilai'] == 0) {$persen_real = 0;
            } else {
                if ($kontrak['adendum'] == 0) {$persen_real = hitung_persen($realisasi, $kontrak['nilai'], 2);}
                else {$persen_real = hitung_persen($realisasi, $kontrak['adendum'], 2);}
                $update_realisasi =  [
                    'realisasi' => $realisasi,
                    'persen_realisasi' => $persen_real
                ];
                $res = $this->db->update('ta_kontrak', $update_realisasi, ['id_kontrak' => $temp['id_kontrak']]);
            }

            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function delete()
    {
        $id = htmlspecialchars($this->input->post('id', TRUE));
        $temp = $this->mquery->select_id('data_kontrak_real', ['id_real' => $id]);
        $string = ['data_kontrak_real' => $temp];
        $log = simpan_log("delete real kontrak", json_encode($string));
        $res = $this->mquery->delete_data('data_kontrak_real', ['id_real' => $id], $log);
        $data = ['status' => TRUE, 'notif' => $res];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
