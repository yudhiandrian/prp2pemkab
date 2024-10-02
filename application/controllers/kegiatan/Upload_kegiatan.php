<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Upload_kegiatan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel');
        $this->user = is_logged_in();
        $this->akses = is_logged_in();
    }

    public function index()
    {
        $data = [
            "menu_active" => "upload_data",
            "submenu_active" => "upload_sirup_skpd"
        ];
        $this->load->view('upload_sirup/skpd/view', $data);
    }

    
    public function load()
    {
        $tahun = 2021;
        if ($this->akses['role'] == "master" or $this->akses['role'] == "admin") {
            $result = $this->mquery->select_data("data_skpd", "id_skpd ASC");
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $encrypt_id = encrypt_url($r['id_skpd']);

                $this->db->select_sum('pagu');
                $this->db->where(['id_skpd' => $r['id_skpd'], 'tahun' => $tahun]);
                $row_anggaran = $this->db->get('data_sirup_skpd')->row_array();

                $nama_skpd = "<a href=" . base_url("upload/sirup-skpd/detail/" . $encrypt_id) . ">" . $r['nama_skpd'] . "</a>";
                $no++;
                $row = [
                    'no' => $no,
                    'nama_skpd' => $nama_skpd,
                    'pagu' => format_rupiah($row_anggaran['pagu'])
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

    public function detail($encrypt_id)
    {
        $id_skpd = decrypt_url($encrypt_id);
        $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
        $data = [
            "menu_active" => "upload_data",
            "submenu_active" => "upload_sirup_skpd",
            "skpd" => $skpd
        ];
        $this->load->view('upload_sirup/skpd/view_detail', $data);
    }

    public function load_data()
    {
        $skpd = $this->input->post('id');
        $result = $this->mquery->select_by('data_sirup_skpd', ['tahun' => 2021, 'id_skpd' => $skpd]);
        $data = [];
        $no = 0;
        foreach ($result as $r) {
            $no++;
            $row = [
                'no' => $no,
                'tahun' => $r['tahun'],
                'bulan' => bulan($r['bulan']),
                'nama_paket' => $r['nama_paket'],
                'pagu' => format_rupiah($r['pagu']),
                'metode_pemilihan' => $r['metode_pemilihan'],
                'sumber_dana' => $r['sumber_dana'],
                'kode_rup' => $r['kode_rup']
            ];
            $data[] = $row;
        }
        $output['data'] = $data;
        echo json_encode($output);
    }

    public function form_upload()
    {
        $id_skpd = htmlspecialchars($this->input->post('skpd', TRUE));
        $tahun = htmlspecialchars($this->input->post('tahun', TRUE));
        $data = [
            'id_skpd' => $id_skpd,
            'tahun' => $tahun
        ];
        $this->load->view('kegiatan/form_upload', $data);
    }

    function upload()
    {
        $post = $this->input->post(null, TRUE);
        $id_skpd = htmlspecialchars($post['id_skpd']);
        $tahun = htmlspecialchars($post['tahun']);
        $result_skpd = $this->mquery->select_id("data_skpd", ['id_skpd'=>$id_skpd]);
        $object = new PHPExcel();
        $new_file = "";
        $config['upload_path'] = "./uploads/berkas/excel/";
        $config['allowed_types'] = 'xlsx';
        $config['file_name'] = $id_skpd . '-kegiatan-' . date("Ymd-His");
        $config['max_size'] = 5012;

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
                // $excelreader     = new PHPExcel_Reader_Excel5();
                // $loadexcel         = $excelreader->load('./uploads/berkas/excel/' . $new_file);
                // $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

                $excelreader     = new PHPExcel_Reader_Excel2007();
                $loadexcel         = $excelreader->load('./uploads/berkas/excel/' . $new_file);
                $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);


                $pagu = '';
                $this->db->trans_start();
                $numrow = 0;
                foreach ($sheet as $row) {
                    if ($numrow > 4) {
                        $tahun = $tahun;
                        $kode = $row['B'];
                        $nama_kegiatan = $row['C'];
                        $pagu = $row['D'];
                        $no_kontrak = $row['F'];
                        $tgl_kontrak = $row['G'];
                        $nilai_kontrak = $row['H'];
                        $waktu = $row['I'];
                        $nm_perusahaan = $row['J'];
                        $status_2 = $row['K'];
                        $koordinat = $row['L'];
                        $lokasi_pekerjaan = $row['M'];

                        $array_alokasi =  [
                            'tahun' => $tahun,
                            'no_kontrak' => $no_kontrak,
                            'pagu' => $pagu,
                            'kd_urusan' => $result_skpd['kd_urusan'],
                            'kd_bidang' => $result_skpd['kd_bidang'],
                            'kd_unit' => $result_skpd['kd_unit'],
                            'kd_sub' => $result_skpd['kd_sub'],
                            'kd_keg' => $result_skpd['id_skpd'],
                            'tgl_kontrak' => $tgl_kontrak,
                            'keperluan' => $nama_kegiatan,
                            'waktu' => $waktu,
                            'nilai' => $nilai_kontrak,
                            'nm_perusahaan' => $nm_perusahaan,
                            'status_2' => $status_2,
                            'koordinat' => $koordinat,
                            'lokasi_pekerjaan' => $lokasi_pekerjaan
                        ];

                        $array_update =  [
                            'no_kontrak' => $no_kontrak,
                            'pagu' => $pagu,
                            'tgl_kontrak' => $tgl_kontrak,
                            'keperluan' => $nama_kegiatan,
                            'waktu' => $waktu,
                            'nilai' => $nilai_kontrak,
                            'nm_perusahaan' => $nm_perusahaan,
                            'status_2' => $status_2,
                            'koordinat' => $koordinat,
                            'lokasi_pekerjaan' => $lokasi_pekerjaan
                        ];

                            if($kode==0){$res0=$this->db->insert('ta_kontrak', $array_alokasi);}
                            else{
                                $res0=$this->db->update('ta_kontrak', $array_update, ['id_kontrak'=>$kode]);
                            }
                    }
                    $numrow++;
                }


                $sheet = $loadexcel->getSheet(1)->toArray(null, true, true, true);
                $numrow = 0;
                foreach ($sheet as $row) {
                    if ($numrow > 4) {
                        $id_kegiatan = $row['B'];
                        $id_kegiatan_detail = $row['D'];
                        $jenis_target = $row['E'];
                        $tahapan_target = $row['F'];
                        $jadwal_target = $row['G'];
                        $target = $row['H'];
                        $realisasi = $row['I'];
                        $keterangan_target = $row['J'];

                        $array_alokasi1 =  [
                            'id_kegiatan' => $id_kegiatan,
                            'jenis_target' => $jenis_target,
                            'tahapan_target' => $tahapan_target,
                            'jadwal_target' => $jadwal_target,
                            'target' => $target,
                            'realisasi' => $realisasi,
                            'keterangan_target' => $keterangan_target,
                            'user_input' => $this->user['user'],
                            'tgl_input' => date('Y-m-d H:i:s'),
                            'target_keuangan' => 0,
                            'realisasi_keuangan' => 0
                        ];

                        $array_update1 =  [
                            'jenis_target' => $jenis_target,
                            'tahapan_target' => $tahapan_target,
                            'jadwal_target' => $jadwal_target,
                            'target' => $target,
                            'realisasi' => $realisasi,
                            'keterangan_target' => $keterangan_target
                        ];

                        if(strlen($jadwal_target)>=8)
                        {
                            if($id_kegiatan_detail==0){$res1=$this->db->insert('data_kegiatan_detail', $array_alokasi1);}
                            else{
                                $res1=$this->db->update('data_kegiatan_detail', $array_update1, ['id_kegiatan_detail'=>$id_kegiatan_detail]);
                            }

                            $max_realisasi = $this->mquery->max_data_where('data_kegiatan_detail', 'realisasi', ['id_kegiatan' => $id_kegiatan]);
                            $realisasi_fisik = round($max_realisasi['realisasi'],2);
                            $update_realisasi_fisik =  [
                                    'realisasi_fisik' => $realisasi_fisik
                                ];
                            $res = $this->db->update('ta_kontrak', $update_realisasi_fisik, ['id_kontrak' => $id_kegiatan]);
                        }
                    }
                    $numrow++;
                }

                $sheet = $loadexcel->getSheet(2)->toArray(null, true, true, true);
                $numrow = 0;
                foreach ($sheet as $row) {
                    if ($numrow > 4) {
                        $id_kontrak = $row['B'];
                        $id_real = $row['D'];
                        $tgl_realisasi = $row['E'];
                        $nilai = $row['F'];
                        $keterangan = $row['G'];

                        $array_alokasi2 =  [
                            'id_kontrak' => $id_kontrak,
                            'tgl_realisasi' => $tgl_realisasi,
                            'nilai' => $nilai,
                            'kd_keg' => $result_skpd['id_skpd'],
                            'keterangan' => $keterangan,
                            'tahun' => date('Y')
                        ];

                        $array_update2 =  [
                            'tgl_realisasi' => $tgl_realisasi,
                            'nilai' => $nilai,
                            'keterangan' => $keterangan
                        ];

                        if(strlen($tgl_realisasi)>=8)
                        {
                            if($id_real==0)
                            {
                                $res1=$this->db->insert('data_kontrak_real', $array_alokasi2);
                            }
                            else{
                                $res1=$this->db->update('data_kontrak_real', $array_update2, ['id_real'=>$id_real]);
                            }

                            $kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak]);
                            $sum_kontrak_real = $this->mquery->sum_data('data_kontrak_real', 'nilai', ['id_kontrak' => $id_kontrak]);
                            $realisasi = $sum_kontrak_real['nilai'];
            
                            if ($kontrak['nilai'] == 0) {
                                $persen_real = 0;
                            } else {
                                if ($kontrak['adendum'] == 0) {$persen_real = hitung_persen($realisasi, $kontrak['nilai'], 2);}
                                else {$persen_real = hitung_persen($realisasi, $kontrak['adendum'], 2);}
                                $update_realisasi =  [
                                    'realisasi' => $realisasi,
                                    'persen_realisasi' => $persen_real
                                ];
                                $res = $this->db->update('ta_kontrak', $update_realisasi, ['id_kontrak' => $id_kontrak]);
                            }
                        }
                    }
                    $numrow++;
                }

                $sheet = $loadexcel->getSheet(3)->toArray(null, true, true, true);
                $numrow = 0;
                foreach ($sheet as $row) {
                    if ($numrow > 4) {
                        $id_kontrak = $row['B'];
                        $nama_pa = $row['D'];
                        $nip_pa = $row['E'];

                        $array_alokasi3 =  [
                            'id_kontrak' => $id_kontrak,
                            'nama_pa' => $nama_pa,
                            'nip_pa' => $nip_pa
                        ];

                        $array_update3 =  [
                            'nama_pa' => $nama_pa,
                            'nip_pa' => $nip_pa
                        ];

                        if(strlen($nama_pa)>=5)
                        {
                            $jml_kpa= $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $id_kontrak]);
                            if($jml_kpa==0){$res1=$this->db->insert('ta_kontrak_pa', $array_alokasi3);}
                            else{
                                $res1=$this->db->update('ta_kontrak_pa', $array_update3, ['id_kontrak'=>$id_kontrak]);
                            }
                        }
                    }
                    $numrow++;
                }
                
                $this->db->trans_complete();
                $res = $this->db->trans_status();
                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }
    
    public function form_upload2007()
    {
        $id_skpd = htmlspecialchars($this->input->post('skpd', TRUE));
        $data = [
            'id_skpd' => $id_skpd
        ];
        $this->load->view('kegiatan/form_upload2007', $data);
    }

    function upload2007()
    {
        $post = $this->input->post(null, TRUE);
        $id_skpd = htmlspecialchars($post['id_skpd']);
        $tahun = htmlspecialchars($post['tahun']);
        $result_skpd = $this->mquery->select_id("data_skpd", ['id_skpd'=>$id_skpd]);
        $object = new PHPExcel();
        $new_file = "";
        $config['upload_path'] = "./uploads/berkas/excel/";
        $config['allowed_types'] = 'xlsx';
        $config['file_name'] = $id_skpd . '-kegiatan-' . date("Ymd-His");
        $config['max_size'] = 5012;

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
                $excelreader     = new PHPExcel_Reader_Excel2007();
                $loadexcel         = $excelreader->load('./uploads/berkas/excel/' . $new_file);
                //$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
                $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);
                $pagu = '';
                $this->db->trans_start();
                $numrow = 0;
                foreach ($sheet as $row) {
                    if ($numrow > 4) {
                        $kode = $row['B'];
                        $nama_kegiatan = $row['C'];
                        $pagu = $row['D'];
                        $no_kontrak = $row['F'];
                        $tgl_kontrak = $row['G'];
                        $nilai_kontrak = $row['H'];
                        $nm_perusahaan = $row['J'];
                        $status_3 = $row['K'];
                        $waktu = $row['I'];
                        $tahun = $tahun;
                        $status_1 = $row['L'];
                        $status_2 = $row['M'];
                        $id_prioritas = $row['N'];
                        $id_kegiatan = $row['O'];
                        $koordinat = $row['P'];
                        $lokasi_pekerjaan = $row['Q'];
                        $id_kabupaten = $row['R'];

                        $array_alokasi =  [
                            'tahun' => $tahun,
                            'id_prioritas' => $id_prioritas,
                            'id_kegiatan' => $id_kegiatan,
                            'no_kontrak' => $no_kontrak,
                            'pagu' => $pagu,
                            'kd_urusan' => $result_skpd['kd_urusan'],
                            'kd_bidang' => $result_skpd['kd_bidang'],
                            'kd_unit' => $result_skpd['kd_unit'],
                            'kd_sub' => $result_skpd['kd_sub'],
                            'kd_keg' => $result_skpd['id_skpd'],
                            'tgl_kontrak' => $tgl_kontrak,
                            'keperluan' => $nama_kegiatan,
                            'waktu' => $waktu,
                            'nilai' => $nilai_kontrak,
                            'nm_perusahaan' => $nm_perusahaan,
                            'status_1' => $status_1,
                            'status_2' => $status_2,
                            'status_3' => $status_3,
                            'koordinat' => $koordinat,
                            'lokasi_pekerjaan' => $lokasi_pekerjaan,
                            'id_kabupaten' => $id_kabupaten
                        ];

                        $array_update =  [
                            'id_prioritas' => $id_prioritas,
                            'id_kegiatan' => $id_kegiatan,
                            'no_kontrak' => $no_kontrak,
                            'pagu' => $pagu,
                            'tgl_kontrak' => $tgl_kontrak,
                            'keperluan' => $nama_kegiatan,
                            'waktu' => $waktu,
                            'nilai' => $nilai_kontrak,
                            'nm_perusahaan' => $nm_perusahaan,
                            'status_1' => $status_1,
                            'status_2' => $status_2,
                            'status_3' => $status_3,
                            'koordinat' => $koordinat,
                            'lokasi_pekerjaan' => $lokasi_pekerjaan,
                            'id_kabupaten' => $id_kabupaten
                        ];

                            if($kode==0){$res0=$this->db->insert('ta_kontrak', $array_alokasi);}
                            else{
                                $res0=$this->db->update('ta_kontrak', $array_update, ['id_kontrak'=>$kode]);
                            }
                    }
                    $numrow++;
                }


                $sheet = $loadexcel->getSheet(1)->toArray(null, true, true, true);
                $numrow = 0;
                foreach ($sheet as $row) {
                    if ($numrow > 4) {
                        $id_kegiatan = $row['B'];
                        $id_kegiatan_detail = $row['D'];
                        $jenis_target = $row['E'];
                        $tahapan_target = $row['F'];
                        $jadwal_target = $row['G'];
                        $target = $row['H'];
                        $realisasi = $row['I'];
                        $keterangan_target = $row['J'];

                        $array_alokasi1 =  [
                            'id_kegiatan_detail' => $id_kegiatan_detail,
                            'id_kegiatan' => $id_kegiatan,
                            'jenis_target' => $jenis_target,
                            'tahapan_target' => $tahapan_target,
                            'jadwal_target' => $jadwal_target,
                            'target' => $target,
                            'realisasi' => $realisasi,
                            'keterangan_target' => $keterangan_target,
                            'user_input' => $this->user['user'],
                            'tgl_input' => date('Y-m-d H:i:s'),
                            'target_keuangan' => 0,
                            'realisasi_keuangan' => 0
                        ];

                        $array_update1 =  [
                            'jenis_target' => $jenis_target,
                            'tahapan_target' => $tahapan_target,
                            'jadwal_target' => $jadwal_target,
                            'target' => $target,
                            'realisasi' => $realisasi,
                            'keterangan_target' => $keterangan_target
                        ];

                        if(strlen($jadwal_target)>=8)
                        {
                            if($id_kegiatan_detail==0){$res1=$this->db->insert('data_kegiatan_detail', $array_alokasi1);}
                            else{
                                $res1=$this->db->update('data_kegiatan_detail', $array_update1, ['id_kegiatan_detail'=>$id_kegiatan_detail]);
                            }

                            $max_realisasi = $this->mquery->max_data_where('data_kegiatan_detail', 'realisasi', ['id_kegiatan' => $id_kegiatan]);
                            $realisasi_fisik = round($max_realisasi['realisasi'],2);
                            $update_realisasi_fisik =  [
                                    'realisasi_fisik' => $realisasi_fisik
                                ];
                            $res = $this->db->update('ta_kontrak', $update_realisasi_fisik, ['id_kontrak' => $id_kegiatan]);
                        }
                    }
                    $numrow++;
                }

                $sheet = $loadexcel->getSheet(2)->toArray(null, true, true, true);
                $numrow = 0;
                foreach ($sheet as $row) {
                    if ($numrow > 4) {
                        $id_kontrak = $row['B'];
                        $id_real = $row['D'];
                        $tgl_realisasi = $row['E'];
                        $nilai = $row['F'];
                        $keterangan = $row['G'];

                        $array_alokasi2 =  [
                            'id_real' => $id_real,
                            'id_kontrak' => $id_kontrak,
                            'tgl_realisasi' => $tgl_realisasi,
                            'nilai' => $nilai,
                            'keterangan' => $keterangan,
                            'tahun' => date('Y'),
                            'no_kontrak' => " ",
                            'kd_urusan' => $result_skpd['kd_urusan'],
                            'kd_bidang' => $result_skpd['kd_bidang'],
                            'kd_unit' => $result_skpd['kd_unit'],
                            'kd_sub' => $result_skpd['kd_sub'],
                            'kd_keg' => $result_skpd['id_skpd']
                        ];

                        $array_update2 =  [
                            'tgl_realisasi' => $tgl_realisasi,
                            'nilai' => $nilai,
                            'keterangan' => $keterangan
                        ];

                        if(strlen($tgl_realisasi)>=8)
                        {
                            if($id_real==0){$res1=$this->db->insert('data_kontrak_real', $array_alokasi2);}
                            else{
                                $res1=$this->db->update('data_kontrak_real', $array_update2, ['id_real'=>$id_real]);
                            }

                            $kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak]);
                            $sum_kontrak_real = $this->mquery->sum_data('data_kontrak_real', 'nilai', ['id_kontrak' => $id_kontrak]);
                            $realisasi = $sum_kontrak_real['nilai'];
            
                            if ($kontrak['nilai'] == 0) {
                                $persen_real = 0;
                            } else {
                                if ($kontrak['adendum'] == 0) {$persen_real = hitung_persen($realisasi, $kontrak['nilai'], 2);}
                                else {$persen_real = hitung_persen($realisasi, $kontrak['adendum'], 2);}
                                $update_realisasi =  [
                                    'realisasi' => $realisasi,
                                    'persen_realisasi' => $persen_real
                                ];
                                $res = $this->db->update('ta_kontrak', $update_realisasi, ['id_kontrak' => $id_kontrak]);
                            }
                        }
                    }
                    $numrow++;
                }

                $sheet = $loadexcel->getSheet(3)->toArray(null, true, true, true);
                $numrow = 0;
                foreach ($sheet as $row) {
                    if ($numrow > 4) {
                        $id_kontrak = $row['B'];
                        $nama_pa = $row['D'];
                        $nip_pa = $row['E'];

                        $array_alokasi3 =  [
                            'id_kontrak' => $id_kontrak,
                            'nama_pa' => $nama_pa,
                            'nip_pa' => $nip_pa
                        ];

                        $array_update3 =  [
                            'nama_pa' => $nama_pa,
                            'nip_pa' => $nip_pa
                        ];

                        if(strlen($nama_pa)>=5)
                        {
                            $jml_kpa= $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $id_kontrak]);
                            if($jml_kpa==0){$res1=$this->db->insert('ta_kontrak_pa', $array_alokasi3);}
                            else{
                                $res1=$this->db->update('ta_kontrak_pa', $array_update3, ['id_kontrak'=>$id_kontrak]);
                            }
                        }
                    }
                    $numrow++;
                }
                
                $this->db->trans_complete();
                $res = $this->db->trans_status();

                $data = ['status' => TRUE, 'notif' => $res];
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }

    function delete()
    {
        $id = htmlspecialchars($this->input->post('id', TRUE));
        $id_realisasi = decrypt_url($id);
        if ($id_realisasi == "error") {
            $data = ['notif' => FALSE, 'pesan' => "blocked"];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $temp = $this->mquery->select_id('data_realisasi', ['id_realisasi' => $id_realisasi]);
            $string = ['realisasi' => $temp];
            $log = simpan_log("delete realisasi", json_encode($string));
            $res = $this->mquery->delete_data('data_realisasi', ['id_realisasi' => $id_realisasi], $log);
            $data = ['notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
}
