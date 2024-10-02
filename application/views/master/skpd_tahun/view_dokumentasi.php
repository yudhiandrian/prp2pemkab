<?php $this->load->view("_partial/header"); ?>
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left flex-column flex-sm-row">
            <h3 class="text-white pb-3 fw-bold">DATA Dokumentasi</h3>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card full-height">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div>
                        <div class="table-responsive">
                            <table class="table-default" style="width: 100%;">
                                <thead>
                                    <tr style="background-color: #1572EB; color: white;">
                                        <th width="5px">NO</th>
                                        <th>NAMA SKPD</th>
                                        <th>Kode Urusan</th>
                                        <th>Kode Bidang</th>
                                        <th>Kode Unit</th>
                                        <th>Kode Sub</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        $result = $this->mquery->select_data("kegiatan_dokumentasi", "id_dokumentasi ASC");
                                        foreach ($result as $r) {
                                            $row_kegiatan = $this->mquery->select_id('data_kegiatan_detail', ['id_kegiatan_detail' => $r['id_kegiatan_detail']]);
                                            $row_kontrak = $this->mquery->select_id(' ta_kontrak', ['id_kontrak' => $row_kegiatan['id_kegiatan']]);
                                            $skpd = $this->mquery->select_id('data_skpd', ['kd_urusan' => $row_kontrak['kd_urusan'], 'kd_bidang' => $row_kontrak['kd_bidang'], 'kd_unit' => $row_kontrak['kd_unit'], 'kd_sub' => $row_kontrak['kd_sub']]);
                                    ?>
                                    <tr>
                                        <td><?=$r['tgl_input']?></td>
                                        <td><?=$r['id_kegiatan_detail']?></td>
                                        <td><?=$row_kegiatan['id_kegiatan']?></td>
                                        <td><?=$row_kontrak['keperluan']?></td>
                                        <td><?=$skpd['nama_skpd']?></td>
                                        <td></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('_partial/footer'); ?>
<?php $this->load->view('_partial/tag_close'); ?>