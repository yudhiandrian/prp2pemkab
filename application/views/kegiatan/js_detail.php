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
                url: "<?= site_url('kegiatan/load_kegiatan'); ?>",
                type: 'POST',
                data: {
                    skpd: "<?= $skpd['id_skpd']; ?>",
                    tahun: "<?= $tahun; ?>"
                }
            },
            columnDefs: [{
                className: 'text-right',
                targets: [4]
            }, {
                className: 'text-center',
                targets: [0, 3, 6]
            }],
            columns: [{
                data: 'no'
            }, {
                data: 'no_kontrak'
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
            }, {
                data: 'aksi'
            }]
        });
    }

    function reload_ajax() {
        table.ajax.reload(null, false);
    }

    $(document).on("click", "#tombol-tambah", function() {
        var self = this;
        $(self).attr('disabled', true);
        $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i>");
        $.ajax({
            url: '<?= site_url('kegiatan/form'); ?>',
            type: "POST",
            data: {
                opsi: "add",
                skpd: $(this).data('id')
            },
            success: function(data) {
                $('#load-form-action').html(data);
                $(self).html('TAMBAH DATA');
                $(self).attr('disabled', false);
            }
        });
    });

    $(document).on("submit", "#form-tambah", function(e) {
        e.preventDefault(e);
        var self = "#btn-tambah";
        var form_id = "#form-tambah";
        $(self).attr('disabled', true);
        $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: "<?= site_url('kegiatan/add'); ?>",
            type: 'POST',
            data: $(form_id).serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    $('#modal-form-action').modal('hide');
                    reload_ajax();
                    if (data.notif) {
                        notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                    } else {
                        notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    }
                } else {
                    notifikasi('error', 'Gagal', data.pesan);
                    $.each(data.errors, function(key, value) {
                        $(form_id + ' [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                        $(form_id + ' [name="' + key + '"]').parents(".form-group").addClass('has-error');
                        $(form_id + ' .' + key).html(value);
                        if (value == "") {
                            $(form_id + ' [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                            $(form_id + ' [name="' + key + '"]').parents(".form-group").addClass('has-success');
                        }
                    });
                }
                $(self).html("<i class='fa fa-save'></i> SIMPAN");
                $(self).attr('disabled', false);
            },
            error: function(xhr, status, msg) {
                alert('Status: ' + status + "\n" + msg);
                $(self).html("<i class='fa fa-save'></i> SIMPAN");
                $(self).attr('disabled', false);
            }
        });
    });


    $(document).on("click", "#tombol-ubah", function() {
        var self = this;
        $(self).attr('disabled', true);
        $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i>");
        $.ajax({
            url: '<?= site_url('kegiatan/form'); ?>',
            type: "POST",
            data: {
                opsi: "edit",
                id: $(this).data('id')
            },
            success: function(data) {
                $('#load-form-action').html(data);
                $(self).html('<i class="fa fa-edit"></i>');
                $(self).attr('disabled', false);
            }
        });
    });

    $(document).on("submit", "#form-ubah", function(e) {
        e.preventDefault(e);
        var self = "#btn-ubah";
        var form_id = "#form-ubah";
        $(self).attr('disabled', true);
        $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: "<?= site_url('kegiatan/edit'); ?>",
            type: 'POST',
            data: $(form_id).serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    $('#modal-form-action').modal('hide');
                    reload_ajax();
                    if (data.notif) {
                        notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                    } else {
                        notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    }
                } else {
                    notifikasi('error', 'Gagal', data.pesan);
                    $.each(data.errors, function(key, value) {
                        $(form_id + ' [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                        $(form_id + ' [name="' + key + '"]').parents(".form-group").addClass('has-error');
                        $(form_id + ' .' + key).html(value);
                        if (value == "") {
                            $(form_id + ' [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                            $(form_id + ' [name="' + key + '"]').parents(".form-group").addClass('has-success');
                        }
                    });
                }
                $(self).html("<i class='fa fa-save'></i> SIMPAN");
                $(self).attr('disabled', false);
            },
            error: function(xhr, status, msg) {
                alert('Status: ' + status + "\n" + msg);
                $(self).html("<i class='fa fa-save'></i> SIMPAN");
                $(self).attr('disabled', false);
            }
        });
    });

    $(document).on("click", "#tombol-hapus", function(e) {
        e.preventDefault();
        swal({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda Yakin Akan Menghapus Data Ini?",
            icon: 'warning',
            buttons: {
                confirm: {
                    text: 'HAPUS DATA',
                    className: 'btn btn-success'
                },
                cancel: {
                    visible: true,
                    text: 'BATAL',
                    className: 'btn btn-danger'
                }
            }
        }).then((Delete) => {
            if (Delete) {
                $.ajax({
                    url: '<?= site_url('kegiatan/delete'); ?>',
                    type: "POST",
                    data: {
                        id: $(this).data('id')
                    },
                    success: function(data) {
                        reload_ajax();
                        if (data.notif) {
                            notifikasi('success', 'Berhasil', 'Data Berhasil Dihapus');
                        } else {
                            notifikasi('error', 'Gagal', 'Data Gagal Dihapus');
                        }
                    }
                });
            }
        })
    });
    

    
    $(document).on("click", "#tombol-upload", function() {
        var self = this;
        $(self).attr('disabled', true);
        $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: '<?= site_url('kegiatan/upload_kegiatan/form_upload'); ?>',
            type: "POST",
            data: {
                skpd: "<?= $skpd['id_skpd']; ?>",
                tahun: "<?=$tahun?>"
            },
            success: function(data) {
                $('#load-form-upload').html(data);
                $(self).html("Upload Excel");
                $(self).attr('disabled', false);
            }
        });
    });

    $(document).on("submit", "#form-upload", function(e) {
        e.preventDefault(e);
        var self = "#btn-upload";
        var form_id = "#form-upload";
        $(self).attr('disabled', true);
        $(self).html("<i class='fa fa-circle-notch fa-spin fa-sm'></i> LOADING...");
        $.ajax({
            url: "<?= site_url('kegiatan/upload_kegiatan/upload'); ?>",
            type: "POST",
            enctype: 'multipart/form-data',
            data: new FormData($(form_id)[0]),
            dataType: "json",
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                if (data.status) {
                    $('#modal-form-upload').modal('hide');
                    reload_ajax();
                    if (data.notif) {
                        notifikasi('success', 'Berhasil', 'Data Berhasil Disimpan');
                    } else {
                        notifikasi('error', 'Gagal', 'Data Gagal Disimpan');
                    }
                } else {
                    notifikasi('error', 'Gagal', data.pesan);
                    $.each(data.errors, function(key, value) {
                        $(form_id + ' [name="' + key + '"]').parents(".form-group").removeClass('has-success');
                        $(form_id + ' [name="' + key + '"]').parents(".form-group").addClass('has-error');
                        $(form_id + ' .' + key).html(value);
                        if (value == "") {
                            $(form_id + ' [name="' + key + '"]').parents(".form-group").removeClass('has-error');
                            $(form_id + ' [name="' + key + '"]').parents(".form-group").addClass('has-success');
                        }
                    });
                }
                $(self).html("<i class='fa fa-save'></i> SIMPAN");
                $(self).attr('disabled', false);
            },
            error: function(xhr, status, msg) {
                alert('Status: ' + status + "\n" + msg);
                $(self).html("<i class='fa fa-save'></i> SIMPAN");
                $(self).attr('disabled', false);
            }
        });
    });
</script>