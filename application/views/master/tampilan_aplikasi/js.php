<script>
    function load_data_rekap(tahun) {
            $('#load-tampilan').css('display', 'none');
            $('.preload_tampilan').show();
            $.ajax({
                url: '<?= site_url('tampilan-aplikasi/load'); ?>',
                type: "POST",
                data: {
                    tahun : 123
                },
                dataType: "html",
                success: function(data) {
                    $('#load-tampilan').css('display', 'block');
                    $('.preload_tampilan').hide();
                    $('#load-tampilan').html(data);
                }
            });
        };
</script>