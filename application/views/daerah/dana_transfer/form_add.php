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
                        <label for="id_skpd">Satuan Kerja</label>
                        <select name="id_skpd" id="id_skpd" class="form-control select2" style="width: 100%;">
                            <option value="">Pilih SKPD</option>
                            <?php foreach ($skpd as $s) : ?>
                                <option value="<?= $s['id_skpd']; ?>"><?= $s['nama_skpd']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-danger id_skpd"></small>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="text" name="tahun" id="tahun" class="form-control" value="<?=$tahun?>" readonly>
                        <small class="text-danger tahun"></small>
                    </div>
                    <div class="form-group">
                        <label for="id_uraian">Nama Uraian</label>
                        <select name="id_uraian" id="id_uraian" class="form-control select2" style="width: 100%;">
                            <option value="">Pilih Uraian</option>
                            <?php foreach ($uraian as $kab) : ?>
                                <option value="<?= $kab['id_uraian']; ?>">[<?= $kab['kode_rekening']; ?>] <?= $kab['uraian']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-danger id_uraian"></small>
                    </div>
                    <div class="form-group">
                        <label for="anggaran">Anggaran</label>
                        <input type="text" name="anggaran" id="anggaran" class="form-control" autocomplete="off">
                        <small class="text-danger anggaran"></small>
                    </div>
                    <div class="form-group">
                        <label for="st_anggaran">Status Anggaran</label>
                        <select name="st_anggaran" id="st_anggaran" class="form-control select2" style="width: 100%;">
                            <option value="1">APBD</option>
                            <option value="2">P APBD</option>
                        </select>
                        <small class="text-danger st_anggaran"></small>
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