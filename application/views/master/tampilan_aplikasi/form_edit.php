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
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" value="<?= $row_tampilan['title']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger title"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="link">Website Daerah</label>
                        <input type="text" name="link" id="link" value="<?= $row_tampilan['link']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger link"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="judul1">Judul 1</label>
                        <input type="text" name="judul1" id="judul1" value="<?= $row_tampilan['judul1']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger judul1"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="judul2">Judul 2</label>
                        <input type="text" name="judul2" id="judul2" value="<?= $row_tampilan['judul2']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger judul2"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="sub1">Sub Judul1 </label>
                        <input type="text" name="sub1" id="sub1" value="<?= $row_tampilan['sub1']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger sub1"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="sub2">Sub Judul 2</label>
                        <input type="text" name="sub2" id="sub2" value="<?= $row_tampilan['sub2']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger sub2"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="bagian1">Bagian 1</label>
                        <input type="text" name="bagian1" id="bagian1" value="<?= $row_tampilan['bagian1']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger bagian1"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="bagian2">Bagian 2</label>
                        <input type="text" name="bagian2" id="bagian2" value="<?= $row_tampilan['bagian2']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger bagian2"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="copyright">Copyright </label>
                        <input type="text" name="copyright" id="copyright" value="<?= $row_tampilan['copyright']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger copyright"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="koordinat">Koordinat Pusat : </label>
                        <input type="text" name="koordinat" id="koordinat" value="<?= $row_tampilan['koordinat']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger koordinat"></small>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="zoom">Zoom Peta : </label>
                        <input type="text" name="zoom" id="zoom" value="<?= $row_tampilan['zoom']; ?>" class="form-control" autocomplete="off">
                        <small class="text-danger zoom"></small>
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