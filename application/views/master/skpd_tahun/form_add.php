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
                        <input type="text" name="tahun" id="tahun"  value="<?=$tahun?>" class="form-control" autocomplete="off" readonly>
                        <small class="text-danger tahun"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="nama_skpd">Nama SKPD</label>
                            <select name="nama_skpd" id="nama_skpd" class="form-control select2" style="width: 100%;">
                                <?php 
                                    foreach ($data_skpd as $r) : 
                                        $jumlah = $this->mquery->count_data('data_skpd_tahun', ['tahun' => $tahun, 'id_skpd' => $r['id_skpd']]);
                                        if($jumlah==0){
                                ?>
                                    <option value="<?= $r['id_skpd'] ?>"><?= $r['nama_skpd'] ?></option>
                                <?php } endforeach; ?>
                            </select>
                        <small class="text-danger nama_skpd"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn-tambah" class="btn btn-sm btn-primary">
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