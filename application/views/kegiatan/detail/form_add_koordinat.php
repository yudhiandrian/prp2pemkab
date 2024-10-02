<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            FORM KOORDINAT
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="form-ubah-koordinat">
        <div class="modal-body">
            <div class="form-group">
                <label for="koordinat">Koordinat</label>
                <input type="hidden" name="id_kontrak" id="id_kontrak" value="<?= $kontrak['id_kontrak']; ?>" class="form-control" readonly>
                <input type="text" name="koordinat" id="koordinat" value="<?= $kontrak['koordinat']; ?>" class="form-control"  autocomplete="off">
                <small class="text-danger koordinat"></small>
            </div>
            <div class="form-group">
                <label for="lokasi_pekerjaan">Lokasi Kegiatan</label>
                <textarea name="lokasi_pekerjaan" id="lokasi_pekerjaan" class="form-control" rows="3"><?= $kontrak['lokasi_pekerjaan']; ?></textarea>
                <small class="text-danger lokasi_pekerjaan"></small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn-ubah-koordinat" class="btn btn-sm btn-primary">
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