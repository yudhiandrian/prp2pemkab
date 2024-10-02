<?php $this->load->view("_partial/header"); ?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/sunburst.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/modules/sankey.js"></script>
<script src="https://code.highcharts.com/modules/organization.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">Selamat Datang <?= $users['username']; ?></h3>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <?php
                        if ($this->user['is_skpd'] == 'Y') {
                            $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
                            $cek_data_0 = $this->mquery->count_data('tbl_realisasi_skpd', ['id_skpd'=>$user['id_skpd'], 'realisasi<' => 0]);
                            $result = $this->mquery->select_by("tbl_realisasi_skpd", ['id_skpd'=>$user['id_skpd'], 'realisasi<' => 0], 'tahun ASC');
                        } else {
                            $cek_data_0 = $this->mquery->count_data('tbl_realisasi_skpd', ['realisasi<' => 0]);
                            $result = $this->mquery->select_by("tbl_realisasi_skpd", ['realisasi<' => 0], 'tahun ASC');
                        }
                        
                        if($cek_data_0>0){
                    ?>
                        <h2 style="font-size:27px; color:#FF6347">
                            Terdapat Input Data Yang Tidak Valid Pada Data LRA OPD, 
                            Mohon Untuk Segera Diperbaiki. Terima Kasih.
                        <h2>
                        <h4 style="font-size:17px; color:#FF6347">
                            Input Data LRA Menggunakan Data Akumulasi. 
                            <br>Data Bulan Februari Adalah Data Bulan Januari + Februari.
                            <br>Data Bulan Maret Adalah Data Bulan Januari + Februari + Maret.
                            <br>Dan Seterusnya.
                            <br>Sehingga Tidak Mungkin Data Bulan Tertentu Lebih Kecil Dari Data bulan Sebelumnya.
                        <h4>
                        <div class="table-responsive">
                            <table id="load-content" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th width="5px" class="text-center">No</th>
                                        <th>Nama SKPD</th>
                                        <th>Uraian</th>
                                        <th>Data Tidak Valid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no=1;
                                        foreach ($result as $r) {
                                            $row_skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $r['id_skpd'], 'tahun'=>$r['tahun']]);
                                            if($r['kode_rekening']==4){$uraian="PENDAPATAN DAERAH";}
                                            else{$uraian="BELANJA DAERAH";}
                                    ?>
                                    <tr>
                                        <td class="text-center"><?=$no++?></td>
                                        <td><?=$row_skpd['nama_skpd']?></td>
                                        <td><?=$uraian?></td>
                                        <td>
                                            Bulan <b><?=bulan($r['bulan'])?></b> Tahun <b><?=$r['tahun']?></b>
                                            <br>Data Lebih Kecil Dari Bulan Sebelumnya.
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

                    <?php
                        if ($this->user['is_skpd'] == 'Y') {
                            $user = $this->mquery->select_id('users', ['id_user' => $this->user['user']]);
                            $cek_data_0 = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd'=>$user['id_skpd'], 'bulan' => 0]);
                            $result = $this->mquery->select_by("data_skpd", ['id_skpd'=>$user['id_skpd']], 'id_skpd ASC');
                        } else {
                            $cek_data_0 = $this->mquery->count_data('data_realisasi_detail_skpd', ['bulan' => 0]);
                            $result = $this->mquery->select_data("data_skpd", 'id_skpd ASC');
                        }
                        
                        if($cek_data_0>0){
                    ?>
                            <table id="load-content" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th width="5px" class="text-center">No</th>
                                        <th>Nama SKPD</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no=1;
                                        $tahun_now=date('Y');
                                        for ($thn = 2022; $thn <= $tahun_now; $thn++){
                                            foreach ($result as $r) {
                                                $cek_data_1 = $this->mquery->count_data('data_realisasi_detail_skpd', ['id_skpd'=>$r['id_skpd'], 'tahun'=>$thn, 'bulan' => 0]);
                                                if($cek_data_1>0){
                                                $row_skpd = $this->mquery->select_id('data_skpd_tahun', ['id_skpd' => $r['id_skpd'], 'tahun'=>$thn]);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?=$no++?></td>
                                        <td><?=$row_skpd['nama_skpd']?></td>
                                        <td>
                                            Data Tahun <b><?=$thn?></b> Terdapat Bulan=0
                                            <br>Mohon untuk segera dihapus.
                                        </td>
                                    </tr>
                                    <?php }}} ?>
                                </tbody>
                            </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<?php $this->load->view('_partial/tag_close'); ?>