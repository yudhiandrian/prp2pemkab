<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            FORM TAMBAH
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="form-add-kontrak">
        <div class="modal-body">
            <div class="form-group">
                <label for="id_kontrak">Nomor Kontrak</label>
                <input type="hidden" name="id_kontrak" id="id_kontrak" value="<?= $kontrak['id_kontrak']; ?>" class="form-control" readonly>
                <input type="text" name="no_kontrak" id="no_kontrak" value="<?= $kontrak['no_kontrak']; ?>" class="form-control" readonly>
                <small class="text-danger id_kontrak"></small>
            </div>
            <div class="form-group">
                <label for="tahun">Tahun</label>
                <input type="text" name="tahun" id="tahun" value="<?= $kontrak['tahun']; ?>" class="form-control" readonly>
                <small class="text-danger tahun"></small>
            </div>
            <div class="form-group">
                <label for="tgl_realisasi">Tanggal Realisasi</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="text" name="tgl_realisasi" id="tgl_realisasi" data-date-format="dd-mm-yyyy" class="form-control" autocomplete="off">
                </div>
                <small class="text-danger tgl_realisasi"></small>
            </div>
            <div class="form-group">
                <label for="nilai">Jumlah Realisasi</label>
                <input type="text" name="nilai" id="nilai" class="form-control" autocomplete="off">
                <small class="text-danger nilai"></small>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control" autocomplete="off">
                <small class="text-danger keterangan"></small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn-add-kontrak" class="btn btn-sm btn-primary">
                <i class="fa fa-save"></i> SIMPAN
            </button>
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                <i class="fa fa-times"></i> BATAL
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#tgl_realisasi').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });
</script>