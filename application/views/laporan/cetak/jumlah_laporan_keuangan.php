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

<body onload='window.print()' onfocus='window.close()'>
    <div class='box-header'>
        <div class='alignleft'>
            <img src='<?= base_url('uploads/'.$tbl_tampilan['logo'])?>' width='50px'>
        </div>
        <div class='aligncenter'>
            <span class='title'>PEMERINTAH <?=strtoupper($nama_kabupaten)?></span>
            <span class='title'>LAPORAN REKAPITULASI JUMLAH DATA LAPORAN KEUANGAN</span>
            <i>Periode <?=$priode?></i>
        </div>
    </div>
    <br>
    <br>
    <br>
    <hr>
    <div class='box-body'>
        <table style="width: 100%;">
            <thead>
                <tr style="background-color: #1572EB; color: white;">
                    <th width="5px">NO</th>
                    <th>NAMA SKPD</th>
                    <th class="text-center">BULAN</th>
                    <th class="text-center">TANGGAL DATA</th>
                    <th class="text-center">USER INPUT</th>
                    <th class="text-center">JUMLAH LAPORAN</th>
                    <th class="text-center">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $no = 0;
                foreach ($row_skpd as $r) :
                    $no++;
                    $keterangan="";
                    $jumlah=0;
                    for ($i = 1; $i < 13; $i++){
                        $cek_laporan = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'bulan' => $i]);
                        if($cek_laporan>0){$keterangan .= bulan($i).", "; $jumlah++;}
                        
                    }
                    $cek_log_upload = $this->mquery->count_data('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2]);
                    if($cek_log_upload!=0)
                    {
                        $where = array('id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2);
                        $result_max = $this->mquery->max_data_where("log_upload", "bulan", $where);
                        $row_log_upload = $this->mquery->select_id('log_upload', ['id_skpd' => $r['id_skpd'], 'tahun' => $tahun, 'st_data' => 2, 'bulan' => $result_max['bulan']]);
                        $row_users = $this->mquery->select_id('users', ['id_user' => $row_log_upload['user_input']]); 
                        $tamp_bulan=bulan($result_max['bulan']);
                        $row_tgl_data=$row_log_upload['tgl_data'];
                        $row_username=$row_users['username'];
                    }
                    else
                    {
                        $tamp_bulan="";
                        $row_tgl_data="";
                        $row_username="";
                    }
                ?>
                    <tr>
                        <td style="text-align: center;"><?=$no?></td>
                        <td style="text-align: left;"><?=$r['nama_skpd']?></td>
                        <td style="text-align: center;"><?=$tamp_bulan?></td>
                        <td style="text-align: center;"><?=$row_tgl_data?></td>
                        <td style="text-align: center;"><?=$row_username?></td>
                        <td style="text-align: center;"><?=$jumlah?></td>
                        <td style="text-align: left;"><?=$keterangan?></td>
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