<?php $this->load->view("_partial/header"); ?>
<?php
    $tahun_now=date('Y');
?>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">Data Realisasi Anggaran Defenitif Pada APBD <?= $skpd['nama_skpd'] ?> periode 1 Januari 2022 s.d <?=$nama_periode?></h3>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">

                <div class="col-sm-12">
                    <div class="d-flex align-items-left flex-column flex-sm-row">
                    <h3 class="pb-3 fw-bold"><?= $skpd['nama_skpd'] ?></h3>
                        <div class="ml-sm-auto py-md-0">
                            
                        </div>
                    </div>
                    <div class="preload" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                        <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                        <h5>Sedang memuat data...</h5>
                    </div>
                    <div id="display-content" style="display: none;">
                        <div class="table-responsive">
                            <table id="load-content" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th width="5px">No</th>
                                        <th class="text-center">Kode Rekening</th>
                                        <th class="text-center">Uraian</th>
                                        <th class="text-center">Anggaran</th>
                                        <th class="text-center">Pegawai</th>
                                        <th class="text-center">Barang & Jasa</th>
                                        <th class="text-center">Modal</th>
                                        <th class="text-center">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-form-action" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div id="load-form-action"></div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<script src="<?= base_url('assets/js/jquery.mask.min.js'); ?>"></script>
<?php $this->load->view('upload_anggaran_defenitif/skpd/js_detail2'); ?>
<?php $this->load->view('_partial/tag_close'); ?>