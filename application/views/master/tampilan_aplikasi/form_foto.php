<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            EDIT <?=strtoupper($target)?>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="form-upload">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="foto">Upload <?=ucfirst($target)?> (<i style="font-weight: normal;">Format Jpg/Jpeg/Png Maximal 500KB</i>)</label>
                        <br>
                        <img src="<?= cek_file("uploads/" . $row_tampilan[$target]); ?>" style="width: 200px; height: 225px;" alt="Photo" id="load-image">
                        <input type="hidden" name="target" value="<?=$target?>" class="form-control" autocomplete="off">
                        <input type="file" name="foto" id="foto" class=" form-control" accept="image/*" onchange="return file_image('foto', '#load-image', '<?= base_url('uploads/no-image.png'); ?>')">
                        <small class="text-danger foto"></small>
                    </div>
                </div>
            </div>
        
    </div>
    <div class="modal-footer">
        <button type="submit" id="btn-upload" class="btn btn-sm btn-primary">
            <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
            <i class="fa fa-times"></i> BATAL
        </button>
    </div>
    </form>
</div>

<script>
    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>