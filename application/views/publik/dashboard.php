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
            <h3 class="text-white pb-3 fw-bold">Progress Report Pengendalian Pembangunan <?=ucwords(strtolower($nama_kabupaten))?></h3>
			<div class="ml-sm-auto py-md-0">
                <a href="<?= base_url()?>" class="btn btn-danger btn-round btn-sm"><i class="fa fa-home"></i> Home</a> 
            </div>
		</div>
    </div>
</div>


<div class="page-inner mt--5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Pilih Tahun</legend>
                        <div class="form-group">
                            <div class="row mb-2">
                                <div class="col-md-8 col-sm-6" style="margin-bottom: 3px; margin-top: 3px;">
                                    <select name="tahun" id="tahun" class="select2 form-control">
                                        <?php
                                        $tahun_sekarang = date('Y');
                                        $result_tahun   = [];
                                        for ($i = $tahun_sekarang; $i >= tahun_mulai(); $i--) {
                                            array_push($result_tahun, ['tahun' => $i]);
                                        }
                                        ?>
                                        <?php foreach ($result_tahun as $r) : ?>
                                            <?php if ($r['tahun'] == $tahun_data) : ?>
                                                <option value="<?= $r['tahun']; ?>" <?= "selected"; ?>><?= $r['tahun']; ?></option>
                                            <?php else : ?>
                                                <option value="<?= $r['tahun']; ?>"><?= $r['tahun']; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-6" style="margin-bottom: 3px; margin-top: 3px;">
                                    <button type="button" id="btn-tampil" class="btn btn-success btn-block"> <i class="fa fa-search"></i> TAMPILKAN</button>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-lg-12 col-12">
                    <?php $this->load->view('grafik/daerah/v_anggaran_pendapatan_provinsi'); ?>  
                </div>
            </div>
            <br>
            
            <div class="row">
                <div class="col-lg-12 col-12">
                    <?php $this->load->view('grafik/daerah/v_anggaran_belanja_provinsi'); ?>  
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 col-12">
                    <?php $this->load->view('grafik/daerah/v_arus_kas'); ?>  
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 col-12">
                    <?php $this->load->view('grafik/daerah/v_mandatory'); ?>  
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 col-12">
                    <?php $this->load->view('grafik/daerah/v_realisasi_pendapatan_belanja_provinsi'); ?>  
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
                    <h3 style="font-weight: bold;">Rating Persentase Realisasi Belanja oleh OPD <?=$nama_kabupaten?> periode 1 Januari s.d <?= $this->fungsi->nama_bulan($realisasi_apbd_provinsi['tanggal_data'])?></h3>
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
                                        <th>NAMA OPD</th>
                                        <th class="text-center">Bulan</th>
                                        <th class="text-center">Pendapatan</th>
                                        <th class="text-center">Belanja</th>
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
<?php $this->load->view('grafik/daerah/js_realisasi_pendapatan'); ?>
<?php $this->load->view('grafik/daerah/js_struktur_anggaran_provinsi'); ?>
<?php $this->load->view('grafik/daerah/js_alokasi_pendapatan_provinsi'); ?>

