<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title mb-0">
            FORM TAMBAH
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="form-tambah">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="text" name="tahun" id="tahun" class="form-control" value="<?=$tahun?>" readonly>
                        <small class="text-danger tahun"></small>
                    </div>
                    <div class="form-group">
                        <label for="pendapatan">Anggaran Pendapatan</label>
                        <input type="text" name="pendapatan" id="pendapatan" class="form-control">
                        <small class="text-danger pendapatan"></small>
                    </div>
                    <div class="form-group">
                        <label for="belanja">Anggaran Belanja</label>
                        <input type="text" name="belanja" id="belanja" class="form-control">
                        <small class="text-danger belanja"></small>
                    </div>
                    <div class="form-group">
                        <label for="papbd">Tanggal P APBD</label>
                        <input type="date" name="papbd" id="papbd" class="form-control">
                        <small class="text-danger papbd"></small>
                    </div>
                    <div class="form-group">
                        <label for="pendapatan_p">Anggaran Pendapatan P APBD</label>
                        <input type="text" name="pendapatan_p" id="pendapatan_p" class="form-control">
                        <small class="text-danger pendapatan_p"></small>
                    </div>
                    <div class="form-group">
                        <label for="belanja_p">Anggaran Belanja P APBD</label>
                        <input type="text" name="belanja_p" id="belanja_p" class="form-control">
                        <small class="text-danger belanja_p"></small>
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
    $('.select2').select2();
</script>