<script>
    $(document).ready(function() {
        $('.select2').select2();
        var tahun = $('#tahun').val();
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
                $('#btn-tampil').attr('disabled', false);
                $('#btn-tampil').html("<i class='fa fa-search'></i> TAMPILKAN");
            },
            ajax: {
                url: "<?= site_url('laporan-realisasi-keuangan/load'); ?>",
                type: 'POST',
                data: {
                    tahun: tahun
                }
            },
            columnDefs: [{
                className: 'text-center',
                targets: [0, 2, 3, 4, 5]
            }],
            columns: [{
                data: 'no'
            }, {
                data: 'nama_skpd'
            }, {
                data: 'bulan'
            }, {
                data: 'tanggal'
            }, {
                data: 'user'
            }, {
                data: 'jumlah'
            }, {
                data: 'keterangan'
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
    });

    $(document).on("click", "#tombol-preview", function() {
        var tahun = $("#tahun").val();
        window.open ("<?= site_url('pdf-preview-jumlah-laporan-keuangan/'); ?>"+tahun,"_blank");
    });

    $(document).on("click", "#tombol-cetak", function() {
        var tahun = $("#tahun").val();
        window.open ("<?= site_url('pdf-cetak-jumlah-laporan-keuangan/'); ?>"+tahun,"_blank");
    });
</script>