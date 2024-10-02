<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    $tbl_tampilan = $this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
    $row_kabupaten=$this->mquery->select_id("ta_kabupaten", ['id_kabupaten' => 34]);
    $nama_kabupaten=$row_kabupaten['nama_kabupaten'];
    $nama_ibukota=$row_kabupaten['kabupaten_danadesa'];
    $this->user = is_logged_in();
    $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
    $row_skpd_tahun = $this->mquery->select_id("data_skpd_tahun", ['id_skpd'=>$user['id_skpd'], 'tahun'=>$tahun]);
    $row_penanda_tangan = $this->mquery->select_id("penanda_tangan", ['id_skpd'=>$row_skpd_tahun['id_skpd'], 'is_active'=>'Y']);
    $nama_ttd=$row_penanda_tangan['nama_ttd'];
    $nip_ttd=$row_penanda_tangan['nip_ttd'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Progress Report Pengendalian Pembangunan <?=ucwords(strtolower($nama_kabupaten))?></title>
    <meta name="description" content="Progress Report Pengendalian Pembangunan kabupaten" />
    <meta name="keywords" content="aplikasi,progress,report,pengendalian,pembangunan,kabupaten,kota" />
    <meta name="author" content="Progress Report Pengendalian Pembangunan kabupaten" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="all,follow">

    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('uploads/'.$tbl_tampilan['logo'])?>">
    <style type='text/css'>
        @page {
            size: 33cm 21.5cm;
        }

        body {
            font-family: Times;
            font-size: 10px;
        }

        div.box-header {
            text-align: center;
            padding-bottom: 20px;
        }

        div.box-body {
            font-size: 12px;
            clear: both;
            margin-top: 15px;
        }

        span.title {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            display: block;
        }

        span.alamat {
            margin: 0;
            font-size: 12px;
            display: block;
        }

        span.telepon {
            font-size: 12px;
            display: block;
        }

        .alignleft {
            float: left;
            width: 100px;
            text-align: left;
        }

        .aligncenter {
            float: left;
            width: 80%;
            text-align: center;
        }

        .alignright {
            float: right;
            width: 300px;
            text-align: right;
        }

        .aligntandatangan {
            padding-left: 75%;
            float: left;
            width: 250px;
            text-align: center;
        }

        table {
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px 8px;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 26px;
        }
    </style>

</head>

<body>
    <div class='box-header'>
        <div class='alignleft'>
            <img src='<?= base_url('uploads/'.$tbl_tampilan['logo'])?>' width='50px'>
        </div>
        <div class='aligncenter'>
            <span class='title'>PEMERINTAH <?=strtoupper($nama_kabupaten)?></span>
            <span class='title'>LAPORAN DATA REALISASI KEUANGAN PER SKPD</span>
            <i>Periode <?=$priode?></i>
        </div>
    </div>
    <br>
    <br>
    <br>
    <hr>
    <div class='box-body'>
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Rekapitulasi Data Realisasi Anggaran</legend>
                <?php
                    $result = $this->mquery->select_data("data_skpd", "id_skpd ASC");
                    $no = 0;
                    $bulan_max=0;
                    $total_pendapatan_all=0;
                    $total_belanja_all=0;
                    $realisasi_pendapatan_all=0;
                    $realisasi_belanja_all=0;

                    $tanggal_now=date('Y-m-d');
                    $cek_setting = $this->mquery->count_data('setting_anggaran', ['tahun'=>$tahun]);
                    if($cek_setting!=0)
                    {
                        $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
                        $tgl_papbd=$cek_papbd['papbd'];
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
                    }
                    else
                    {
                        $hsl_stanggaran=1;
                        $anggaran_pendapatan=0;
                        $anggaran_belanja=0;
                    }

                    foreach ($result as $r) {
                        $where = array('id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2);
                        $result_max = $this->mquery->max_data_where("log_upload", "bulan", $where);
                        $cek_jml_data = $this->mquery->count_data('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2]);

                        if($cek_jml_data!=0)
                        {
                            if($bulan_max<=$result_max['bulan']){$bulan_max=$result_max['bulan'];}
                            $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2, 'bulan' => $result_max['bulan']]);
                            $row_users = $this->mquery->select_id('users', ['id_user' => $row_log_upload['user_input']]);
                            $cek_uraian_belanja = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                            if($cek_uraian_belanja!=0)
                            {
                                $row_data_uraian_belanja = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                                $total_belanja=$row_data_uraian_belanja['anggaran'];
                            }
                            else{$total_belanja=0;}
                            
                            $cek_uraian_pendapatan = $this->mquery->count_data('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                            if($cek_uraian_pendapatan!=0)
                            {
                                $row_data_uraian_pendapatan = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                                $total_pendapatan=$row_data_uraian_pendapatan['anggaran'];
                            }else{$total_pendapatan=0;}
                            
                            $row_realisasi_pendapatan = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 4]);
                            $row_realisasi_belanja = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 5]);
                            $realisasi_pendapatan=$row_realisasi_pendapatan['realisasi'];
                            $realisasi_belanja=$row_realisasi_belanja['realisasi'];
                        }
                        else
                        {
                            $total_belanja=0;
                            $total_pendapatan=0;
                            $realisasi_pendapatan=0;
                            $realisasi_belanja=0;
                        }
                        
                        $total_belanja_all=$total_belanja_all+$total_belanja;
                        $total_pendapatan_all=$total_pendapatan_all+$total_pendapatan;
                    
                        // $realisasi_pendapatan_all=$realisasi_pendapatan_all+$realisasi_pendapatan;
                        // $realisasi_belanja_all=$realisasi_belanja_all+$realisasi_belanja;
                    }

                    $row_sum_kode_4 = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['tahun' => $tahun, 'kode_rekening' => 4]);
                    $realisasi_pendapatan_all=$row_sum_kode_4['realisasi'];
                    $row_sum_kode_5 = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['tahun' => $tahun, 'kode_rekening' => 5]);
                    $realisasi_belanja_all=$row_sum_kode_5['realisasi'];

                    if($anggaran_belanja==0){$persen_total_belanja_all=0;}
                    else{$persen_total_belanja_all = round(($realisasi_belanja_all / $anggaran_belanja * 100), 2);;}

                    if($anggaran_pendapatan==0){$persen_total_pendapatan_all=0;}
                    else{$persen_total_pendapatan_all = round(($realisasi_pendapatan_all / $anggaran_pendapatan * 100), 2);}

                    $nama_kabupaten = "<h2>".$nama_kabupaten."</h2>";
                    
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
                        echo $keterangan;
                ?>
            <br>
        </fieldset>
    </div>
    <br>   
    
    <div class='box-body'>
        <table style="width: 100%;">
            <thead>
                <tr style="background-color: #1572EB; color: white;">
                    <th width="5px">NO</th>
                    <th>NAMA SKPD</th>
                    <th class="text-center">BULAN</th>
                    <th class="text-center">PENDAPATAN</th>
                    <th class="text-center">BELANJA</th>
                    <th class="text-center">TANGGAL DATA</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $no = 0;
                foreach ($row_skpd as $r) :
                    $no++;
                    $result_realisasi = 0;
                    $where = array('id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2);
                    $result_max = $this->mquery->max_data_where("log_upload", "bulan", $where);
                    $nama_skpd = "<h2>" . $r['nama_skpd'] . "</h2>";
                    $cek_data = $this->mquery->count_data('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2]);
                    
                    if($cek_data!=0)
                    {
                        $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2, 'bulan' => $result_max['bulan']]);
                        $row_users = $this->mquery->select_id('users', ['id_user' => $row_log_upload['user_input']]);
                        $cek_papbd = $this->mquery->select_id('setting_anggaran', ['tahun' => $tahun]);
                        $tgl_papbd=$cek_papbd['papbd'];
                        $tanggal_data=$row_log_upload['tgl_data'];
                        if($tanggal_data<$tgl_papbd){$hsl_stanggaran=1;}
                        else{$hsl_stanggaran=2;}
                        $row_data_uraian_belanja = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 5,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                        $row_data_uraian_pendapatan = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $r['id_skpd'], 'kode_rekening' => 4,'st_anggaran' => $hsl_stanggaran, 'tahun' => $tahun]);
                        // $row_realisasi_pendapatan = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 4]);
                        // $row_realisasi_belanja = $this->mquery->select_id('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $result_max['bulan'], 'kode_rekening' => 5]);
                        
                        $row_realisasi_pendapatan = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'kode_rekening' => 4]);
                        $row_realisasi_belanja = $this->mquery->sum_data('tbl_realisasi_skpd', 'realisasi', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'kode_rekening' => 5]);

                        $total_belanja=$row_data_uraian_belanja['anggaran'];
                        $total_pendapatan=$row_data_uraian_pendapatan['anggaran'];
                        $realisasi_belanja=$row_realisasi_belanja['realisasi'];
                        $realisasi_pendapatan=$row_realisasi_pendapatan['realisasi'];
                        $data_log_upload=$row_log_upload['tgl_data'];
                        $data_users=$row_users['username'];
                    }
                    else
                    {
                        $total_belanja=0;
                        $total_pendapatan=0;
                        $realisasi_belanja=0;
                        $realisasi_pendapatan=0;
                        $data_log_upload="";
                        $data_users="";
                    }
                    
                    if($total_belanja==0){$persen_total_belanja=0;}
                    else{$persen_total_belanja = round(($realisasi_belanja / $total_belanja * 100), 2);;}

                    if($total_pendapatan==0){$persen_total_pendapatan=0;}
                    else{$persen_total_pendapatan = round(($realisasi_pendapatan / $total_pendapatan * 100), 2);}

                    $tampil_pendapatan = "<table class='table-detail' style='width:100%;'>
                        <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_pendapatan) . "</td></tr>
                        <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($realisasi_pendapatan) . "</td></tr>
                        <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_pendapatan . "%</td></tr>
                    </table>";

                    $tampil_belanja = "<table class='table-detail' style='width:100%;'>
                        <tr><td>Anggaran</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($total_belanja) . "</td></tr>
                        <tr><td>Realisasi</td><td style='text-align:right; font-weight:bold;'>Rp" . format_rupiah($realisasi_belanja) . "</td></tr>
                        <tr><td>Persen</td><td style='text-align:right; font-weight:bold;'>" . $persen_total_belanja . "%</td></tr>
                    </table>";
                ?>
                    <tr>
                        <td style="text-align: center;"><?=$no?></td>
                        <td style="text-align: left;"><?=$r['nama_skpd']?></td>
                        <td style="text-align: center;"><?=bulan($result_max['bulan'])?></td>
                        <td style="text-align: center;"><?=$tampil_pendapatan?></td>
                        <td style="text-align: center;"><?=$tampil_belanja?></td>
                        <td style="text-align: center;"><?=$data_log_upload?></td>
                    </tr>
                <?php  endforeach; ?>
            </tbody>
        </table>
    </div>
    <br>   

    <br>     
    <div class='aligntandatangan'>
        <?=$nama_ibukota?>, <?=nama_bulan(date('Y-m-d'))?>
        <br><?=ucwords(strtolower($row_skpd_tahun['nama_skpd']))?>
        <br>       
        <br>
        <br>  
        <br>
        <br>
        <?=$nama_ttd?>
        <br>
        <?=$nip_ttd?>
    </div>
</body>

</html>