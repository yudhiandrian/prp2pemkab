<?php $this->load->view("_partial/header_frontend"); ?>

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
            <h3 class="text-white pb-3 fw-bold">Progress Report Pengendalian Pembangunan Provinsi Sumatera Utara</h3>
            <div class="ml-sm-auto py-md-0">
                <a href="<?= base_url()?>" class="btn btn-danger btn-round btn-sm"><i class="fa fa-home"></i> Home</a> 
                <a href="<?= site_url('apbd-provinsi') ?>" class="btn btn-danger btn-round btn-sm"></i> Provinsi</a> 
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <?php $this->load->view('grafik/daerah/v_realisasi_pendapatan_provinsi'); ?>         
                    </div>         
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h3 style="font-weight: bold;">Rating Realisasi Pendapatan Asli Daerah (PAD) oleh OPD Provinsi Sumatera Utara periode 1 Januari s.d <?= $this->fungsi->nama_bulan($realisasi_apbd_provinsi['tanggal_data'])?></h3>
                    <div class="preload" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                        <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                        <h5>Sedang memuat data...</h5>
                    </div>
                    <div id="display-content" style="display: none;">
                        <div class="table-responsive">
                            <table id="load-content" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th width="5px">NO</th>
                                        <th>NAMA SKPD</th>
                                        <th class="text-center">Anggaran Pendapatan</th>
                                        <th class="text-center">Anggaran Pendapatan Asli Daerah (PAD)</th>
                                        <th class="text-center">Realisasi Pendapatan Asli Daerah (PAD)</th>
                                        <th class="text-center">Persen Realisasi PAD</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <hr style="border-bottom: 3px solid #eee;">
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<?php $this->load->view('publik/js_realisasi_pendapatan'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_pendapatan_provinsi'); ?>
<?php $this->load->view('_partial/tag_close'); ?>