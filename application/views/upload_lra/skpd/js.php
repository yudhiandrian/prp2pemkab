<script>
    $(document).ready(function() {
        $('.select2').select2();
        var tahun = $('#tahun').val();
        load_data(tahun);
        load_data_rekap(tahun);
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
                url: "<?= site_url('upload-lra-opd/load'); ?>",
                type: 'POST',
                data: {
                    tahun: tahun
                }
            },
            columnDefs: [{
                className: 'text-right',
                targets: [3, 4, 5, 6]
            }, {
                className: 'text-center',
                targets: [0, 2]
            }],
            columns: [{
                data: 'no'
            }, {
                data: 'nama_skpd'
            }, {
                data: 'bulan'
            }, {
                data: 'pendapatan'
            }, {
                data: 'belanja'
            }, {
                data: 'tanggal_data'
            }, {
                data: 'user_input'
            }]
        });
    }
    
    function load_data_rekap(tahun) {
            $('#load-data-rekap').css('display', 'none');
            $('.preload_rekap').show();
            $.ajax({
                url: '<?= site_url('upload-lra-opd/load_rekap'); ?>',
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

    $(document).on("click", "#btn-tampil", function(e) {
        e.preventDefault();
        var self = this;
        $(self).attr('disabled', true);
        $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        var tahun = $('#tahun').val();
        load_data(tahun);
        load_data_rekap(tahun);
    });
    
    $(document).on("click", "#tombol-preview", function() {
        var tahun = $("#tahun").val();
        window.open ("<?= site_url('pdf-preview-data-lra-opd/'); ?>"+tahun,"_blank");
    });

    $(document).on("click", "#tombol-cetak", function() {
        var tahun = $("#tahun").val();
        window.open ("<?= site_url('pdf-cetak-data-lra-opd/'); ?>"+tahun,"_blank");
    });
</script>