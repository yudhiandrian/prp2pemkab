<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_fisik extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kegiatan_model', 'kegiatan');
        $this->akses = is_logged_in();
        $this->user = is_logged_in();
    }

    public function index()
    {
        if ($this->user['is_skpd'] == 'Y') {
            $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $user['id_skpd']]);
            $jml_keg_all = $this->mquery->count_data('ta_kontrak', ['tahun' => date('Y'), 'kd_urusan' => $skpd['kd_urusan'],'kd_bidang' => $skpd['kd_bidang'],'kd_unit' => $skpd['kd_unit'],'kd_sub' => $skpd['kd_sub']]);
        } else {
            $jml_keg_all = $this->mquery->count_data('ta_kontrak', ['tahun' => date('Y')]);
        }
        
        $data = [
            "menu_active" => "laporan",
            "submenu_active" => "laporan-realisasi-fisik",
            "jml_keg_all" => $jml_keg_all
        ];
        $this->load->view('laporan/laporan_fisik/view', $data);
    }

    public function load()
    {
        $tahun = $this->input->post('tahun');
        if ($this->user['is_skpd'] == 'Y') {
            $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
            $result = $this->mquery->select_by("data_skpd_tahun", ['id_skpd'=>$user['id_skpd'], 'tahun'=>$tahun]);
        } else {
            $result = $this->mquery->select_by("data_skpd_tahun", ['tahun'=>$tahun], "id_skpd ASC");
        }
        $data = [];
        $no = 0;
        foreach ($result as $r) {
            $encrypt_id = encrypt_url($r['id_skpd']);
            $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['tahun'=>$tahun, 'kd_urusan' => $r['kd_urusan'],'kd_bidang' => $r['kd_bidang'],'kd_unit' => $r['kd_unit'],'kd_sub' => $r['kd_sub']]);

            $nama_skpd = "<a href=" . base_url("laporan-realisasi-fisik/detail/" .$tahun ."/". $encrypt_id) . "><h2>" . $r['nama_skpd'] . "</h2></a>";
            $no++;
            $row = [
                'no' => $no,
                'nama_skpd' => $nama_skpd,
                'jumlah' => $jumlah_kegiatan
            ];
            $data[] = $row;
        }
        $output['data'] = $data;
        echo json_encode($output);
    }

    public function detail($tahun, $encrypt_id)
    {
        $id_skpd = decrypt_url($encrypt_id);
        $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
        $data = [
            "menu_active" => "laporan",
            "submenu_active" => "laporan-realisasi-fisik",
            "skpd" => $skpd,
            "tahun" => $tahun
        ];
        $this->load->view('laporan/laporan_fisik/view_detail', $data);
    }

    public function load_kegiatan()
    {
        $id_skpd = $this->input->post('skpd');
        $tahun = $this->input->post('tahun');
        $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
        $result = $this->mquery->select_by('ta_kontrak', ['tahun'=>$tahun, 'kd_urusan' => $skpd['kd_urusan'],'kd_bidang' => $skpd['kd_bidang'],'kd_unit' => $skpd['kd_unit'],'kd_sub' => $skpd['kd_sub']]);
        $data = [];
        $no = 0;
        $realisasi_total=0;
        $nilai_total=0;
        $fisik_total=0;
        $jml_input_data=0;
        foreach ($result as $r) {
            $no++;
            $encrypt_id = encrypt_url($r['id_kontrak']);
            $jml_kontrak_pa = $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $r['id_kontrak']]);
            
            if($jml_kontrak_pa==0){$nama_pa="";}
            else{
                $kontrak_pa = $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $r['id_kontrak']]);
                $nama_pa=$kontrak_pa['nama_pa'];
            }


            $no_kontrak=$r['no_kontrak'];
            $hit_kontrak_real = $this->mquery->count_data('data_kontrak_real', ['id_kontrak' => $r['id_kontrak']]);
            if($hit_kontrak_real==0){$realisasi=0;}
            {
                $sum_kontrak_real = $this->mquery->sum_data('data_kontrak_real', 'nilai', ['id_kontrak' => $r['id_kontrak']]);
                $realisasi=$sum_kontrak_real['nilai'];
                $realisasi_total=$realisasi_total+$realisasi;
            }

            if($r['nilai']==0){$persen_real=0;}
            else{
                $persen_real=hitung_persen($realisasi, $r['nilai'], 2);
            }

            $jml_fisik = $this->mquery->count_data('data_kegiatan_detail', ['id_kegiatan' => $r['id_kontrak']]);
            
            if($jml_fisik==0){$realisasi_fisik=0;}
            else{
                $max_realisasi = $this->mquery->max_data_where('data_kegiatan_detail', 'realisasi', ['id_kegiatan' => $r['id_kontrak']]);
                $realisasi_fisik=$max_realisasi['realisasi'];
                $jml_input_data++;
            }

            if($realisasi_fisik>=$persen_real){$tamp_realisasi_fisik="<button class='btn btn-success btn-sm'>".$realisasi_fisik." %</button>";}
            else {$tamp_realisasi_fisik="<button class='btn btn-danger btn-sm'>".$realisasi_fisik." %</button>";}

            if($jml_fisik==0){$tamp_realisasi_fisik="-";}

            if ($this->akses['role'] == "pakar"){$keperluan=$r['keperluan'];}
            else{$keperluan = "<a href=" . base_url("kegiatan/detail/" . $encrypt_id) . ">" . $r['keperluan'] . "</a>";}
            
            $data_kontrak="Nomor : ".$r['no_kontrak']."<br>Tanggal : ".substr($r['tgl_kontrak'],0,10);
            $nilai_total=$nilai_total+$r['nilai'];
            $fisik_total=$fisik_total+$realisasi_fisik;

            if ($this->akses['role'] == "pakar"){$edit="-";}
            else{$edit = action_edit($r['id_kontrak']);}
            
            $row = [
                'no' => $no,
                'no_kontrak' => $data_kontrak,
                'keperluan' => $r['keperluan'],
                'waktu' => $r['waktu'],
                'nilai' => 'Rp '.format_rupiah($r['nilai']),
                'realisasi' => format_rupiah($realisasi)."<br>Persen : ".$persen_real." %",
                'persen' => $tamp_realisasi_fisik,
                'nama_pa' => $nama_pa
            ];
            $data[] = $row;
        }
        $output['data'] = $data;
        echo json_encode($output);
    }

    public function form()
    {
        $opsi = htmlspecialchars($this->input->post('opsi', TRUE));
        if ($opsi == "add") {
            $skpd = htmlspecialchars($this->input->post('skpd', TRUE));
            $pa = $this->mquery->select_id('pengguna_anggaran', ['id_skpd' => $skpd, 'is_active' => 'Y']);
            $data = [
                'skpd' => $this->mquery->select_id('data_skpd', ['id_skpd' => $skpd]),
                'pengguna_anggaran' => $pa,
                'jenis_pelaksanaan' => $this->mquery->select_data('jenis_pelaksanaan')
            ];
            $this->load->view('kegiatan/form_add', $data);
        } elseif ($opsi == "edit") {
            $id = htmlspecialchars($this->input->post('id', TRUE));
            $ta_kontrak = $this->mquery->select_id('ta_kontrak', ['id_kontrak' => $id]);
            $jml_kontrak_pa = $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $id]);
            
            if($jml_kontrak_pa==0){$nama_pa=""; $nip_pa="";}
            else{
                $kontrak_pa = $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $id]);
                $nama_pa=$kontrak_pa['nama_pa'];
                $nip_pa=$kontrak_pa['nip_pa'];
            }
            $data = [
                'id_kontrak' => $id,
                'nama_pa' => $nama_pa,
                'nip_pa' => $nip_pa,
            ];
            $this->load->view('kegiatan/form_edit', $data);
        } else {
            $this->load->view('blocked');
        }
    }

    private function _rule_form()
    {
        $this->form_validation->set_rules('nama_pa', 'Nama Pengguna Anggaran', 'required|trim');
        $this->form_validation->set_rules('nip_pa', 'NIP Pengguna Anggaran', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
    }

    private function _send_error()
    {
        $errors = [
            'nama_pa' => form_error('nama_pa'),
            'nip_pa' => form_error('nip_pa')
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
            $array =  [
                'id_skpd' => htmlspecialchars($post['id_skpd']),
                'id_pa' => htmlspecialchars($post['id_pa']),
                'tgl_input' => date('Y-m-d H:i:s')
            ];
            $string = ['data_kegiatan' => $array];
            $log = simpan_log("insert kegiatan", json_encode($string));
            $res = $this->mquery->insert_data('data_kegiatan', $array, $log);
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

            $jml_kontrak_pa = $this->mquery->count_data('ta_kontrak_pa', ['id_kontrak' => $id_kontrak]);
            
            if($jml_kontrak_pa==0)
            {
                $array =  [
                    'id_kontrak' => $id_kontrak,
                    'nama_pa' => htmlspecialchars($post['nama_pa']),
                    'nip_pa' => htmlspecialchars($post['nip_pa'])
                ];
                $string = ['ta_kontrak_pa' => $array];
                $log = simpan_log("insert pa", json_encode($string));
                $res = $this->mquery->insert_data('ta_kontrak_pa', $array, $log);
            }
            else
            {
                $array =  [
                    'nama_pa' => htmlspecialchars($post['nama_pa']),
                    'nip_pa' => htmlspecialchars($post['nip_pa'])
                ];
                $temp = $this->mquery->select_id('ta_kontrak_pa', ['id_kontrak' => $id_kontrak]);
                $string = ['ta_kontrak_pa' => ['old' => $temp, 'new' => $array]];
                $log = simpan_log("update pa", json_encode($string));
                $res = $this->mquery->update_data('ta_kontrak_pa', $array, ['id_kontrak' => $id_kontrak], $log);
            }

            $data = ['status' => TRUE, 'notif' => $res];
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

}
