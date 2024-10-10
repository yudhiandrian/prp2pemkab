<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    $tbl_tampilan = $this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
    $row_kabupaten=$this->mquery->select_id("ta_kabupaten", ['id_kabupaten' => 34]);
    $nama_kabupaten=$row_kabupaten['nama_kabupaten'];
    $nama_ibukota=$row_kabupaten['kabupaten_danadesa'];
    $this->user = is_logged_in();
    $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
    $row_skpd_tahun = $this->mquery->select_id("data_skpd_tahun", ['id_skpd'=>$user['id_skpd'], 'tahun'=>$tahun]);
    $jml_data = $this->mquery->count_data('penanda_tangan', ['id_skpd'=>$row_skpd_tahun['id_skpd'], 'is_active'=>'Y']);
    if($jml_data==0){ $nama_ttd = ""; $nip_ttd = "";}else{
        $row_penanda_tangan = $this->mquery->select_id("penanda_tangan", ['id_skpd'=>$row_skpd_tahun['id_skpd'], 'is_active'=>'Y']);
        $nama_ttd=$row_penanda_tangan['nama_ttd'];
        $nip_ttd=$row_penanda_tangan['nip_ttd'];
    }
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
            size: 21.5cm 33cm;
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
            <span class='title'>LAPORAN DATA REALISASI ANGGARAN PER KODE REKENING BULAN <?=strtoupper(bulan($bulan))?></span>
            <i><?=$row_skpd['nama_skpd']?></i>
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
                    <th>TAHUN</th>
                    <th>BULAN</th>
                    <th class="text-center">KODE REKENING</th>
                    <th class="text-center">URAIAN</th>
                    <th class="text-center">ANGGARAN</th>
                    <th class="text-center">REALISASI</th>
                    <th class="text-center">PERSEN</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $no = 0;
                foreach ($result as $r) {
                    $no++;
                    $row_anggaran = $this->mquery->select_id('data_uraian_kegiatan_skpd', ['id_skpd' => $row_skpd['id_skpd'], 'tahun' => $tahun, 'kode_rekening' => $r['kode_rekening']]);
                    $row_uraian = $this->mquery->select_id('data_uraian_kegiatan_pemko', ['kode_rekening' => $r['kode_rekening']]);
                    
                    if($row_anggaran['anggaran']==0){$persen=0;}
                    else{$persen=round(($r['realisasi']/$row_anggaran['anggaran']*100),2);}
                    if($row_anggaran['anggaran']!=0){
                ?>
                    <tr>
                        <td style="text-align: center;"><?=$no?></td>
                        <td style="text-align: center;"><?=$tahun?></td>
                        <td style="text-align: center;"><?=bulan($bulan)?></td>
                        <td style="text-align: left;"><?=$r['kode_rekening']?></td>
                        <td style="text-align: left;"><?=$row_uraian['uraian']?></td>
                        <td style="text-align: center;"><?=format_rupiah($row_anggaran['anggaran'])?></td>
                        <td style="text-align: center;"><?=format_rupiah($r['realisasi'])?></td>
                        <td style="text-align: center;"><?=format_rupiah($persen)?>%</td>
                    </tr>
                <?php }} ?>
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