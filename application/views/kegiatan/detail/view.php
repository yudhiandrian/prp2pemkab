<?php 
    $this->load->view("_partial/header"); 
    if($ta_kontrak['tgl_kontrak']<2020){$tamp_tgl="-";}else{$tamp_tgl=substr($ta_kontrak['tgl_kontrak'], 0, 10);}
?>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">DETAIL KEGIATAN FISIK</h3>
            <div class="ml-sm-auto py-md-0">
                <a href="<?= site_url('kegiatan/skpd/' .$ta_kontrak['tahun']."/". encrypt_url($skpd['id_skpd'])); ?>" class="btn btn-primary btn-round btn-sm mr-2 mb-3">KEMBALI KE KEGIATAN</a>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Data Kegiatan Fisik</legend>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table-detail" style="width: 100%; margin-bottom: 20px;">
                                    <tr>
                                        <td width="20%">SKPD</td>
                                        <td width="4px">:</td>
                                        <td><?= $skpd['nama_skpd'] ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Kegiatan</td>
                                        <td>:</td>
                                        <td><?= $ta_kontrak['keperluan']; ?></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Pagu</td>
                                        <td>:</td>
                                        <td><?= 'Rp ' . format_rupiah($ta_kontrak['pagu']) ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Perusahaan</td>
                                        <td>:</td>
                                        <td><?= $ta_kontrak['nm_perusahaan']; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>No. Kontrak</td>
                                        <td>:</td>
                                        <td><?= $ta_kontrak['no_kontrak']; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Kontrak</td>
                                        <td>:</td>
                                        <td><?=$tamp_tgl?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Waktu</td>
                                        <td>:</td>
                                        <td><?= $ta_kontrak['waktu']; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Nilai Kontrak</td>
                                        <td>:</td>
                                        <td><?= 'Rp ' . format_rupiah($ta_kontrak['nilai']) ?></td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Adendum</td>
                                        <td>:</td>
                                        <td>
                                            <?= 'Rp ' . format_rupiah($ta_kontrak['adendum']) ?>
                                            <?php  if(strlen($ta_kontrak['keterangan'])>=3) { ?>
                                                <br>Keterangan : <?=$ta_kontrak['keterangan']?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="text-right">
                                                <?php if ($this->akses['ubah'] == 'Y') { ?>
                                                    <button id="tombol-ubah-adendum" class="btn btn-secondary btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-form-action">UBAH ADENDUM</button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Realisasi Keuangan</td>
                                        <td>:</td>
                                        <td><?= 'Rp ' . format_rupiah($realisasi) ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Persen</td>
                                        <td>:</td>
                                        <td><?= $persen_real ?> %</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Kuasa Pengguna Anggaran (KPA)</td>
                                        <td>:</td>
                                        <td><?= $nama_pa; ?></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Data Realisasi Fisik</legend>
                        <div class="preload" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                            <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                            <h5>Sedang memuat data...</h5>
                        </div>
                        <div id="display-content" style="display: none;">
                        <div class="text-right">
                            <?php if ($this->akses['tambah'] == 'Y') { ?>
                                <button id="tombol-tambah" class="btn btn-secondary btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-form-action">TAMBAH DATA</button>
                            <?php } ?>
                        </div>
                        <div class="table-responsive">
                            <table id="load-content" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th width="5px">NO</th>
                                        <th>TAHAPAN/TANGGAL</th>
                                        <th class="text-center">TARGET FISIK (%)</th>
                                        <th>REALISASI FISIK (%)</th>
                                        <th>DEVIASI</th>
                                        <th>KETERANGAN</th>
                                        <th>OPSI</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </fieldset>

                    <hr>
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Data Realisasi Keuangan</legend>
                        <div class="text-right">
                            <?php if ($this->akses['tambah'] == 'Y') { ?>
                                <button id="tombol-tambah-kontrak" class="btn btn-secondary btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-form-action">TAMBAH DATA</button>
                            <?php } ?>
                        </div>
                        <div class="table-responsive">
                            <table id="load-kontrak" class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th width="5px">NO</th>
                                        <th>TANGGAL REALISASI</th>
                                        <th class="text-center">NILAI</th>
                                        <th>KETERANGAN</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </fieldset>
                    <hr>
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Dokumentasi</legend>
                        <div class="preload_dokumentasi" style="width: 100%; text-align: center; border: 1px solid #00a65a; border-radius: 25px;">
                            <img src="<?= base_url('images/ring_green.gif') ?>" alt="" style="width: 125px;">
                            <h5>Sedang memuat data...</h5>
                        </div>
                        <div id="display-dokumentasi" style="display: none;">
                            <div id="dokumentasi"></div>
                        </div>
                    </fieldset>

                    </div>
                </div>

                <br><hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-right">
                            <?php if ($this->akses['ubah'] == 'Y') { ?>
                                <button id="tombol-ubah-koordinat" class="btn btn-secondary btn-round btn-sm mr-2 mb-3" data-toggle="modal" data-target="#modal-form-action">UBAH KOORDINAT</button>
                            <?php } ?>
                        </div>
                        <h3 class="pb-3 fw-bold">Peta Lokasi</h3>
                        <?php $this->load->view('kegiatan/load_peta_detail'); ?>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-form-action" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div id="load-form-action"></div>
    </div>
</div>

<?php $this->load->view('_partial/footer'); ?>
<?php $this->load->view('kegiatan/detail/js'); ?>
<?php $this->load->view('_partial/tag_close'); ?>