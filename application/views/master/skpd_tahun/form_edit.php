<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            FORM UBAH
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="form-ubah">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="text" name="tahun" id="tahun"  value="<?= $data_skpd_tahun['tahun']; ?>" class="form-control" autocomplete="off" readonly>
                        <small class="text-danger tahun"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="nama_skpd">Nama SKPD Sebelumnya</label>
                        <input type="text" name="nama_skpd" id="nama_skpd" value="<?= $result_skpd['nama_skpd']; ?>" class="form-control" autocomplete="off" readonly>
                        <small class="text-danger nama_skpd"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="nama_skpd">Nama SKPD</label>
                        <input type="hidden" name="id_data" id="id_data" value="<?= $data_skpd_tahun['id_data']; ?>" class="form-control" autocomplete="off">
                        <input type="text" name="nama_skpd" id="nama_skpd" value="<?= $data_skpd_tahun['nama_skpd']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger nama_skpd"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn-ubah" class="btn btn-sm btn-primary">
                <i class="fa fa-save"></i> SIMPAN
            </button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                <i class="fa fa-times"></i> BATAL
            </button>
        </div>
    </form>
</div>

<script>
    $('.select2').select2();
</script>