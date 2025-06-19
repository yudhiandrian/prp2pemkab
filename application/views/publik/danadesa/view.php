<?php $this->load->view("_partial/header_frontend"); ?>
<script src="https://code.highcharts.com/6.0.0/highcharts.js"></script>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white fw-bold" style="margin-bottom: 0;">Progress Report Pengendalian Pembangunan <?=ucwords(strtolower($result_kabupaten_nama))?></h3>
            <div class="ml-sm-auto py-md-0">
                <a href="<?= base_url()?>" class="btn btn-danger btn-round btn-sm"><i class="fa fa-home"></i> Home</a> 
            </div>
        </div>
    </div>
</div>


<?php 
    error_reporting(0); 
    $tahun_now = date('Y');
?>
<div class="page-inner">
    <div class="row">
        <div class="col-lg-12">
            <iframe
                src="https://prp2sumut.sumutprov.go.id/view-dana-desa-<?=$tahun_now?>-RFM3Q016RnJIdERtWlNZNlpOdUl6Zz09"
                width="100%"
                height="600"
                style="border:0;"
                loading="lazy">
            </iframe>
        </div>
    </div>
    <!-- </div>
    </div> -->
</div>

<?php $this->load->view('_partial/footer'); ?>
<?php $this->load->view('_partial/tag_close'); ?>