<?php $this->load->view("_partial/header_frontend"); ?>

<script src="<?= base_url('assets/highphp/js/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="https://code.highcharts.com/6.0.0/highcharts.js"></script>
<script src="https://code.highcharts.com/6.0.0/modules/sunburst.js"></script>
<script src="<?= base_url('assets/highphp/js/highcharts-3d.js'); ?>"></script>
<script src="<?= base_url('assets/highphp/js/exporting.js'); ?>"></script>
<!-- <script src="<?= base_url('assets/highphp/js/export-data.js'); ?>"></script>
<script src="<?= base_url('assets/highphp/js/accessibility.js'); ?>"></script> -->

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
    <div class="card full-height">
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
                                            <?php if ($r['tahun'] == $tahun) : ?>
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
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="preload_realisasi_pad" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                                <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                                <h5>Sedang memuat data...</h5>
                            </div>
                            <div id="load-data-realisasi-pad" style="display: none;"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="preload_realisasi_belanja" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                                <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                                <h5>Sedang memuat data...</h5>
                            </div>
                            <div id="load-data-realisasi-belanja" style="display: none;"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="preload_arus_kas" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                                <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                                <h5>Sedang memuat data...</h5>
                            </div>
                            <div id="load-data-arus-kas" style="display: none;"></div>
                        </div>
                    </div>

                </div>
            </div>
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
                        <h2>Daftar Kegiatan Pada <?=$skpd['nama_skpd']?> Tahun <?=$tahun?></h2>
                        <div class="table-responsive">
                            <table id="load-content" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th width="5px">NO</th>
                                        <th>NAMA KEGIATAN</th>
                                        <th class="text-center">WAKTU</th>
                                        <th class="text-center">NILAI KONTRAK</th>
                                        <th class="text-center">REALISASI KEUANGAN</th>
                                        <th class="text-center">REALISASI FISIK</th>
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

<?php $this->load->view('_partial/footer'); ?>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        var tahun = $('#tahun').val();
        load_data_realisasi_pad(tahun);
        load_data_realisasi_belanja(tahun);
        load_data_arus_kas(tahun);
        load_data(tahun);
    });

    function load_data(tahun) {
        $('#display-content').css('display', 'none');
        $('.preload').show();
        table = $('#load-content').DataTable({
            destroy: true,
            ordering: false,
            bAutoWidth: false,
            initComplete: function() {
                $('#display-content').css('display', 'block');
                $('.preload').hide();
            },
            ajax: {
                url: "<?= site_url('publik/skpd/load_kegiatan'); ?>",
                type: 'POST',
                data: {
                    skpd : <?=$skpd['id_skpd']?>,
                    tahun_data : tahun
                }
            },
            columnDefs: [{
                className: 'text-right',
                targets: [3, 4]
            }, {
                className: 'text-center',
                targets: [0, 2, 5]
            }],
            columns: [{
                data: 'no'
            }, {
                data: 'keperluan'
            }, {
                data: 'waktu'
            }, {
                data: 'nilai'
            }, {
                data: 'realisasi'
            }, {
                data: 'persen'
            }]
        });
    }

        function load_data_realisasi_pad(tahun) {
            $('#load-data-realisasi-pad').css('display', 'none');
            $('.preload_realisasi_pad').show();
            $.ajax({
                url: '<?= site_url('data-apbd/realisasi_pad_opd'); ?>',
                type: "POST",
                data: {
                    tahun : tahun,
                    id_skpd : <?=$skpd['id_skpd']?>
                },
                dataType: "html",
                success: function(data) {
                    $('#load-data-realisasi-pad').css('display', 'block');
                    $('.preload_realisasi_pad').hide();
                    $('#btn-tampil').html("<i class='fa fa-search'></i> TAMPILKAN");
                    $('#btn-tampil').attr('disabled', false);
                    $('#load-data-realisasi-pad').html(data);
                }
            });
        }

        function load_data_realisasi_belanja(tahun) {
            $('#load-data-realisasi-belanja').css('display', 'none');
            $('.preload_realisasi_belanja').show();
            $.ajax({
                url: '<?= site_url('data-apbd/realisasi_belanja_opd'); ?>',
                type: "POST",
                data: {
                    tahun : tahun,
                    id_skpd : <?=$skpd['id_skpd']?>
                },
                dataType: "html",
                success: function(data) {
                    $('#load-data-realisasi-belanja').css('display', 'block');
                    $('.preload_realisasi_belanja').hide();
                    $('#load-data-realisasi-belanja').html(data);
                }
            });
        }

        function load_data_arus_kas(tahun) {
            $('#load-data-arus-kas').css('display', 'none');
            $('.preload_arus_kas').show();
            $.ajax({
                url: '<?= site_url('data-apbd/arus_kas_opd'); ?>',
                type: "POST",
                data: {
                    tahun : tahun,
                    id_skpd : <?=$skpd['id_skpd']?>
                },
                dataType: "html",
                success: function(data) {
                    $('#load-data-arus-kas').css('display', 'block');
                    $('.preload_arus_kas').hide();
                    $('#load-data-arus-kas').html(data);
                }
            });
        }

    $(document).on("click", "#btn-tampil", function(e) {
    e.preventDefault();
    var self = this;
    $(self).attr('disabled', true);
    $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
    var tahun = $('#tahun').val();
    load_data_realisasi_pad(tahun);
    load_data_realisasi_belanja(tahun);
    load_data_arus_kas(tahun);
});
</script>
<?php $this->load->view('_partial/tag_close'); ?>