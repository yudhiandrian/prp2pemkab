<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            FORM ADENDUM
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="form-ubah-adendum">
        <div class="modal-body">
            <div class="form-group">
                <label for="adendum">Adendum</label>
                <input type="hidden" name="id_kontrak" id="id_kontrak" value="<?= $kontrak['id_kontrak']; ?>" class="form-control" readonly>
                <input type="text" name="adendum" id="adendum" value="<?= $kontrak['adendum']; ?>" class="form-control"  autocomplete="off">
                <small class="text-danger adendum"></small>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"><?= $kontrak['keterangan']; ?></textarea>
                <small class="text-danger keterangan"></small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn-ubah-adendum" class="btn btn-sm btn-primary">
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