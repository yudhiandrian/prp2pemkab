<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan_fisik extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kegiatan_model', 'kegiatan');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
		$this->load->library('PHPExcel');
    }

    public function infrastruktur($tahun, $encrypt_id)
    {
        if ($this->akses['akses'] == 'Y') {
            $id_skpd = decrypt_url($encrypt_id);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "skpd" => $skpd,
                "tahun" => $tahun,
                "encrypt_id" => $encrypt_id
            ];
            $this->load->view('kegiatan/infrastruktur', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }


    public function non_infrastruktur($tahun, $encrypt_id)
    {
        if ($this->akses['akses'] == 'Y') {
            $id_skpd = decrypt_url($encrypt_id);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "skpd" => $skpd,
                "tahun" => $tahun,
                "encrypt_id" => $encrypt_id
            ];
            $this->load->view('kegiatan/non_infrastruktur', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function all($tahun, $encrypt_id)
    {
        if ($this->akses['akses'] == 'Y') {
            $id_skpd = decrypt_url($encrypt_id);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "skpd" => $skpd,
                "tahun" => $tahun,
                "encrypt_id" => $encrypt_id
            ];
            $this->load->view('kegiatan/seluruh_kegiatan', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function fisik_dak($tahun, $encrypt_id)
    {
        if ($this->akses['akses'] == 'Y') {
            $id_skpd = decrypt_url($encrypt_id);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "skpd" => $skpd,
                "tahun" => $tahun,
                "encrypt_id" => $encrypt_id
            ];
            $this->load->view('kegiatan/fisik_dak', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function all_cek($tahun, $encrypt_id)
    {
        if ($this->akses['akses'] == 'Y') {
            $id_skpd = decrypt_url($encrypt_id);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "skpd" => $skpd,
                "tahun" => $tahun,
                "encrypt_id" => $encrypt_id
            ];
            $this->load->view('kegiatan/seluruh_kegiatan_cek', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function input_data($tahun, $encrypt_id)
    {
        if ($this->akses['akses'] == 'Y') {
            $id_skpd = decrypt_url($encrypt_id);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "skpd" => $skpd,
                "tahun" => $tahun,
                "encrypt_id" => $encrypt_id
            ];
            $this->load->view('kegiatan/input_data', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load_infrastruktur($pilihan)
    {
        if ($this->akses['akses'] == 'Y') {
            $sekarang=date('Y-m-d');
            $id_skpd = $this->input->post('skpd');
            $tahun = $this->input->post('tahun');
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            
            $tables = "ta_kontrak";
            $search = array('no_kontrak', 'tgl_kontrak', 'keperluan', 'waktu');
            
            if($pilihan==1){
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_3'=> 'Y');
            }
            elseif($pilihan==2){
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_3'=> 'N');
            }
            elseif($pilihan==3){
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun);
            }
            elseif($pilihan==4){
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'LEFT(tgl_input,10)'=> $sekarang);
            }
            else{
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_2'=> 'Y');
            }

            $isWhere = null;
            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_where($tables,$search,$where,$isWhere);

        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load_infrastruktur_cek($pilihan)
    {
        if ($this->akses['akses'] == 'Y') {
            $sekarang=date('Y-m-d');
            $id_skpd = $this->input->post('skpd');
            $tahun = $this->input->post('tahun');
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            
            $tables = "ta_kontrak";
            $search = array('no_kontrak', 'tgl_kontrak', 'keperluan', 'waktu');
            
            if($pilihan==1){
                $result = $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_3'=> 'Y']);
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_3'=> 'Y');
            }
            elseif($pilihan==2){
                $result = $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_3'=> 'N']);
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_3'=> 'N');
            }
            elseif($pilihan==3){
                $result = $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun]);
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun);
            }
            else{
                $result = $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'LEFT(tgl_input,10)'=> $sekarang]);
                $where  = array('kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'LEFT(tgl_input,10)'=> $sekarang);
            }

            $isWhere = null;
            
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

                $keperluan = "<a href=" . base_url("kegiatan/detail/" . $encrypt_id) . ">" . $r['keperluan'] . "</a>";

                $data_kontrak = "Nomor : " . $r['no_kontrak'] . "<br>Tanggal : " . substr($r['tgl_kontrak'], 0, 10);
                if($r['id_prioritas']!=0){$data_kontrak=$data_kontrak."<br>Prioritas :".$r['id_prioritas'];}
                if($r['id_kegiatan']!=0)
                {
                    $hsl_kegiatan = $this->mquery->select_id('kegiatan_strategis', ['id_kegiatan' => $r['id_kegiatan']]);
                    $data_kontrak=$data_kontrak."<br>Kegiatan :".$hsl_kegiatan['urutan'];
                }

                if($r['nilai']!=0){$nilai_total = $nilai_total + $r['nilai'];}
                else{$nilai_total = $nilai_total + $r['pagu'];}
                $fisik_total = $fisik_total + $realisasi_fisik;

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
                if($pilihan==1)
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
                elseif($pilihan==2)
                {
                    $array_update =  [
                        'nilai1' => $nilai_total,
                        'realisasi1' => $realisasi_total,
                        'persen_realisasi1' => $persen_total,
                        'persen_fisik1' => $persen_fisik,
                        'jml_input1' => $jml_input_data
                    ];
                    $this->db->update('data_skpd_rekap', $array_update, ['id_skpd' => $id_skpd, 'tahun' => $tahun]);
                }
                elseif($pilihan==3)
                {
                    $array_update =  [
                        'nilai2' => $nilai_total,
                        'realisasi2' => $realisasi_total,
                        'persen_realisasi2' => $persen_total,
                        'persen_fisik2' => $persen_fisik,
                        'jml_input2' => $jml_input_data
                    ];
                    $this->db->update('data_skpd_rekap', $array_update, ['id_skpd' => $id_skpd, 'tahun' => $tahun]);
                }
                
            }
            else
            {
                if($pilihan==1)
                {
                    $array_insert =  [
                        'id_skpd' => $id_skpd,
                        'tahun' => $tahun,
                        'nilai' => $nilai_total,
                        'realisasi' => $realisasi_total,
                        'persen_realisasi' => $persen_total,
                        'persen_fisik' => $persen_fisik,
                        'jml_input' => $jml_input_data,
                        'nilai1' => 0,
                        'realisasi1' => 0,
                        'persen_realisasi1' => 0,
                        'persen_fisik1' => 0,
                        'jml_input1' => 0
                    ];
                    $this->db->insert('data_skpd_rekap', $array_insert);
                }
                elseif($pilihan==2)
                {
                    $array_insert =  [
                        'id_skpd' => $id_skpd,
                        'tahun' => $tahun,
                        'nilai' => 0,
                        'realisasi' => 0,
                        'persen_realisasi' => 0,
                        'persen_fisik' => 0,
                        'jml_input' => 0,
                        'nilai1' => $nilai_total,
                        'realisasi1' => $realisasi_total,
                        'persen_realisasi1' => $persen_total,
                        'persen_fisik1' => $persen_fisik,
                        'jml_input1' => $jml_input_data
                    ];
                    $this->db->insert('data_skpd_rekap', $array_insert);
                }
            }
            
            if($pilihan==1){
                $result = $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_3'=> 'Y']);
            }
            elseif($pilihan==2){
                $result = $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'status_3'=> 'N']);
            }
            elseif($pilihan==3){
                $result = $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun]);
            }
            else{
                $result = $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun, 'LEFT(tgl_input,10)'=> $sekarang]);
            }

            $data = [
                "result" => $result,
                "id_skpd" => $id_skpd,
                "tahun" => $tahun,
                "skpd" => $skpd,
                "pilihan" => $pilihan
            ];
            //$this->load->view('kegiatan/load_infrastruktur', $data);
            
            header('Content-Type: application/json');
            echo $this->M_Datatables->get_tables_where($tables,$search,$where,$isWhere);

        } else {
            redirect(site_url('blocked'));
        }
    }

    public function download_excel($tahun, $encrypt_id)
    {
            $id_skpd = decrypt_url($encrypt_id);
            $skpd = $this->mquery->select_id('data_skpd', ['id_skpd' => $id_skpd]);
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "skpd" => $skpd,
                "tahun" => $tahun,
                "encrypt_id" => $encrypt_id,
                "ta_kontrak" => $this->mquery->select_by('ta_kontrak', ['kd_urusan' => $skpd['kd_urusan'], 'kd_bidang' => $skpd['kd_bidang'], 'kd_unit' => $skpd['kd_unit'], 'kd_sub' => $skpd['kd_sub'], 'tahun'=> $tahun])
            ];
            $this->load->view('kegiatan/cetak_excel', $data);
    }

    public function status($nilai, $id, $encrypt_id)
    {
        if($nilai==1){$hasil="Y";}else{$hasil="N";}
        $dataarray =  [
            'status_3' => $hasil
        ];
        $this->db->update('ta_kontrak', $dataarray, ['id_kontrak'=>$id]);
        redirect(site_url('kegiatan/detail/'.$encrypt_id));
    }



}
