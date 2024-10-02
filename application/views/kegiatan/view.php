<?php $this->load->view("_partial/header"); ?>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">

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
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Rekapitulasi Kegiatan Fisik</legend>
                        <div class="preload_rekap" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                            <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                            <h5>Sedang memuat data...</h5>
                        </div>
                        <div id="load-data-rekap" style="display: none;"></div>
                        <br>
                    </fieldset>
                </div>

                <div class="col-sm-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Rekapitulasi Kegiatan Fisik DAK</legend>
                        <div class="preload_rekap_dak" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                            <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                            <h5>Sedang memuat data...</h5>
                        </div>
                        <div id="load-data-rekap-dak" style="display: none;"></div>
                        <br>
                    </fieldset>
                </div>

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
                                        <th>NAMA OPD</th>
                                        <th width="200px">KEGIATAN</th>
                                        <th>PAGU / NILAI KONTRAK</th>
                                        <th>REALISASI KEUANGAN</th>
                                        <th>REALISASI FISIK</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <b>Total Jumlah Kegiatan : <?=format_angka($jml_keg_all)?> Kegiatan</b>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Data Kegiatan Fisik Per SKPD</legend>
                        <div class="preload_rekap1" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                            <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                            <h5>Sedang memuat data...</h5>
                        </div>
                        <div id="load-data-rekap1" style="display: none;"></div>
                        <br>
                    </fieldset>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Data Kegiatan Fisik DAK Per SKPD</legend>
                        <div class="preload_rekap1_dak" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                            <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                            <h5>Sedang memuat data...</h5>
                        </div>
                        <div id="load-data-rekap1-dak" style="display: none;"></div>
                        <br>
                    </fieldset>
                </div>
            </div>

            <br><hr>
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="pb-3 fw-bold">Peta Lokasi</h3>
                    <?php $this->load->view('kegiatan/load_peta'); ?>
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
        load_data(tahun);
        load_data_rekap(tahun);
        load_data_rekap1(tahun);
        load_data_rekap_dak(tahun);
        load_data_rekap1_dak(tahun);
    });
    
    function load_data_rekap(tahun) {
            $('#load-data-rekap').css('display', 'none');
            $('.preload_rekap').show();
            $.ajax({
                url: '<?= site_url('kegiatan/rekap_kegiatan/load_rekap/1'); ?>',
                type: "POST",
                data: {
                    tahun : tahun
                },
                dataType: "html",
                success: function(data) {
                    $('#load-data-rekap').css('display', 'block');
                    $('.preload_rekap').hide();
                    $('#load-data-rekap').html(data);
                }
            });
        }
        
    function load_data_rekap1(tahun) {
            $('#load-data-rekap1').css('display', 'none');
            $('.preload_rekap1').show();
            $.ajax({
                url: '<?= site_url('kegiatan/rekap_kegiatan/load_rekap1/1'); ?>',
                type: "POST",
                data: {
                    tahun : tahun
                },
                dataType: "html",
                success: function(data) {
                    $('#load-data-rekap1').css('display', 'block');
                    $('.preload_rekap1').hide();
                    $('#load-data-rekap1').html(data);
                }
            });
        }
        
    
    function load_data_rekap_dak(tahun) {
            $('#load-data-rekap-dak').css('display', 'none');
            $('.preload_rekap_dak').show();
            $.ajax({
                url: '<?= site_url('kegiatan/rekap_kegiatan/load_rekap/2'); ?>',
                type: "POST",
                data: {
                    tahun : tahun
                },
                dataType: "html",
                success: function(data) {
                    $('#load-data-rekap-dak').css('display', 'block');
                    $('.preload_rekap_dak').hide();
                    $('#load-data-rekap-dak').html(data);
                }
            });
        }
        
    function load_data_rekap1_dak(tahun) {
            $('#load-data-rekap1-dak').css('display', 'none');
            $('.preload_rekap1_dak').show();
            $.ajax({
                url: '<?= site_url('kegiatan/rekap_kegiatan/load_rekap1/2'); ?>',
                type: "POST",
                data: {
                    tahun : tahun
                },
                dataType: "html",
                success: function(data) {
                    $('#load-data-rekap1-dak').css('display', 'block');
                    $('.preload_rekap1_dak').hide();
                    $('#load-data-rekap1-dak').html(data);
                }
            });
        }

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
                $('#btn-tampil').attr('disabled', false);
                $('#btn-tampil').html("<i class='fa fa-search'></i> TAMPILKAN");
            },
            ajax: {
                url: "<?= site_url('kegiatan/kegiatan/load'); ?>",
                type: 'POST',
                data: {
                    tahun: tahun
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
                data: 'nama_skpd'
            }, {
                data: 'jumlah'
            }, {
                data: 'kontrak'
            }, {
                data: 'realisasi'
            }, {
                data: 'persen_fisik'
            }]
        });
    }

$(document).on("click", "#btn-tampil", function(e) {
    e.preventDefault();
    var self = this;
    $(self).attr('disabled', true);
    $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
    var tahun = $('#tahun').val();
    load_data(tahun);
        load_data_rekap(tahun);
        load_data_rekap1(tahun);
        load_data_rekap_dak(tahun);
        load_data_rekap1_dak(tahun);
});
</script>
<?php $this->load->view('_partial/tag_close'); ?>