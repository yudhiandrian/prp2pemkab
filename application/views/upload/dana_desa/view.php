<?php
    $row_kabupaten=$this->mquery->select_id("ta_kabupaten", ['id_kabupaten' => 34]);
    $nama_kabupaten=$row_kabupaten['nama_kabupaten'];
    $tahun_now = date('Y');
?>
<?php $this->load->view("_partial/header"); ?>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">Dana Desa <?=ucwords(strtolower($nama_kabupaten))?></h3>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <iframe
                        src="https://prp2sumut.sumutprov.go.id/view-dana-desa-<?=$tahun_now?>-RFM3Q016RnJIdERtWlNZNlpOdUl6Zz09"
                        width="100%"
                        height="600"
                        style="border:0;"
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<?php $this->load->view('upload/dana_desa/js'); ?>
<?php $this->load->view('_partial/tag_close'); ?>