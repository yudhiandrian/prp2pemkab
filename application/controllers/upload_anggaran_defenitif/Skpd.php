<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Skpd extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel');
        $this->load->model('M_fungsi', 'fungsi');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function index()
    {
        if ($this->akses['akses'] == 'Y') {
            $data = [
                "menu_active" => "upload_data",
                "submenu_active" => "data-anggaran-defenitif"
            ];
            $this->load->view('upload_anggaran_defenitif/skpd/view', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }


    public function load()
    {
        if ($this->akses['akses'] == 'Y') {
            $tahun = $this->input->post('tahun');
            if ($this->user['is_skpd'] == 'Y') {
                $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
                $result = $this->mquery->select_by("data_skpd", ['id_skpd'=>$user['id_skpd']]);
            } else {
                $result = $this->mquery->select_data("data_skpd", "id_skpd ASC");
            }
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $encrypt_id = encrypt_url($r['id_skpd']);
                $nama_skpd = "<a href=" . base_url("data-anggaran-defenitif/detail/" . $tahun . '/' .$encrypt_id) . "><h2>" . $r['nama_skpd'] . "</h2></a>";
                
                $bulan=0;
                $cek_jml_data2 = $this->mquery->count_data('log_upload_defenitif', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun]);
                if($cek_jml_data2>0)
                {
                    $last = $this->mquery->max_data('log_upload_defenitif', 'bulan', ['tahun' => $tahun, 'id_skpd' => $r['id_skpd']]);
                    $bulan=bulan($last['bulan']);
                    $last_data = $this->mquery->select_id('log_upload_defenitif', ['tahun' => $tahun, 'id_skpd' => $r['id_skpd'], 'bulan'=>$last['bulan']]);
                    $anggaran=format_rupiah($last_data['anggaran']);
                }
                else
                {
                    $bulan='';
                    $anggaran="";
                }

                $no++;
                $row = [
                    'no' => $no,
                    'nama_skpd' => $nama_skpd,
                    'bulan' => $bulan,
                    'anggaran' => $anggaran
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
        $id_skpd = decrypt_url($encrypt_id);
        $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
        $data = [
            "menu_active" => "upload_data",
            "submenu_active" => "data-anggaran-defenitif",
            "skpd" => $skpd,
            "tahun" => $tahun
        ];
        $this->load->view('upload_anggaran_defenitif/skpd/view_detail', $data);
    }


    public function load_apbd()
    {
        //$tahun=2022;
        $tahun = $this->input->post('tahun');
        $id_skpd = $this->input->post('id');
        $result = $this->mquery->select_by('log_upload_defenitif', ['id_skpd' => $id_skpd, 'tahun'=> $tahun], 'bulan ASC');
        $data = [];
        $no = 0;
        foreach ($result as $r) {
            $paramater = $tahun . '/' . $r['bulan'] . '/' . encrypt_url($id_skpd);
            $bulan = "<a href='" . site_url('data-anggaran-defenitif/detail2/' . $paramater) . "'>" . bulan($r['bulan']) . "</a>";
            $no++;
            $row = [
                'no' => $no,
                'tahun' => $r['tahun'],
                'bulan' => $bulan,
                'anggaran' => format_rupiah($r['anggaran']),
                'pegawai' => format_rupiah($r['pegawai']),
                'barang' => format_rupiah($r['barang']),
                'modal' => format_rupiah($r['modal'])
            ];
            $data[] = $row;
        }

        $output['data'] = $data;
        echo json_encode($output);
    }

    public function detail2($tahun, $bulan, $encrypt_id)
    {
        $id_skpd = decrypt_url($encrypt_id);
        $row_log_upload = $this->mquery->select_id('log_upload_defenitif', ['id_skpd' => $id_skpd, 'tahun' => $tahun, 'bulan' => $bulan]);
        $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
        $data = [
            "menu_active" => "upload_data",
            "submenu_active" => "data-anggaran-defenitif",
            "skpd" => $skpd,
            "tahun" => $tahun,
            "bulan" => $bulan,
            "nama_periode" => $this->fungsi->nama_bulan($row_log_upload['tgl_data'])
        ];
        $this->load->view('upload_anggaran_defenitif/skpd/view_detail2', $data);
    }


    public function load_apbd2()
    {
        //$tahun=2022;
        $skpd = $this->input->post('id');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $encrypt_id = encrypt_url($skpd);
        $result = $this->mquery->select_by('data_anggaran_defenitif', ['id_skpd' => $skpd, 'tahun' => $tahun, 'bulan' => $bulan], "id_defenitif ASC");
        $data = [];
        $no = 0;
        $tahun_now=date('Y');
        foreach ($result as $r) {
            $no++;
            // $row_anggaran = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $skpd, 'tahun' => $tahun, 'kode_rekening' => $r['kode_rekening']]);
            $row_uraian = $this->mquery->select_id('data_uraian_defenitif', ['kode_rekening' => $r['kode_rekening'], 'id_skpd' => $skpd, 'tahun' => $tahun]);
            
            // if($row_anggaran['anggaran']==0){$persen=0;}
            // else{$persen=round(($r['realisasi']/$row_anggaran['anggaran']*100),2);}

            if($tahun_now==$tahun)
            {
                if ($this->akses['ubah'] == 'Y') 
                    {$edit = action_edit(encrypt_url($row_uraian['id_defenitif']));}
                else{$edit = "-";}
            }
            else
            {
                if ($this->akses['ubah_1'] == 'Y') 
                    {$edit = action_edit(encrypt_url($row_uraian['id_defenitif']));}
                else{$edit = "-";}
            }

            $row = [
                'no' => $no,
                'kode_rekening' => $r['kode_rekening'],
                'uraian' => $row_uraian['uraian'],
                'anggaran' => format_rupiah($r['anggaran']),
                'pegawai' => format_rupiah($r['pegawai']),
                'barang' => format_rupiah($r['barang']),
                'modal' => format_rupiah($r['modal']),
                'opsi' => $edit
            ];
            
            $data[] = $row;
        }

        $output['data'] = $data;
        echo json_encode($output);
    }

    public function form_upload()
    {
        $id_skpd = htmlspecialchars($this->input->post('skpd', TRUE));
        $data = [
            'id_skpd' => $id_skpd
        ];
        $this->load->view('upload_anggaran_defenitif/skpd/form_upload', $data);
    }

    function upload()
    {
        $post = $this->input->post(null, TRUE);
        $id_skpd = htmlspecialchars($post['id_skpd']);
        $cek_tanggal=$post['tanggal'];
        $temp_bulan=substr($cek_tanggal,5,2);
        $hsl_bulan=intval($temp_bulan);

        $object = new PHPExcel();
        $new_file = "";

        $config['upload_path'] = "./uploads/berkas/excel/";
        $config['allowed_types'] = 'xls';
        $config['file_name'] = $id_skpd . '_defenitif_' . date("Ymd-His");
        $config['max_size'] = 1024;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_upload')) {
            $errors = [
                'file_upload' => $this->upload->display_errors()
            ];
            $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $upload = $this->upload->data();
            $new_file = $upload['file_name'];


            if ($new_file != "" || $new_file != NULL) {
                $excelreader     = new PHPExcel_Reader_Excel5();
                $loadexcel         = $excelreader->load('./uploads/berkas/excel/' . $new_file);
                $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
                
                $numrow = 0;
                $cek_validasi=0;
                foreach ($sheet as $row) {
                    if ($numrow > 1) {
                        $cek_D = strlen($row['A']);
                        if ($cek_D != 0) {
                            if ($row['A']== "KODE"){$cek_validasi=1;}
                        }
                    }
                    $numrow++;
                }

                $cek_validasi=1;

                if($cek_validasi==0)
                {
                    $errors = [
                        'data error' => 'data error'
                    ];
                    $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Format Tidak Sesuai'];
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
                else
                {
                    $this->db->delete('data_anggaran_defenitif', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'bulan' => $hsl_bulan]);
                    $this->db->trans_start();
                    $data = array();
                    $numrow = 0;
                    $kode_rekening='';
                    $level='';
                    $anggaran='';
                    $jenis=1;
                    foreach ($sheet as $row) {
                        if ($numrow > 2) {
                            $cek_D = strlen($row['A']);
                            if ($cek_D != 0) {
                                $kode_rekening=str_replace(' ','',$row['A']);
                                $cek_koma=strpos($row['C'],",");
                                if ($cek_koma !== FALSE)
                                {$anggaran = konversi_angka($row['C']);}
                                else {$anggaran = number_only($row['C']);}

                                $cek_koma=strpos($row['D'],",");
                                if ($cek_koma !== FALSE)
                                {$pegawai = konversi_angka($row['D']);}
                                else {$pegawai = number_only($row['D']);}

                                $cek_koma=strpos($row['E'],",");
                                if ($cek_koma !== FALSE)
                                {$barang = konversi_angka($row['E']);}
                                else {$barang = number_only($row['E']);}

                                $cek_koma=strpos($row['F'],",");
                                if ($cek_koma !== FALSE)
                                {$modal = konversi_angka($row['F']);}
                                else {$modal = number_only($row['F']);}

                                $cek_jml_data2 = $this->mquery->count_data('data_anggaran_defenitif', ['id_skpd' => $id_skpd, 'kode_rekening' => $kode_rekening, 'tahun' => $post['tahun'], 'bulan' => $hsl_bulan]);
                                    if($cek_jml_data2==0)
                                    {$kode_rekening=$kode_rekening;}
                                    else{$kode_rekening=$kode_rekening.".0";}
                                
                                if(strlen($kode_rekening)==33){$kode=1;}else{$kode=0;}
                                
                                $array_alokasi_skpd =  [
                                    'id_skpd' => $id_skpd,
                                    'kode_rekening' => $kode_rekening,
                                    'anggaran' => $anggaran,
                                    'pegawai' => $pegawai,
                                    'barang' => $barang,
                                    'modal' => $modal,
                                    'tahun' => $post['tahun'],
                                    'bulan' => $hsl_bulan,
                                    'kode' => $kode
                                ];

                                $array_uraian_skpd =  [
                                    'id_skpd' => $id_skpd,
                                    'kode_rekening' => $kode_rekening,
                                    'tahun' => $post['tahun']
                                ];

                                if (!preg_match('/[A-Za-z]/', $kode_rekening)){
                                    $this->db->insert('data_anggaran_defenitif', $array_alokasi_skpd);
                                    $cek_jml_data2 = $this->mquery->count_data('data_uraian_defenitif', ['id_skpd' => $id_skpd, 'kode_rekening' => $kode_rekening, 'tahun' => $post['tahun']]);
                                    if($cek_jml_data2==0)
                                    {$this->db->insert('data_uraian_defenitif', $array_uraian_skpd);}
                                }

                                $cek_total=$row['A'];
                                if($cek_total=="Jumlah")
                                {
                                    $anggaran_total = number_only($row['C']);
                                    $pegawai_total = number_only($row['D']);
                                    $barang_total = number_only($row['E']);
                                    $modal_total = number_only($row['F']);
                                }
                            }
                        }
                        $numrow++;
                    }
                    
                    $this->db->trans_complete();
                    $res = $this->db->trans_status();

                    $this->db->trans_start();
                    $this->db->delete('log_upload_defenitif', ['id_skpd' => $id_skpd, 'tahun' => $post['tahun'], 'bulan' => $hsl_bulan]);

                    $array_tanggal =  [
                        'tahun' => $post['tahun'],
                        'bulan' => $hsl_bulan,
                        'id_skpd' => $id_skpd,
                        'tgl_data' => $post['tanggal'],
                        'tanggal_input' => date('Y-m-d H:i:s'),
                        'user_input' => $this->user['user'],
                        'namafile' => $new_file,
                        'anggaran' => $anggaran_total,
                        'pegawai' => $pegawai_total,
                        'barang' => $barang_total,
                        'modal' => $modal_total
                    ];
                    $this->db->insert('log_upload_defenitif', $array_tanggal);

                    $this->db->trans_complete();
                    $res = $this->db->trans_status();

                    $data = ['status' => TRUE, 'notif' => $res];
                    $this->output->set_content_type('application/json')->set_output(json_encode($data));
                }
            }
        }     
    }
    

    function delete()
    {
        $id = htmlspecialchars($this->input->post('id', TRUE));
        $id_log = decrypt_url($id);
        if ($id_log == "error") {
            $data = ['notif' => FALSE, 'pesan' => "blocked"];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $temp = $this->mquery->select_id('log_upload', ['id_log' => $id_log]);
            $string = ['log_upload' => $temp];
            $log = simpan_log("delete log_upload", json_encode($string));
            $res = $this->mquery->delete_data('log_upload', ['id_log' => $id_log], $log);
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function form()
    {
        $opsi = htmlspecialchars($this->input->post('opsi', TRUE));
        $id_skpd = htmlspecialchars($this->input->post('id_skpd', TRUE));
        $tahun = htmlspecialchars($this->input->post('tahun', TRUE));
        $bulan = htmlspecialchars($this->input->post('bulan', TRUE));
        if ($opsi == "add") {
            $data = [
                "skpd" => $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]),
                "uraian" => $this->mquery->select_data('data_kode_rekening'),
                "tahun" => $tahun,
                "bulan" => $bulan
            ];
            $this->load->view('upload_anggaran_defenitif/skpd/form_add', $data);
        } elseif ($opsi == "edit") {
            $encrypt_id = htmlspecialchars($this->input->post('id', TRUE));
            $id_no = decrypt_url($encrypt_id);
            $data_uraian = $this->mquery->select_id('data_uraian_defenitif', ['id_defenitif' => $id_no]);
            $data = [
                "data_uraian" => $data_uraian,
                "skpd" => $this->mquery->select_id('data_skpd', ['id_skpd' => $data_uraian['id_skpd']])
            ];
            $this->load->view('upload_anggaran_defenitif/skpd/form_edit', $data);
        } else {
            $this->load->view('blocked');
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('id_skpd', 'Satuan kerja', 'required|trim');
        $this->form_validation->set_rules('uraian', 'Uraian', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'id_skpd' => form_error('id_skpd'),
            'uraian' => form_error('uraian')
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
            $id_uraian=htmlspecialchars($post['id_uraian']);
            $temp = $this->mquery->select_id('data_kode_rekening', ['id_uraian' => $id_uraian]);
            $realisasi = str_replace(".", "", htmlspecialchars($post['realisasi']));
            $array =  [
                'id_skpd' => htmlspecialchars($post['id_skpd']),
                'kode_rekening' => $temp['kode_rekening'],
                'realisasi' => $realisasi,
                'tahun' => htmlspecialchars($post['tahun']),
                'bulan' => htmlspecialchars($post['bulan'])
            ];

            $string = ['data_realisasi_detail_skpd' => $array];
            $log = simpan_log("insert data_realisasi_detail_skpd", json_encode($string));
            $res = $this->mquery->insert_data('data_realisasi_detail_skpd', $array, $log);
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
            $id_no = htmlspecialchars($post['id_no']);
            $array =  [
                'uraian' => htmlspecialchars($post['uraian'])
            ];
            $temp = $this->mquery->select_id('data_uraian_defenitif', ['id_defenitif' => $id_no]);
            $string = ['data_uraian_defenitif' => ['old' => $temp, 'new' => $array]];
            $log = simpan_log("update data_uraian_defenitif", json_encode($string));
            $res = $this->mquery->update_data('data_uraian_defenitif', $array, ['id_defenitif' => $id_no], $log);
            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function delete_data()
    {
        $encrypt_id = htmlspecialchars($this->input->post('id', TRUE));
        $id_uraian = decrypt_url($encrypt_id);
        $temp = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_realisasi' => $id_uraian]);
        $string = ['data_realisasi_detail_skpd' => $temp];
        $log = simpan_log("delete data_realisasi_detail_skpd", json_encode($string));
        $res = $this->mquery->delete_data('data_realisasi_detail_skpd', ['id_realisasi' => $id_uraian], $log);
        $data = ['status' => TRUE, 'notif' => $res];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


}