<?php $this->load->view('grafik/daerah/js_alokasi_belanja_provinsi'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_belanja'); ?>

<?php $this->load->view('grafik/daerah/js_target_pad_provinsi'); ?>
<?php $this->load->view('grafik/daerah/js_target_realisasi_pad_provinsi'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_pad_provinsi'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_pendapatan_provinsi'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_belanja_provinsi'); ?>
<?php $this->load->view('grafik/daerah/js_serapan_belanja_provinsi'); ?>

<?php $this->load->view('grafik/daerah/js_arus_kas_bulanan'); ?>
<?php $this->load->view('grafik/daerah/js_arus_kas_triwulan'); ?>

<?php $this->load->view('grafik/daerah/js_mandatory'); ?>
<?php $this->load->view('grafik/daerah/js_mandatory_papbd'); ?>

<?php $this->load->view('grafik/daerah/js_alokasi_covid'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_covid'); ?>

<?php $this->load->view('grafik/daerah/js_realisasi_dak'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_dak_persen'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_dak1'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_dak1_persen'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_dekon'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_dekon_persen'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_tp'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_tp_persen'); ?>

<?php $this->load->view('grafik/daerah/js_realisasi_belanja_sekretariat'); ?>
<?php $this->load->view('grafik/daerah/js_realisasi_sekre'); ?>

<?php $this->load->view('publik/js_realisasi_belanja'); ?>


<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    $(document).on("click", "#btn-tampil", function(e) {
        e.preventDefault();
        var self = this;
        $(self).attr('disabled', true);
        $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        var tahun = $('#tahun').val();
        window.location.href = "<?= site_url('apbd-'); ?>" + tahun;
    });

		Circles.create({
			id:'circles-1',
			radius:80,
			value:<?= hitung_persen($realisasi_apbd_provinsi['real_pendapatan_terakhir'],$struktur_anggaran_provinsi['pendapatan_setting'],1); ?>,
			maxValue:100,
			width:20,
			text: <?= hitung_persen($realisasi_apbd_provinsi['real_pendapatan_terakhir'],$struktur_anggaran_provinsi['pendapatan_setting'],1); ?>,
			colors:['#f1f1f1', '#FF9E27'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		Circles.create({
			id:'circles-2',
			radius:80,
			value:<?= hitung_persen($realisasi_apbd_provinsi['real_belanja_terakhir'],$struktur_anggaran_provinsi['belanja_setting'],1); ?>,
			maxValue:100,
			width:20,
			text: <?= hitung_persen($realisasi_apbd_provinsi['real_belanja_terakhir'],$struktur_anggaran_provinsi['belanja_setting'],1); ?>,
			colors:['#f1f1f1', '#2BB930'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

        Circles.create({
            id:'circles-3',
            radius:100,
            value:<?= hitung_persen(324680879612,363999195257,1); ?>,
            maxValue:100,
            width:20,
            text: <?= hitung_persen(324680879612,363999195257,1); ?>,
            colors:['#f1f1f1', '#2BB930'],
            duration:400,
            wrpClass:'circles-wrp',
            textClass:'circles-text',
            styleWrapper:true,
            styleText:true
        })

        Circles.create({
            id:'circles-4',
            radius:120,
            value:<?= hitung_persen($realisasi_pb_provinsi['dk_real_total'],$realisasi_pb_provinsi['dk_pagu_total'],1); ?>,
            maxValue:100,
            width:30,
            text: <?= hitung_persen($realisasi_pb_provinsi['dk_real_total'],$realisasi_pb_provinsi['dk_pagu_total'],1); ?>,
            colors:['#f1f1f1', '#FF9E27'],
            duration:400,
            wrpClass:'circles-wrp',
            textClass:'circles-text',
            styleWrapper:true,
            styleText:true
        })

        Circles.create({
            id:'circles-5',
            radius:120,
            value:<?= hitung_persen($realisasi_pb_provinsi['tp_real_total'],$realisasi_pb_provinsi['tp_pagu_total'],1); ?>,
            maxValue:100,
            width:30,
            text: <?= hitung_persen($realisasi_pb_provinsi['tp_real_total'],$realisasi_pb_provinsi['tp_pagu_total'],1); ?>,
            colors:['#f1f1f1', '#2BB930'],
            duration:400,
            wrpClass:'circles-wrp',
            textClass:'circles-text',
            styleWrapper:true,
            styleText:true
        })

        Circles.create({
            id:'circles-6',
            radius:120,
            value:<?= hitung_persen($realisasi_pb_provinsi['dak_real_total'],$realisasi_pb_provinsi['dak_pagu_total'],1); ?>,
            maxValue:100,
            width:30,
            text: <?= hitung_persen($realisasi_pb_provinsi['dak_real_total'],$realisasi_pb_provinsi['dak_pagu_total'],1); ?>,
            colors:['#f1f1f1', '#FF9E27'],
            duration:400,
            wrpClass:'circles-wrp',
            textClass:'circles-text',
            styleWrapper:true,
            styleText:true
        })

        Circles.create({
            id:'circles-7',
            radius:120,
            value:<?= hitung_persen($realisasi_pb_provinsi['dak_real_total'],$realisasi_pb_provinsi['dak_pagu_total'],1); ?>,
            maxValue:100,
            width:30,
            text: <?= hitung_persen($realisasi_pb_provinsi['dak_real_total'],$realisasi_pb_provinsi['dak_pagu_total'],1); ?>,
            colors:['#f1f1f1', '#2BB930'],
            duration:400,
            wrpClass:'circles-wrp',
            textClass:'circles-text',
            styleWrapper:true,
            styleText:true
        })

        Circles.create({
            id:'circles-11',
            radius:120,
            value:<?= hitung_persen($realisasi_pb_provinsi['realisasi_belanja_asekre'],$realisasi_pb_provinsi['pagu_belanja_asekre'],1); ?>,
            maxValue:100,
            width:30,
            text: <?= hitung_persen($realisasi_pb_provinsi['realisasi_belanja_asekre'],$realisasi_pb_provinsi['pagu_belanja_asekre'],1); ?>,
            colors:['#f1f1f1', '#2BB930'],
            duration:400,
            wrpClass:'circles-wrp',
            textClass:'circles-text',
            styleWrapper:true,
            styleText:true
        })
	</script>

<?php $this->load->view('_partial/tag_close'); ?>