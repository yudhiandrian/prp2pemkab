<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kegiatan_model', 'kegiatan');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
    }

    public function index($tahun=NULL)
    {
        if ($this->akses['akses'] == 'Y') {
            if($tahun==NULL){$tahun=date('Y');}
            $jml_keg_all = $this->mquery->count_data('ta_kontrak', ['tahun' => $tahun, 'status_3'=> "Y"]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "jml_keg_all" => $jml_keg_all,
                "tahun" => $tahun
            ];
            $this->load->view('kegiatan/view', $data);
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
                $result = $this->mquery->select_by("data_skpd_tahun", ['tahun' => $tahun, 'id_skpd'=>$user['id_skpd']]);
            } else {
                $result = $this->mquery->select_by("data_skpd_tahun", ['tahun' => $tahun], "id_skpd ASC");
            }
            $data = [];
            $no = 0;
            foreach ($result as $r) {
                $encrypt_id = encrypt_url($r['id_skpd']);
                $skpd = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
                $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun]);
                $berkontrak = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'nilai!='=> 0]);
                $belum_mulai = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'nilai!='=> 0, 'realisasi_fisik'=> 0]);
                $dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'realisasi_fisik!='=> 0]);
                $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'realisasi_fisik'=> 100]);
                $belum_selesai=$dikerjakan-$selesai_dikerjakan;

                $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun]);
                $total_pagu = $row_pagu['pagu'];

                $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>0]);
                $total_nilai_murni = $row_nilai_murni['nilai'];
                $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>1]);
                $total_nilai_adendum = $row_nilai_adendum['nilai'];
                $jml_adendum = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>1]);

                $total_nilai = $total_nilai_murni + $total_nilai_adendum;

                $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun]);
                $total_realisasi = $row_realisasi['realisasi'];

                if($total_nilai==0){$persen_keuangan=0;}
                else{
                    $persen_keuangan = hitung_persen($total_realisasi, $total_nilai, 2);
                }
                
                $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun]);
                $total_fisik = $row_fisik['realisasi_fisik'];

                if($total_fisik==0){$persen_fisik=0;}
                else{
                    $persen_fisik = round(($total_fisik/$jumlah_kegiatan),2);
                }

                $nama_skpd = "<a href=" . base_url("kegiatan/skpd/" . $tahun .'/'. $encrypt_id) . "><h2>" . $r['nama_skpd'] . "</h2></a>";
                $no++;

                if ($jumlah_kegiatan == 0) {
                    $tamp_persen_fisik = "-";
                    $tamp_jml_input = "-";
                } else {
                    if ($persen_keuangan > $persen_fisik) {
                        $tamp_persen_fisik = "<button class='btn btn-danger btn-sm'>" . $persen_fisik . " %</button>";
                    } else {
                        $tamp_persen_fisik = "<button class='btn btn-success btn-sm'>" . $persen_fisik . " %</button>";
                    }
                }
             
                $keterangan = "<table class='table-detail' style='width:100%; text-align:center; font-weight:bold;'>
                    <tr>
                        <td colspan='4' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Total : ".format_angka($jumlah_kegiatan)."</td>
                    </tr>
                    <tr>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ff3e3f, #ffc7c6);'>Belum Berkontrak: ".format_angka($jumlah_kegiatan-$berkontrak)."</td>
                        <td colspan='3' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Sudah Berkontrak: ".format_angka($berkontrak)."</td>
                    </tr>
                    <tr>
                        <td style='background-image: linear-gradient(to bottom, #ffb3b3, #ffe6e6);'>Belum Mulai: ".format_angka($belum_mulai)."</td>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Sedang Proses: ".format_angka($belum_selesai)."</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef);'>Selesai: ".format_angka($selesai_dikerjakan)."</td>
                    </tr>
                </table>";

                if($total_nilai_adendum==0)
                {$tamp_kontrak=format_rupiah($total_pagu).'<br>' .  format_rupiah($total_nilai);}
                else{$tamp_kontrak=format_rupiah($total_pagu).'<br>' .  format_rupiah($total_nilai).'<br>'.$jml_adendum.' Adendum : ' .  format_rupiah($total_nilai_adendum);}
                
                $row = [
                    'no' => $no,
                    'nama_skpd' => $nama_skpd,
                    'jumlah' => $keterangan,
                    'kontrak' => $tamp_kontrak,
                    'realisasi' => format_rupiah($total_realisasi)."<br>Persen : ".$persen_keuangan."%",
                    'persen_fisik' => $tamp_persen_fisik
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

    public function skpd($tahun, $encrypt_id)
    {
        if ($this->akses['akses'] == 'Y') {
            $php_versi = substr(phpversion(),0,1);
            $id_skpd = decrypt_url($encrypt_id);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "skpd" => $skpd,
                "php_versi" => $php_versi,
                "tahun" => $tahun,
                "encrypt_id" => $encrypt_id
            ];
            $this->load->view('kegiatan/view_detail', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load_kegiatan()
    {
        if ($this->akses['akses'] == 'Y') {
            $id_skpd = $this->input->post('skpd');
            $tahun = $this->input->post('tahun');
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $result1 = $this->mquery->select_by('ta_kontrak', ['kd_keg' => 0, 'kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun]);
            foreach ($result1 as $r1) {
                if($r1['kd_keg']==0){
                    $array_update_kd_keg =  [
                        'kd_keg' => $id_skpd
                    ];
                    $this->db->update('ta_kontrak', $array_update_kd_keg, ['id_kontrak' => $r1['id_kontrak']]);
                }
            }

            $result = $this->mquery->select_by('ta_kontrak', ['kd_keg' => $id_skpd, 'tahun'=> $tahun]);
            $data = [];
            $no = 0;
            $realisasi_total = 0;
            $nilai_total = 0;
            $fisik_total = 0;
            $jml_input_data = 0;
            $tahun_now=date('Y');
            foreach ($result as $r) {
                $no++;
                $encrypt_id = encrypt_url($r['id_kontrak']);

                $jml_kontrak_pa = $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $r['id_kontrak']]);

                if ($jml_kontrak_pa == 0) {
                    $nama_pa = "";
                } else {
                    $kontrak_pa = $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $r['id_kontrak']]);
                    $nama_pa = $kontrak_pa['nama_pa'];
                }

                $id_kontrak = $r['id_kontrak'];
                $hit_kontrak_real = $this->mquery->count_data('data_kontrak_real', ['id_kontrak' => $id_kontrak]);
                if ($hit_kontrak_real == 0) {
                    $realisasi = 0;
                } {
                    $sum_kontrak_real = $this->mquery->sum_data('data_kontrak_real', 'nilai', ['id_kontrak' => $id_kontrak]);
                    $realisasi = $sum_kontrak_real['nilai'];
                    $realisasi_total = $realisasi_total + $realisasi;
                }


                if ($r['nilai'] == 0) {
                    $persen_real = 0;
                } else {
                    if ($r['adendum'] == 0) {$persen_real = hitung_persen($realisasi, $r['nilai'], 2);}
                    else {$persen_real = hitung_persen($realisasi, $r['adendum'], 2);}
    
                    $update_realisasi =  [
                        'realisasi' => $realisasi,
                        'persen_realisasi' => $persen_real
                    ];
                    $this->db->update('ta_kontrak', $update_realisasi, ['id_kontrak' => $r['id_kontrak']]);
                }

                $jml_fisik = $this->mquery->count_data('data_kegiatan_detail', ['id_kegiatan' => $r['id_kontrak']]);

                if ($jml_fisik == 0) {
                    $realisasi_fisik = 0;
                } else {
                    $max_realisasi = $this->mquery->max_data_where('data_kegiatan_detail', 'realisasi', ['id_kegiatan' => $r['id_kontrak']]);
                    $realisasi_fisik = round($max_realisasi['realisasi'],2);
                    $update_realisasi_fisik =  [
                        'realisasi_fisik' => $realisasi_fisik
                    ];
                    $this->db->update('ta_kontrak', $update_realisasi_fisik, ['id_kontrak' => $r['id_kontrak']]);
                    $jml_input_data++;
                }

                if ($realisasi_fisik >= $persen_real) {
                    $tamp_realisasi_fisik = "<button class='btn btn-success btn-sm'>" . $realisasi_fisik . " %</button>";
                } else {
                    $tamp_realisasi_fisik = "<button class='btn btn-danger btn-sm'>" . $realisasi_fisik . " %</button>";
                }

                if ($jml_fisik == 0) {
                    $tamp_realisasi_fisik = "-";
                }

                if ($r['status_2'] == 'N') 
                {$keperluan = "<a href=" . base_url("kegiatan/detail/" . $encrypt_id) . ">" . $r['keperluan'] . "</a>";}
                else{
                    $keperluan = "<button class='btn btn-success btn-sm'>DAK Fisik</button> "." <a href=" . base_url("kegiatan/detail/" . $encrypt_id) . ">" . $r['keperluan'] . "</a>";
                }
                
                if($r['tgl_kontrak']<2020){$tamp_tgl="-";}else{$tamp_tgl=substr($r['tgl_kontrak'], 0, 10);}
                $data_kontrak = "Nomor : " . $r['no_kontrak'] . "<br>Tanggal : " . $tamp_tgl;
                if($r['id_prioritas']!=0){$data_kontrak=$data_kontrak."<br>Prioritas :".$r['id_prioritas'];}
                if($r['id_kegiatan']!=0)
                {
                    $hsl_kegiatan = $this->mquery->select_id('kegiatan_strategis', ['id_kegiatan' => $r['id_kegiatan']]);
                    $data_kontrak=$data_kontrak."<br>Kegiatan :".$hsl_kegiatan['urutan'];
                }

                if($r['nilai']!=0){$nilai_total = $nilai_total + $r['nilai'];}
                else{$nilai_total = $nilai_total + $r['pagu'];}
                $fisik_total = $fisik_total + $realisasi_fisik;

                if($tahun_now==$tahun)
                {
                    if ($this->akses['ubah'] == 'Y') 
                        {$edit = action_edit($r['id_kontrak']);}
                    else{$edit = "-";}

                    if ($this->akses['hapus'] == 'Y') 
                        {$delete = action_delete($r['id_kontrak']);}
                    else{$delete = "-";}
                }
                else
                {
                    if ($this->akses['ubah_1'] == 'Y') 
                        {$edit = action_edit($r['id_kontrak']);}
                    else{$edit = "-";}

                    if ($this->akses['hapus_1'] == 'Y') 
                        {$delete = action_delete($r['id_kontrak']);}
                    else{$delete = "-";}
                }

                $row = [
                    'no' => $no,
                    'no_kontrak' => $data_kontrak,
                    'keperluan' => $keperluan,
                    'waktu' => $r['waktu'],
                    'nilai' => 'Rp ' . format_rupiah($r['pagu']).'<br>Rp ' . format_rupiah($r['nilai']),
                    'realisasi' => format_rupiah($realisasi) . "<br>Persen : " . $persen_real . " %",
                    'persen' => $tamp_realisasi_fisik,
                    'aksi' => $edit . ' ' . $delete
                ];
                $data[] = $row;
            }

            if ($no == 0) {
                $persen_fisik = 0;
            } else {
                $persen_fisik = round(($fisik_total / $no), 2);
            }
            if ($nilai_total == 0) {
                $persen_total = 0;
            } else {
                $persen_total = round(($realisasi_total / $nilai_total * 100), 2);
            }

            $cek_data = $this->mquery->count_data('data_skpd_rekap', ['id_skpd' => $id_skpd, 'tahun' => $tahun]);

            if($cek_data!=0)
            {
                $array_update =  [
                    'nilai' => $nilai_total,
                    'realisasi' => $realisasi_total,
                    'persen_realisasi' => $persen_total,
                    'persen_fisik' => $persen_fisik,
                    'jml_input' => $jml_input_data
                ];
                $this->db->update('data_skpd_rekap', $array_update, ['id_skpd' => $id_skpd, 'tahun' => $tahun]);
            }
            else
            {
                $array_insert =  [
                    'id_skpd' => $id_skpd,
                    'tahun' => $tahun,
                    'nilai' => $nilai_total,
                    'realisasi' => $realisasi_total,
                    'persen_realisasi' => $persen_total,
                    'persen_fisik' => $persen_fisik,
                    'jml_input' => $jml_input_data
                ];
                $this->db->insert('data_skpd_rekap', $array_insert);
            }

            $output['data'] = $data;
            echo json_encode($output);
        } else {
            $data = ['status' => FALSE, 'pesan' => 'blocked'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function form()
    {
        $opsi = htmlspecialchars($this->input->post('opsi', TRUE));
        if ($opsi == "add") {
            $id_skpd = htmlspecialchars($this->input->post('skpd', TRUE));
            $data = [
                'skpd' => $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd])
            ];
            $this->load->view('kegiatan/form_add', $data);
        } elseif ($opsi == "edit") {
            $id = htmlspecialchars($this->input->post('id', TRUE));
            $ta_kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id]);
            $jml_kontrak_pa = $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $id]);

            if ($jml_kontrak_pa == 0) {
                $nama_pa = "";
                $nip_pa = "";
            } else {
                $kontrak_pa = $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $id]);
                $nama_pa = $kontrak_pa['nama_pa'];
                $nip_pa = $kontrak_pa['nip_pa'];
            }
            $data = [
                'id_kontrak' => $id,
                'nama_pa' => $nama_pa,
                'nip_pa' => $nip_pa,
                'ta_kontrak' => $ta_kontrak
            ];
            $this->load->view('kegiatan/form_edit', $data);
        } else {
            $this->load->view('blocked');
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('tahun', 'Tahun kontrak', 'required|trim');
        $this->form_validation->set_rules('nama_kegiatan', 'Nama kegiatan', 'required|trim');
        $this->form_validation->set_rules('pagu', 'Pagu', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'tahun' => form_error('tahun'),
            'nama_kegiatan' => form_error('nama_kegiatan'),
            'pagu' => form_error('pagu')
        ];
        $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function add()
    {
        $this->form_validation->set_rules('nama_skpd', 'Nama OPD', 'required|trim');
        $this->form_validation->set_rules('tahun', 'Tahun kontrak', 'required|trim');
        $this->form_validation->set_rules('nama_kegiatan', 'Nama kegiatan', 'required|trim');
        $this->form_validation->set_rules('pagu', 'Pagu', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        if ($this->form_validation->run() == false) {
            $errors = [
                'nama_skpd' => form_error('nama_skpd'),
                'tahun' => form_error('tahun'),
                'nama_kegiatan' => form_error('nama_kegiatan'),
                'pagu' => form_error('pagu')
            ];
            $data = ['status' => FALSE, 'errors' => $errors, 'pesan' => 'Data Gagal Disimpan'];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $post = $this->input->post(null, TRUE);
            $id_skpd = htmlspecialchars($this->input->post('id_skpd'));
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $array =  [
                'tahun' => htmlspecialchars($post['tahun']),
                'id_prioritas' => 0,
                'id_kegiatan' => 0,
                'no_kontrak' => htmlspecialchars($post['no_kontrak']),
                'pagu' => htmlspecialchars($post['pagu']),
                'kd_urusan' => $skpd['kd_urusan'],
                'kd_bidang' => $skpd['kd_bidang'],
                'kd_unit' => $skpd['kd_unit'],
                'kd_sub' => $skpd['kd_sub'],
                'kd_keg' => $skpd['id_skpd'],
                'tgl_kontrak' => tanggal_database($post['tgl_kontrak']),
                'keperluan' => htmlspecialchars($post['nama_kegiatan']),
                'waktu' => htmlspecialchars($post['waktu']),
                'nilai' => htmlspecialchars($post['nilai_kontrak']),
                'nm_perusahaan' => htmlspecialchars($post['nama_perusahaan']),
                'status_1' => 'N',
                'status_2' => htmlspecialchars($post['status_2']),
                'koordinat' => htmlspecialchars($post['koordinat']),
                'lokasi_pekerjaan' => htmlspecialchars($post['lokasi_pekerjaan'])
            ];
            $string = ['ta_kontrak' => $array];
            $log = simpan_log("insert kegiatan", json_encode($string));
            $res = $this->mquery->insert_data('ta_kontrak', $array, $log);
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
            $id_kontrak = htmlspecialchars($post['id_kontrak']);
            $temp_kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id_kontrak]);
            $jml_kontrak_pa = $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $id_kontrak]);
           
            if(isset($post['strategis']))
            {
                $array_kontrak =  [
                    'tahun' => htmlspecialchars($post['tahun']),
                    'no_kontrak' => htmlspecialchars($post['no_kontrak']),
                    'pagu' => input_rupiah($post['pagu']),
                    'tgl_kontrak' => tanggal_database($post['tgl_kontrak']),
                    'keperluan' => htmlspecialchars($post['nama_kegiatan']),
                    'waktu' => htmlspecialchars($post['waktu']),
                    'nilai' => input_rupiah($post['nilai_kontrak']),
                    'nm_perusahaan' => htmlspecialchars($post['nama_perusahaan']),
                    'status_2' => htmlspecialchars($post['status_2']),
                    'koordinat' => htmlspecialchars($post['koordinat']),
                    'lokasi_pekerjaan' => htmlspecialchars($post['lokasi_pekerjaan'])
                ];
            }
            else
            {
                $array_kontrak =  [
                    'tahun' => htmlspecialchars($post['tahun']),
                    'no_kontrak' => htmlspecialchars($post['no_kontrak']),
                    'pagu' => input_rupiah($post['pagu']),
                    'tgl_kontrak' => tanggal_database($post['tgl_kontrak']),
                    'keperluan' => htmlspecialchars($post['nama_kegiatan']),
                    'waktu' => htmlspecialchars($post['waktu']),
                    'nilai' => input_rupiah($post['nilai_kontrak']),
                    'nm_perusahaan' => htmlspecialchars($post['nama_perusahaan']),
                    'koordinat' => htmlspecialchars($post['koordinat']),
                    'status_2' => htmlspecialchars($post['status_2']),
                    'lokasi_pekerjaan' => htmlspecialchars($post['lokasi_pekerjaan'])
                ];
            }
            if ($jml_kontrak_pa == 0) {
                $array_pa =  [
                    'id_kontrak' => $id_kontrak,
                    'nama_pa' => htmlspecialchars($post['nama_pa']),
                    'nip_pa' => htmlspecialchars($post['nip_pa'])
                ];

                $string = [
                    'ta_kontrak_pa' => $array_pa,
                    'ta_kontrak' => ['old' => $temp_kontrak['nilai'], 'new' => $array_kontrak['nilai']]
                ];
                $log = simpan_log("insert pa", json_encode($string));
                $this->db->trans_start();
                $this->db->insert('ta_kontrak_pa', $array_pa);
                $this->db->update('ta_kontrak', $array_kontrak, ['id_kontrak' => $id_kontrak]);
                $this->db->insert('log_user', $log);
                $this->db->trans_complete();
                $res = $this->db->trans_status();
            } else {
                $array_pa =  [
                    'nama_pa' => htmlspecialchars($post['nama_pa']),
                    'nip_pa' => htmlspecialchars($post['nip_pa'])
                ];
                $temp = $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $id_kontrak]);
                $string = [
                    'ta_kontrak_pa' => ['old' => $temp, 'new' => $array_kontrak],
                    'ta_kontrak' => ['old' => $temp_kontrak['nilai'], 'new' => $array_kontrak['nilai']]
                ];
                $log = simpan_log("update pa", json_encode($string));
                $this->db->trans_start();
                $this->db->update('ta_kontrak_pa', $array_pa, ['id_kontrak' => $id_kontrak]);
                $this->db->update('ta_kontrak', $array_kontrak, ['id_kontrak' => $id_kontrak]);
                $this->db->insert('log_user', $log);
                $this->db->trans_complete();
                $res = $this->db->trans_status();
            }

            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function delete()
    {
        $id = htmlspecialchars($this->input->post('id', TRUE));
        $string = [
            'ta_kontrak' => $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id]),
            'ta_kontrak_pa' => $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $id]),
            'data_kegiatan_detail' => $this->mquery->select_by('data_kegiatan_detail', ['id_kegiatan' => $id]),
        ];
        $log = simpan_log("delete kegiatan", json_encode($string));

        $this->db->trans_start();
        $this->db->delete('ta_kontrak_pa', ['id_kontrak' => $id]);
        $this->db->delete('data_kegiatan_detail', ['id_kegiatan' => $id]);
        $this->db->trans_complete();
        $res = $this->db->trans_status();

        $res = $this->mquery->delete_data('ta_kontrak', ['id_kontrak' => $id], $log);
        $data = ['status' => TRUE, 'notif' => $res];
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function injek_data()
    {
        $jml_keg_all = $this->mquery->count_data('ta_kontrak', ['tahun' => 2022]);
        $data = [
            "menu_active" => "kegiatan_skpd",
            "submenu_active" => null,
            "jml_keg_all" => $jml_keg_all
        ];
        $this->load->view('kegiatan/injek_data', $data);
    }

    function cek_kegiatan()
    {
        $id_kegiatan=0;
        $id_prioritas = $this->input->post('id_prioritas', TRUE);
        $result = $this->mquery->select_by('kegiatan_strategis', ['id_prioritas' => $id_prioritas], 'id_kegiatan ASC');
        echo "<option value=''>Pilih Kegiatan Strategis</option>";
        foreach ($result as $r) {
            if ($r['id_kegiatan'] == $id_kegiatan) {
                echo "<option value='" . $r['id_kegiatan'] . "' selected>" . $r['nama_kegiatan'] . "</option>";
            } else {
                echo "<option value='" . $r['id_kegiatan'] . "'>" . $r['nama_kegiatan'] . "</option>";
            }
        }
    }

}
