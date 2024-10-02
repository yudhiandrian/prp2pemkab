<?php $this->load->view("_partial/header"); ?>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold"><?= $skpd['nama_skpd']; ?> Tahun <?=$tahun?></h3>
            <?php if ($this->akses['tambah'] == 'Y') { ?>
                <div class="ml-sm-auto py-md-0">
                    <button id="tombol-upload" class="btn btn-warning btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-form-upload">Upload Excel</button>
                    <button id="tombol-tambah" data-id="<?= $skpd['id_skpd']; ?>" class="btn btn-secondary btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-form-action">TAMBAH DATA</button>
                    <?php if($php_versi<6){ ?>
                        <a class="btn btn-success btn-round btn-sm mr-2 mb-3" href="<?= site_url('kegiatan/kegiatan_fisik/download_excel/'.$tahun.'/'.$encrypt_id) ?>">Download Excel</a>
                    <?php }else{ ?>
                        <a class="btn btn-success btn-round btn-sm mr-2 mb-3" href="<?= site_url('kegiatan/cetak/index/'.$tahun.'/'.$encrypt_id) ?>">Download Excel</a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
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
                                        <th>NO. KONTRAK</th>
                                        <th>NAMA KEGIATAN</th>
                                        <th class="text-center">WAKTU</th>
                                        <th class="text-center">PAGU / NILAI KONTRAK</th>
                                        <th class="text-center">REALISASI KEUANGAN</th>
                                        <th class="text-center">REALISASI FISIK</th>
                                        <th class="text-center">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br><hr>
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="pb-3 fw-bold">Peta Lokasi</h3>
                    <?php $this->load->view('kegiatan/load_peta_skpd'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-form-action" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="load-form-action"></div>
    </div>
</div>

<div class="modal fade" id="modal-form-upload" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="load-form-upload"></div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<?php $this->load->view('kegiatan/js_detail'); ?>
<?php $this->load->view('_partial/tag_close'); ?>