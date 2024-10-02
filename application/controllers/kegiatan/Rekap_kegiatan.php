<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_kegiatan extends CI_Controller
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

    public function load_rekap($parameter)
    {
        if ($this->akses['akses'] == 'Y') {
            $tahun = $this->input->post('tahun');

            if($parameter==1)
            {
                $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun]);
                $berkontrak = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'nilai!='=> 0]);
                $belum_mulai = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'nilai!='=> 0, 'realisasi_fisik'=> 0]);
                $dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'realisasi_fisik!='=> 0]);
                $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'realisasi_fisik'=> 100]);
                $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['tahun'=> $tahun]);
                $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'st_adendum'=>0]);
                $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'st_adendum'=>1]);
                $jml_adendum = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'st_adendum'=>1]);
                $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['tahun'=> $tahun]);
                $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['tahun'=> $tahun]);
            }
            else
            {
                $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y"]);
                $berkontrak = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'nilai!='=> 0]);
                $belum_mulai = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'nilai!='=> 0, 'realisasi_fisik'=> 0]);
                $dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'realisasi_fisik!='=> 0]);
                $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'realisasi_fisik'=> 100]);
                $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['tahun'=> $tahun, 'status_2'=> "Y"]);
                $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>0]);
                $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>1]);
                $jml_adendum = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>1]);
                $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['tahun'=> $tahun, 'status_2'=> "Y"]);
                $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['tahun'=> $tahun, 'status_2'=> "Y"]);
            }

                $belum_selesai=$dikerjakan-$selesai_dikerjakan;
                $total_pagu = $row_pagu['pagu'];
                $total_nilai_murni = $row_nilai_murni['nilai'];
                $total_nilai_adendum = $row_nilai_adendum['nilai'];
                $total_nilai = $total_nilai_murni + $total_nilai_adendum;
                $total_realisasi = $row_realisasi['realisasi'];
                if($total_nilai==0){$persen_keuangan=0;}
                else{
                    $persen_keuangan = hitung_persen($total_realisasi, $total_nilai, 2);
                }
                $total_fisik = $row_fisik['realisasi_fisik'];
                if($total_fisik==0){$persen_fisik=0;}
                else{
                    $persen_fisik = round(($total_fisik/$jumlah_kegiatan),2);
                }

                if ($jumlah_kegiatan == 0) {
                    $tamp_persen_fisik = "-";
                    $tamp_jml_input = "-";
                } else {
                    if ($persen_keuangan > $persen_fisik) {
                        $tamp_persen_fisik = "<button class='btn btn-danger btn-sm'>" . $persen_fisik . "%</button>";
                    } else {
                        $tamp_persen_fisik = "<button class='btn btn-success btn-sm'>" . $persen_fisik . "%</button>";
                    }
                }
                if($total_nilai_adendum==0)
                {$tamp_kontrak=format_angka($total_pagu).'<br>' .  format_angka($total_nilai);}
                else{$tamp_kontrak=format_angka($total_pagu).'<br>' .  format_angka($total_nilai).'<br>'.$jml_adendum.' Adendum : ' .  format_angka($total_nilai_adendum);}
                
                $keterangan = "<table class='table-detail' style='width:100%; text-align:center; font-weight:bold;'>
                    <tr>
                        <td colspan='4' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Total Kegiatan : ".format_angka($jumlah_kegiatan)."</td>
                        <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Pagu / Nilai Kontrak</td>
                        <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Realisasi Keuangan</td>
                        <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Realisasi Fisik</td>
                    </tr>
                    <tr>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ff3e3f, #ffc7c6);'>Belum Berkontrak: ".format_angka($jumlah_kegiatan-$berkontrak)."</td>
                        <td colspan='3' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Sudah Berkontrak: ".format_angka($berkontrak)."</td>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>".$tamp_kontrak."</td>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>".format_angka($total_realisasi)."<br>Persen : ".$persen_keuangan."%</td>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>".$tamp_persen_fisik."</td>
                    </tr>
                    <tr>
                        <td style='background-image: linear-gradient(to bottom, #ffb3b3, #ffe6e6);'>Belum Mulai: ".format_angka($belum_mulai)."</td>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Sedang Proses: ".format_angka($belum_selesai)."</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef);'>Selesai: ".format_angka($selesai_dikerjakan)."</td>
                    </tr>
                </table>";

            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "keterangan" => $keterangan
            ];
            $this->load->view('kegiatan/view_rekap', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load_rekap1($parameter)
    {
        if ($this->akses['akses'] == 'Y') {
            $tahun = $this->input->post('tahun');
            $result = $this->mquery->select_data("data_skpd", "id_skpd ASC");
            $no = 0;
            $list_data = "<table class='table-detail' style='width:100%; font-weight:bold;'>
                            <tr>
                            <td rowspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                No
                            </td>
                            <td rowspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Nama OPD
                            </td>
                            <td colspan='6' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Kegiatan
                            </td>
                            <td rowspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Nilai Kontrak<br>Rp ..(1000)
                            </td>
                            <td colspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Realisasi Keuangan
                            </td>
                            <td rowspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Realisasi Fisik <br> %
                            </td>
                        </tr>
                        <tr>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Total
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Belum Berkontrak
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Sudah Berkontrak
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Belum Mulai
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Dalam Proses
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Selesai
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Rp ..(1000)
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                %
                            </td>
                        </tr>";
            foreach ($result as $r) {
                if($parameter==1)
                {
                    $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun]);
                    $berkontrak = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'nilai!='=> 0]);
                    $belum_mulai = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'nilai!='=> 0, 'realisasi_fisik'=> 0]);
                    $dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'realisasi_fisik!='=> 0]);
                    $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'realisasi_fisik'=> 100]);
                    $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun]);
                    $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>0]);
                    $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>1]);
                    $jml_adendum = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>1]);
                    $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun]);
                    $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun]);
                }
                else
                {
                    $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y"]);
                    $berkontrak = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'nilai!='=> 0]);
                    $belum_mulai = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'nilai!='=> 0, 'realisasi_fisik'=> 0]);
                    $dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'realisasi_fisik!='=> 0]);
                    $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'realisasi_fisik'=> 100]);
                    $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y"]);
                    $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>0]);
                    $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>1]);
                    $jml_adendum = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>1]);
                    $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y"]);
                    $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y"]);
                }

                $belum_selesai=$dikerjakan-$selesai_dikerjakan;
                $total_pagu = $row_pagu['pagu'];
                $total_nilai_murni = $row_nilai_murni['nilai'];
                $total_nilai_adendum = $row_nilai_adendum['nilai'];
                $total_nilai = $total_nilai_murni + $total_nilai_adendum;
                $total_realisasi = $row_realisasi['realisasi'];
                if($total_nilai==0){$persen_keuangan=0;}
                else{
                    $persen_keuangan = hitung_persen($total_realisasi, $total_nilai, 2);
                }
                $total_fisik = $row_fisik['realisasi_fisik'];
                if($total_fisik==0){$persen_fisik=0;}
                else{
                    $persen_fisik = round(($total_fisik/$jumlah_kegiatan),2);
                }
                $nama_skpd = $r['nama_skpd'];
                

                if ($jumlah_kegiatan == 0) {
                    $tamp_persen_fisik = "-";
                    $tamp_jml_input = "-";
                } else {
                    if ($persen_keuangan > $persen_fisik) {
                        $tamp_persen_fisik = "<button class='btn btn-danger btn-sm'>" . $persen_fisik . "</button>";
                    } else {
                        $tamp_persen_fisik = "<button class='btn btn-success btn-sm'>" . $persen_fisik . "</button>";
                    }
                }
                if($total_nilai_adendum==0)
                {$tamp_kontrak=format_angka($total_nilai/1000);}
                else{$tamp_kontrak=format_angka($total_nilai/1000).'<br>'.$jml_adendum.' Adendum : ' .  format_angka($total_nilai_adendum/1000);}
                
                if($jumlah_kegiatan!=0)
                {
                    $no++;
                    $list_data = $list_data."
                    <tr>
                        <td style='text-align:center;'>".$no."</td>
                        <td>".$nama_skpd."</td>
                        <td style='text-align:center;'>".format_angka($jumlah_kegiatan)."</td>
                        <td style='text-align:center;'>".format_angka($jumlah_kegiatan-$berkontrak)."</td>
                        <td style='text-align:center;'>".format_angka($berkontrak)."</td>
                        <td style='text-align:center;'>".format_angka($belum_mulai)."</td>
                        <td style='text-align:center;'>".format_angka($belum_selesai)."</td>
                        <td style='text-align:center;'>".format_angka($selesai_dikerjakan)."</td>
                        <td style='text-align:right;'>".$tamp_kontrak."</td>
                        <td style='text-align:right;'>".format_angka($total_realisasi/1000)."</td>
                        <td style='text-align:center;'>".$persen_keuangan."</td>
                        <td style='text-align:center;'>".$tamp_persen_fisik."</td>
                    </tr>";
                }
            }

            $list_data = $list_data."</table>";
                
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "list_data" => $list_data
            ];
            $this->load->view('kegiatan/view_rekap1', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load_rekaps($parameter)
    {
        if ($this->akses['akses'] == 'Y') {
            $tahun = $this->input->post('tahun');
            if($parameter==1)
            {
                $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'id_prioritas!='=>0]);
                $berkontrak = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'nilai!='=> 0, 'id_prioritas!='=>0]);
                $belum_mulai = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'nilai!='=> 0, 'realisasi_fisik'=> 0, 'id_prioritas!='=>0]);
                $dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'realisasi_fisik!='=> 0, 'id_prioritas!='=>0]);
                $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'realisasi_fisik'=> 100, 'id_prioritas!='=>0]);
                $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['tahun'=> $tahun, 'status_2'=> "Y", 'id_prioritas!='=>0]);
                $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>0, 'id_prioritas!='=>0]);
                $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                $jml_adendum = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['tahun'=> $tahun, 'status_2'=> "Y", 'id_prioritas!='=>0]);
                $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['tahun'=> $tahun, 'status_2'=> "Y", 'id_prioritas!='=>0]);
            }
            elseif($parameter==2)
            {
                $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "Y", 'id_prioritas!='=>0]);
                $berkontrak = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "Y", 'nilai!='=> 0, 'id_prioritas!='=>0]);
                $belum_mulai = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "Y", 'nilai!='=> 0, 'realisasi_fisik'=> 0, 'id_prioritas!='=>0]);
                $dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "Y", 'realisasi_fisik!='=> 0, 'id_prioritas!='=>0]);
                $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "Y", 'realisasi_fisik'=> 100, 'id_prioritas!='=>0]);
                $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['tahun'=> $tahun, 'status_3'=> "Y", 'id_prioritas!='=>0]);
                $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'status_3'=> "Y", 'st_adendum'=>0, 'id_prioritas!='=>0]);
                $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'status_3'=> "Y", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                $jml_adendum = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "Y", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['tahun'=> $tahun, 'status_3'=> "Y", 'id_prioritas!='=>0]);
                $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['tahun'=> $tahun, 'status_3'=> "Y", 'id_prioritas!='=>0]);
            }
            elseif($parameter==3)
            {
                $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "N", 'id_prioritas!='=>0]);
                $berkontrak = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "N", 'nilai!='=> 0, 'id_prioritas!='=>0]);
                $belum_mulai = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "N", 'nilai!='=> 0, 'realisasi_fisik'=> 0, 'id_prioritas!='=>0]);
                $dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "N", 'realisasi_fisik!='=> 0, 'id_prioritas!='=>0]);
                $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "N", 'realisasi_fisik'=> 100, 'id_prioritas!='=>0]);
                $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['tahun'=> $tahun, 'status_3'=> "N", 'id_prioritas!='=>0]);
                $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'status_3'=> "N", 'st_adendum'=>0, 'id_prioritas!='=>0]);
                $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'status_3'=> "N", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                $jml_adendum = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'status_3'=> "N", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['tahun'=> $tahun, 'status_3'=> "N", 'id_prioritas!='=>0]);
                $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['tahun'=> $tahun, 'status_3'=> "N", 'id_prioritas!='=>0]);
            }
            else
            {
                $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'id_prioritas!='=>0]);
                $berkontrak = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'nilai!='=> 0, 'id_prioritas!='=>0]);
                $belum_mulai = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'nilai!='=> 0, 'realisasi_fisik'=> 0, 'id_prioritas!='=>0]);
                $dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'realisasi_fisik!='=> 0, 'id_prioritas!='=>0]);
                $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'realisasi_fisik'=> 100, 'id_prioritas!='=>0]);
                $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['tahun'=> $tahun, 'id_prioritas!='=>0]);
                $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'st_adendum'=>0, 'id_prioritas!='=>0]);
                $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['tahun'=> $tahun, 'st_adendum'=>1, 'id_prioritas!='=>0]);
                $jml_adendum = $this->mquery->count_data('ta_kontrak', ['tahun'=> $tahun, 'st_adendum'=>1, 'id_prioritas!='=>0]);
                $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['tahun'=> $tahun, 'id_prioritas!='=>0]);
                $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['tahun'=> $tahun, 'id_prioritas!='=>0]);
            }
                
                $belum_selesai=$dikerjakan-$selesai_dikerjakan;
                $total_pagu = $row_pagu['pagu'];
                $total_nilai_murni = $row_nilai_murni['nilai'];
                $total_nilai_adendum = $row_nilai_adendum['nilai'];
                $total_nilai = $total_nilai_murni + $total_nilai_adendum;
                $total_realisasi = $row_realisasi['realisasi'];
                if($total_nilai==0){$persen_keuangan=0;}
                else{
                    $persen_keuangan = hitung_persen($total_realisasi, $total_nilai, 2);
                }
                $total_fisik = $row_fisik['realisasi_fisik'];
                if($total_fisik==0){$persen_fisik=0;}
                else{
                    $persen_fisik = round(($total_fisik/$jumlah_kegiatan),2);
                }

                if ($jumlah_kegiatan == 0) {
                    $tamp_persen_fisik = "-";
                    $tamp_jml_input = "-";
                } else {
                    if ($persen_keuangan > $persen_fisik) {
                        $tamp_persen_fisik = "<button class='btn btn-danger btn-sm'>" . $persen_fisik . "%</button>";
                    } else {
                        $tamp_persen_fisik = "<button class='btn btn-success btn-sm'>" . $persen_fisik . "%</button>";
                    }
                }
                if($total_nilai_adendum==0)
                {$tamp_kontrak=format_angka($total_pagu).'<br>' .  format_angka($total_nilai);}
                else{$tamp_kontrak=format_angka($total_pagu).'<br>' .  format_angka($total_nilai).'<br>'.$jml_adendum.' Adendum : ' .  format_angka($total_nilai_adendum);}
                
                $keterangan = "<table class='table-detail' style='width:100%; text-align:center; font-weight:bold;'>
                    <tr>
                        <td colspan='4' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Total Kegiatan Strategis: ".format_angka($jumlah_kegiatan)."</td>
                        <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Pagu / Nilai Kontrak</td>
                        <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Realisasi Keuangan</td>
                        <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>Realisasi Fisik</td>
                    </tr>
                    <tr>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ff3e3f, #ffc7c6);'>Belum Berkontrak: ".format_angka($jumlah_kegiatan-$berkontrak)."</td>
                        <td colspan='3' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Sudah Berkontrak: ".format_angka($berkontrak)."</td>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>".$tamp_kontrak."</td>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>".format_angka($total_realisasi)."<br>Persen : ".$persen_keuangan."%</td>
                        <td rowspan='2' style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>".$tamp_persen_fisik."</td>
                    </tr>
                    <tr>
                        <td style='background-image: linear-gradient(to bottom, #ffb3b3, #ffe6e6);'>Belum Mulai: ".format_angka($belum_mulai)."</td>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Sedang Proses: ".format_angka($belum_selesai)."</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef);'>Selesai: ".format_angka($selesai_dikerjakan)."</td>
                    </tr>
                </table>";

            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "keterangan" => $keterangan
            ];
            $this->load->view('kegiatan/view_rekap', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load_rekap1s($parameter)
    {
        if ($this->akses['akses'] == 'Y') {
            $tahun = $this->input->post('tahun');
            $result = $this->mquery->select_data("data_skpd", "id_skpd ASC");
            $no = 0;
            $list_data = "<table class='table-detail' style='width:100%; font-weight:bold;'>
                            <tr>
                            <td rowspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                No
                            </td>
                            <td rowspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Nama OPD
                            </td>
                            <td colspan='6' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                54 Kegiatan Strategis
                            </td>
                            <td rowspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Nilai Kontrak<br>Rp ..(1000)
                            </td>
                            <td colspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Realisasi Keuangan
                            </td>
                            <td rowspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Realisasi Fisik <br> %
                            </td>
                        </tr>
                        <tr>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Total
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Belum Berkontrak
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Sudah Berkontrak
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Belum Mulai
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Dalam Proses
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Selesai
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                Rp ..(1000)
                            </td>
                            <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd); text-align:center;'>
                                %
                            </td>
                        </tr>";
            foreach ($result as $r) {
                if($parameter==1)
                {
                    $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'id_prioritas!='=>0]);
                    $berkontrak = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'nilai!='=> 0, 'id_prioritas!='=>0]);
                    $belum_mulai = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'nilai!='=> 0, 'realisasi_fisik'=> 0, 'id_prioritas!='=>0]);
                    $dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'realisasi_fisik!='=> 0, 'id_prioritas!='=>0]);
                    $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'realisasi_fisik'=> 100, 'id_prioritas!='=>0]);
                    $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'id_prioritas!='=>0]);
                    $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>0, 'id_prioritas!='=>0]);
                    $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                    $jml_adendum = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                    $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'id_prioritas!='=>0]);
                    $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_2'=> "Y", 'id_prioritas!='=>0]);
                }
                elseif($parameter==2)
                {
                    $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'id_prioritas!='=>0]);
                    $berkontrak = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'nilai!='=> 0, 'id_prioritas!='=>0]);
                    $belum_mulai = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'nilai!='=> 0, 'realisasi_fisik'=> 0, 'id_prioritas!='=>0]);
                    $dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'realisasi_fisik!='=> 0, 'id_prioritas!='=>0]);
                    $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'realisasi_fisik'=> 100, 'id_prioritas!='=>0]);
                    $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'id_prioritas!='=>0]);
                    $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'st_adendum'=>0, 'id_prioritas!='=>0]);
                    $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                    $jml_adendum = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                    $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'id_prioritas!='=>0]);
                    $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "Y", 'id_prioritas!='=>0]);
                }
                elseif($parameter==3)
                {
                    $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'id_prioritas!='=>0]);
                    $berkontrak = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'nilai!='=> 0, 'id_prioritas!='=>0]);
                    $belum_mulai = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'nilai!='=> 0, 'realisasi_fisik'=> 0, 'id_prioritas!='=>0]);
                    $dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'realisasi_fisik!='=> 0, 'id_prioritas!='=>0]);
                    $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'realisasi_fisik'=> 100, 'id_prioritas!='=>0]);
                    $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'id_prioritas!='=>0]);
                    $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'st_adendum'=>0, 'id_prioritas!='=>0]);
                    $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                    $jml_adendum = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'st_adendum'=>1, 'id_prioritas!='=>0]);
                    $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'id_prioritas!='=>0]);
                    $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'status_3'=> "N", 'id_prioritas!='=>0]);
                }
                else
                {
                    $jumlah_kegiatan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'id_prioritas!='=>0]);
                    $berkontrak = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'nilai!='=> 0, 'id_prioritas!='=>0]);
                    $belum_mulai = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'nilai!='=> 0, 'realisasi_fisik'=> 0, 'id_prioritas!='=>0]);
                    $dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'realisasi_fisik!='=> 0, 'id_prioritas!='=>0]);
                    $selesai_dikerjakan = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'realisasi_fisik'=> 100, 'id_prioritas!='=>0]);
                    $row_pagu = $this->mquery->sum_data('ta_kontrak', 'pagu', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'id_prioritas!='=>0]);
                    $row_nilai_murni = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>0, 'id_prioritas!='=>0]);
                    $row_nilai_adendum = $this->mquery->sum_data('ta_kontrak', 'nilai', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>1, 'id_prioritas!='=>0]);
                    $jml_adendum = $this->mquery->count_data('ta_kontrak', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'st_adendum'=>1, 'id_prioritas!='=>0]);
                    $row_realisasi = $this->mquery->sum_data('ta_kontrak', 'realisasi', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'id_prioritas!='=>0]);
                    $row_fisik = $this->mquery->sum_data('ta_kontrak', 'realisasi_fisik', ['kd_urusan' => $r['kd_urusan'], 'kd_bidang' => $r['kd_bidang'], 'kd_unit' => $r['kd_unit'], 'kd_sub' => $r['kd_sub'], 'tahun'=> $tahun, 'id_prioritas!='=>0]);
                }
                
                $belum_selesai=$dikerjakan-$selesai_dikerjakan;
                $total_pagu = $row_pagu['pagu'];
                $total_nilai_murni = $row_nilai_murni['nilai'];
                $total_nilai_adendum = $row_nilai_adendum['nilai'];
                $total_nilai = $total_nilai_murni + $total_nilai_adendum;
                $total_realisasi = $row_realisasi['realisasi'];
                if($total_nilai==0){$persen_keuangan=0;}
                else{
                    $persen_keuangan = hitung_persen($total_realisasi, $total_nilai, 2);
                }
                $total_fisik = $row_fisik['realisasi_fisik'];
                if($total_fisik==0){$persen_fisik=0;}
                else{
                    $persen_fisik = round(($total_fisik/$jumlah_kegiatan),2);
                }
                $nama_skpd = $r['nama_skpd'];
                

                if ($jumlah_kegiatan == 0) {
                    $tamp_persen_fisik = "-";
                    $tamp_jml_input = "-";
                } else {
                    if ($persen_keuangan > $persen_fisik) {
                        $tamp_persen_fisik = "<button class='btn btn-danger btn-sm'>" . $persen_fisik . "</button>";
                    } else {
                        $tamp_persen_fisik = "<button class='btn btn-success btn-sm'>" . $persen_fisik . "</button>";
                    }
                }
                if($total_nilai_adendum==0)
                {$tamp_kontrak=format_angka($total_nilai/1000);}
                else{$tamp_kontrak=format_angka($total_nilai/1000).'<br>'.$jml_adendum.' Adendum : ' .  format_angka($total_nilai_adendum/1000);}
                
                if($jumlah_kegiatan!=0)
                {
                    $no++;
                    $list_data = $list_data."
                    <tr>
                        <td style='text-align:center;'>".$no."</td>
                        <td>".$nama_skpd."</td>
                        <td style='text-align:center;'>".format_angka($jumlah_kegiatan)."</td>
                        <td style='text-align:center;'>".format_angka($jumlah_kegiatan-$berkontrak)."</td>
                        <td style='text-align:center;'>".format_angka($berkontrak)."</td>
                        <td style='text-align:center;'>".format_angka($belum_mulai)."</td>
                        <td style='text-align:center;'>".format_angka($belum_selesai)."</td>
                        <td style='text-align:center;'>".format_angka($selesai_dikerjakan)."</td>
                        <td style='text-align:right;'>".$tamp_kontrak."</td>
                        <td style='text-align:right;'>".format_angka($total_realisasi/1000)."</td>
                        <td style='text-align:center;'>".$persen_keuangan."</td>
                        <td style='text-align:center;'>".$tamp_persen_fisik."</td>
                    </tr>";
                }
            }

            $list_data = $list_data."</table>";
                
            $data = [
                "menu_active" => "kegiatan",
                "submenu_active" => null,
                "list_data" => $list_data
            ];
            $this->load->view('kegiatan/view_rekap1', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

}
