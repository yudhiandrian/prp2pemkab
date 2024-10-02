<div class="row">
    <div class="col-sm-6">
        <h3 class="text-black pb-3 fw-bold"><?=$row_tampilan['title']?></h3>
        <h4><?=$row_tampilan['link']?></h4>
        <h1 class="text-black pb-3 fw-bold"><?=$row_tampilan['judul1']?><br><?=$row_tampilan['judul2']?></h1>
        <h2 class="text-black pb-3 fw-bold"><?=$row_tampilan['sub1']?><br><?=$row_tampilan['sub2']?></h2>
        <h3 class="text-black pb-3 fw-bold"><?=$row_tampilan['bagian1']?><br><?=$row_tampilan['bagian2']?></h3>
        <h3><?=$row_tampilan['copyright']?></h3>
        <h3>Koordinat Pusat : <?=$row_tampilan['koordinat']?></h3>
        <h3>Zoom Peta : <?=$row_tampilan['zoom']?></h3>
    </div>
    <div class="col-sm-6">
        <h3><center>Logo
            <br><img src="<?= cek_file("uploads/" . $row_tampilan['logo']); ?>" alt="" style="height: 50px;">
            <br><button type="button" id="tombol-foto" data-id="logo" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-foto"> <i class="fa fa-edit"> Gambar</i></button>
            <a href="<?= base_url("uploads/" . $row_tampilan['logo']) ?>" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-download"></i></a>
        </center></h3>
        <h3><center>Banner
            <br><img src="<?= cek_file("uploads/" . $row_tampilan['banner']); ?>" alt="" style="height: 50px;">
            <br><button type="button" id="tombol-foto" data-id="banner" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-foto"> <i class="fa fa-edit"> Gambar</i></button>
            <a href="<?= base_url("uploads/" . $row_tampilan['banner']) ?>" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-download"></i></a>
        </center></h3>
        <h3><center>Foto
            <br><img src="<?= cek_file("uploads/" . $row_tampilan['foto']); ?>" alt="" style="height: 100px;">
            <br><button type="button" id="tombol-foto" data-id="foto" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-foto"> <i class="fa fa-edit"> Gambar</i></button>
            <a href="<?= base_url("uploads/" . $row_tampilan['foto']) ?>" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-download"></i></a>
        </center></h3>
    </div>
</div>