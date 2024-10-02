<?php $this->load->view("_partial/header"); ?>
<?php
    $tahun_now=date('Y');
?>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">Data Realisasi Anggaran Per Bulan</h3>
            <div class="ml-sm-auto py-md-0">
                <button type="button" id="tombol-preview" class="btn btn-success btn-round btn mr-2 mb-3"> <i class="fa fa-eye"></i> P R E V I E W</button>
                <button type="button" id="tombol-cetak" class="btn btn-warning btn-round btn mr-2 mb-3"> <i class="fa fa-print"></i> C E T A K</button>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">

                <div class="col-sm-12">
                    <div class="d-flex align-items-left flex-column flex-sm-row">
                    
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
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th class="text-center">Pendapatan</th>
                                        <th class="text-center">Belanja</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Jml Data</th>
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
        load_data();
    });

    function load_data() {
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
                url: "<?= site_url('upload-lra-opd/load_detail_rekap'); ?>",
                type: 'POST',
                data: {
                    tahun: "<?= $tahun; ?>",
                    bulan: "<?= $bulan; ?>"
                }
            },
            columnDefs: [{
                className: 'text-right',
                targets: [3, 4]
            }, {
                className: 'text-center',
                targets: [0, 1, 2, 5, 6]
            }],
            columns: [{
                data: 'no'
            }, {
                data: 'tahun'
            }, {
                data: 'bulan'
            }, {
                data: 'pendapatan'
            }, {
                data: 'belanja'
            }, {
                data: 'tanggal_data'
            }, {
                data: 'jml_data'
            }]
        });
    }
    
    $(document).on("click", "#tombol-preview", function() {
        var tahun = <?=$tahun ?>;
        var bulan = <?=$bulan ?>;
        window.open ("<?= site_url('pdf-preview-detail-rekap/'); ?>"+tahun+"/"+bulan,"_blank");
    });

    $(document).on("click", "#tombol-cetak", function() {
        var tahun = <?=$tahun ?>;
        var bulan = <?=$bulan ?>;
        window.open ("<?= site_url('pdf-cetak-detail-rekap/'); ?>"+tahun+"/"+bulan,"_blank");
    });
</script>
<?php $this->load->view('_partial/tag_close'); ?>