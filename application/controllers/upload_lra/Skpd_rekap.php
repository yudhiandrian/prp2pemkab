<?php
defined('BASEPATH') or exit('No direct script access allowed');
$php_versi = substr(phpversion(),0,1);
if($php_versi>6){
    require 'vendor/autoload.php';
}
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
class Skpd_rekap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_fungsi', 'fungsi');
        $this->user = is_logged_in();
        $this->akses = cek_akses_user();
        $php_versi = substr(phpversion(),0,1);
        if($php_versi<6){$this->load->library('PHPExcel');}
    }

    public function index()
    {
        if ($this->akses['akses'] == 'Y') {
            $row_kabupaten=$this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
            $nama_kabupaten=$row_kabupaten['sub2'];
            $tahun = $this->input->post('tahun');
            $result = $this->mquery->select_by("data_skpd_tahun", ['tahun'=>$tahun], "id_skpd ASC");
            $no = 0;
            $bulan_max=0;
            $total_pendapatan_all=0;
            $total_belanja_all=0;
            $realisasi_pendapatan_all=0;
            $realisasi_belanja_all=0;

            $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
            $tgl_papbd=$cek_papbd['papbd'];
            $tanggal_now=date('Y-m-d');

            if($tanggal_now<$tgl_papbd)
            {
                $hsl_stanggaran=1;
                $anggaran_pendapatan=$cek_papbd['pendapatan'];
                $anggaran_belanja=$cek_papbd['belanja'];
            }
            else
            {
                $hsl_stanggaran=2;
                $anggaran_pendapatan=$cek_papbd['pendapatan_p'];
                $anggaran_belanja=$cek_papbd['belanja_p'];
            }

            
            foreach ($result as $r) {
                $where = array('id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2);
                $result_max = $this->mquery->max_data_where("log_upload", "bulan", $where);
                if($bulan_max<=$result_max['bulan']){$bulan_max=$result_max['bulan'];}
                $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2, 'bulan' => $result_max['bulan']]);

                $jml_belanja = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                if($jml_belanja==0){$total_belanja=0;}else{
                    $row_data_uraian_belanja = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                    $total_belanja=$row_data_uraian_belanja['anggaran'];
                }
                $total_belanja_all=$total_belanja_all+$total_belanja;

                $jml_pendapatan = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                if($jml_pendapatan==0){$total_pendapatan=0;}else{
                    $row_data_uraian_pendapatan = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                    $total_pendapatan=$row_data_uraian_pendapatan['anggaran'];
                }
                $total_pendapatan_all=$total_pendapatan_all+$total_pendapatan;

                $row_realisasi_pendapatan = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 4]);
                $row_realisasi_belanja = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 5]);
               
                // $realisasi_pendapatan_all=$realisasi_pendapatan_all+$row_realisasi_pendapatan['realisasi'];
                // $realisasi_belanja_all=$realisasi_belanja_all+$row_realisasi_belanja['realisasi'];
            }

            $row_sum_kode_4 = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['tahun' => $tahun, 'kode_rekening' => 4]);
            $realisasi_pendapatan_all=$row_sum_kode_4['realisasi'];
            $row_sum_kode_5 = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['tahun' => $tahun, 'kode_rekening' => 5]);
            $realisasi_belanja_all=$row_sum_kode_5['realisasi'];

            if($anggaran_belanja==0){$persen_total_belanja_all=0;}
            else{$persen_total_belanja_all = round(($realisasi_belanja_all / $anggaran_belanja * 100), 2);;}

            if($anggaran_pendapatan==0){$persen_total_pendapatan_all=0;}
            else{$persen_total_pendapatan_all = round(($realisasi_pendapatan_all / $anggaran_pendapatan * 100), 2);}

            if ($this->user['is_skpd'] == 'Y') {
                $nama_kabupaten = $nama_kabupaten;
            } else {
                $nama_kabupaten = "<a href=" . base_url("upload-lra-opd/detail_rekap/".$tahun."/".$bulan_max) . "><h2>".$nama_kabupaten."</h2></a>";
            }
            
            $keterangan = "<table class='table-detail' style='width:100%; text-align:center; font-weight:bold;'>
                    <tr>
                        <td rowspan='5' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>".$nama_kabupaten."</td>
                        <td style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>BULAN</td>
                        <td colspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>PENDAPATAN</td>
                        <td colspan='2' style='background-image: linear-gradient(to bottom, #2ebef3, #d8f4fd);'>BELANJA</td>
                    </tr>
                    <tr>
                        <td rowspan='4' style='background-image: linear-gradient(to bottom, #ff3e3f, #ffc7c6);'>".bulan($bulan_max)."</td>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Anggaran</td>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db); text-align:right;'>Rp " . format_rupiah($anggaran_pendapatan) . "</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef);'>Anggaran</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef); text-align:right;'>Rp " . format_rupiah($anggaran_belanja) . "</td>
                    </tr>
                    <tr>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Anggaran [SKPD]</td>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db); text-align:right;'>Rp " . format_rupiah($total_pendapatan_all) . "</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef);'>Anggaran [SKPD]</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef); text-align:right;'>Rp " . format_rupiah($total_belanja_all) . "</td>
                    </tr>
                    <tr>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db);'>Realisasi</td>
                        <td style='background-image: linear-gradient(to bottom, #ffcb2d, #fff5db); text-align:right;'>Rp " . format_rupiah($realisasi_pendapatan_all) . "</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef);'>Realisasi</td>
                        <td style='background-image: linear-gradient(to bottom, #9cd561, #f6f9ef); text-align:right;'>Rp " . format_rupiah($realisasi_belanja_all) . "</td>
                    </tr>
                    <tr>
                        <td style='background-image: linear-gradient(to Top, #ffcb2d, #fff5db);'>Persen</td>
                        <td style='background-image: linear-gradient(to Top, #ffcb2d, #fff5db); text-align:right;'>" . $persen_total_pendapatan_all . "%</td>
                        <td style='background-image: linear-gradient(to Top, #9cd561, #f6f9ef);'>Persen</td>
                        <td style='background-image: linear-gradient(to Top, #9cd561, #f6f9ef); text-align:right;'>" . $persen_total_belanja_all . "%</td>
                    </tr>
                </table>";
            $data = [
                "menu_active" => "upload_data",
                "keterangan" => $keterangan
            ];
            $this->load->view('upload_lra/skpd/view_rekap', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function detail_rekap($tahun, $bulan)
    {
        if ($this->akses['akses'] == 'Y') {
            $data = [
                "menu_active" => "upload_data",
                "submenu_active" => "upload-lra-opd",
                "tahun" => $tahun,
                "bulan" => $bulan
            ];
            $this->load->view('upload_lra/skpd/view_detail_rekap', $data);
        } else {
            redirect(site_url('blocked'));
        }
    }

    public function load_detail_rekap()
    {
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
        $tgl_papbd=$cek_papbd['papbd'];
        $data = [];
        $no = 0;
        for ($i = 1; $i <= $bulan; $i++){
            $no++;
            $nama_bulan = "<a href=" . base_url("upload-lra-opd/detail_rekap2/" . $tahun. "/". $i) . ">" . bulan($i) . "</a>";
            $max_log_upload = $this->mquery->max_data_where('log_upload', 'tgl_data', ['tahun'=> $tahun, 'bulan'=> $i, 'st_data'=> 2]);
            $tanggal_data=$max_log_upload['tgl_data'];

            if($tanggal_data<$tgl_papbd)
            {
                $hsl_stanggaran=1;
                $anggaran_pendapatan=$cek_papbd['pendapatan'];
                $anggaran_belanja=$cek_papbd['belanja'];
            }
            else
            {
                $hsl_stanggaran=2;
                $anggaran_pendapatan=$cek_papbd['pendapatan_p'];
                $anggaran_belanja=$cek_papbd['belanja_p'];
            }

            $row_data_uraian_belanja = $this->mquery->sum_data('data_uraian_kegiatan_skpd', 'anggaran', ['kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
            $total_belanja=$row_data_uraian_belanja['anggaran'];
            $row_data_uraian_pendapatan = $this->mquery->sum_data('data_uraian_kegiatan_skpd', 'anggaran', ['kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
            $total_pendapatan=$row_data_uraian_pendapatan['anggaran'];
            
            // $row_realisasi_pendapatan = $this->mquery->sum_data('data_realisasi_detail_skpd', 'realisasi', ['tahun' => $tahun, 'bulan' => $i, 'kode_rekening' => 4]);
            // $row_realisasi_belanja = $this->mquery->sum_data('data_realisasi_detail_skpd', 'realisasi', ['tahun' => $tahun, 'bulan' => $i, 'kode_rekening' => 5]);

            $row_realisasi_pendapatan = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['tahun' => $tahun, 'bulan<=' => $i, 'kode_rekening' => 4]);
            $row_realisasi_belanja = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['tahun' => $tahun, 'bulan<=' => $i, 'kode_rekening' => 5]);

            if($anggaran_belanja==0){$persen_total_belanja=0;}
            else{$persen_total_belanja = round(($row_realisasi_belanja['realisasi'] / $anggaran_belanja * 100), 2);}
            if($anggaran_pendapatan==0){$persen_total_pendapatan=0;}
            else{$persen_total_pendapatan = round(($row_realisasi_pendapatan['realisasi'] / $anggaran_pendapatan * 100), 2);}

            $tampil_pendapatan = "<table class='table-detail' style='width:100%;'>
                <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($anggaran_pendapatan) . "</td></tr>
                <tr><td>Anggaran [SKPD]</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_pendapatan) . "</td></tr>
                <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($row_realisasi_pendapatan['realisasi']) . "</td></tr>
                <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_pendapatan . "%</td></tr>
            </table>";

            $tampil_belanja = "<table class='table-detail' style='width:100%;'>
                <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($anggaran_belanja) . "</td></tr>
                <tr><td>Anggaran [SKPD]</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_belanja) . "</td></tr>
                <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($row_realisasi_belanja['realisasi']) . "</td></tr>
                <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_belanja . "%</td></tr>
            </table>";

            $jml_data = $this->mquery->count_data('data_realisasi_detail_skpd', ['tahun' => $tahun, 'bulan' => $i, 'kode_rekening' => 5]);
            $jml_skpd = $this->mquery->count_data('data_skpd_tahun', ['tahun'=>$tahun]);

            $row = [
                'no' => $no,
                'tahun' => $tahun,
                'bulan' => $nama_bulan,
                'pendapatan' => $tampil_pendapatan,
                'belanja' => $tampil_belanja,
                'tanggal_data' => $tanggal_data,
                'jml_data' => $jml_data."/".$jml_skpd
            ];
            
            $data[] = $row;
        }
        $output['data'] = $data;
        echo json_encode($output);
    }
    

    public function detail_rekap2($tahun, $bulan)
    {
        $cek_jml = $this->mquery->count_data('log_upload', ['tahun' => $tahun, 'bulan' => $bulan, 'st_data' => 2]);
        if($cek_jml==0){$nama_periode = "";}else{
            $row_log_upload = $this->mquery->select_id('log_upload', ['tahun' => $tahun, 'bulan' => $bulan, 'st_data' => 2]);
            $nama_periode = $this->fungsi->nama_bulan($row_log_upload['tgl_data']);
        }
        
        $data = [
            "menu_active" => "upload_data",
            "submenu_active" => "upload-lra-opd",
            "tahun" => $tahun,
            "bulan" => $bulan,
            "nama_periode" => $nama_periode
        ];
        $this->load->view('upload_lra/skpd/view_detail_rekap2', $data);
    }

    public function load_detail_rekap2()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
        $tgl_papbd=$cek_papbd['papbd'];
        $max_log_upload = $this->mquery->max_data_where('log_upload', 'tgl_data', ['tahun'=> $tahun, 'bulan'=> $bulan, 'st_data'=> 2]);
        $tanggal_data=$max_log_upload['tgl_data'];

        if($tanggal_data<$tgl_papbd)
        { $hsl_stanggaran=1;}
        else {$hsl_stanggaran=2;}
       
        $result = $this->mquery->select_by('data_uraian_kegiatan_skpd', ['tahun' => $tahun, 'st_anggaran' => $hsl_stanggaran], "kode_rekening ASC");
        $data = [];
        $no = 0;
        foreach ($result as $r) {
            
            $row_uraian = $this->mquery->select_id('data_uraian_kegiatan_pemko', ['kode_rekening' => $r['kode_rekening']]);
            $cek_jml = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $bulan]);
            if($cek_jml==0){$hsl_realisasi=0; $persen=0;}
            else
            {
                $row_realisasi = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $bulan]);
                $hsl_realisasi=$row_realisasi['realisasi'];
                if($r['anggaran']==0){$persen=0; $hsl_realisasi=0; }
                else{$persen=round(($hsl_realisasi/$r['anggaran']*100),2);}
            }
            $row_skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $r['id_skpd'], 'tahun'=> $tahun]);

            if($r['anggaran']!=0)
            {
                $no++;
                $row = [
                    'no' => $no,
                    'skpd' => $row_skpd['nama_skpd'],
                    'kode_rekening' => "[".$r['kode_rekening']."]",
                    'uraian' => $row_uraian['uraian'],
                    'anggaran' => format_rupiah($r['anggaran']),
                    'realisasi' => format_rupiah($hsl_realisasi),
                    'persen' => format_rupiah($persen)." %"
                ];
                $data[] = $row;
            }
        }

        $output['data'] = $data;
        echo json_encode($output);
    }

}
