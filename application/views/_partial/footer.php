<?php
    $temp_dakab2 = $this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
?>
<footer class="footer">
    <div class="container-fluid">
        <nav class="pull-left">
                <strong>Copyright &copy; <?=date('Y')?> <?=$temp_dakab2['copyright']?></strong>
        </nav>
        <div class="copyright ml-auto">
            <b>Version 1.3</b>
        </div>
    </div>
</footer>

</div>
</div>

<!--   Core JS Files   -->
<script src="<?= base_url(); ?>assets/js/core/jquery.3.2.1.min.js"></script>
<script src="<?= base_url(); ?>assets/js/core/popper.min.js"></script>
<script src="<?= base_url(); ?>assets/js/core/bootstrap.min.js"></script>
<!-- jQuery UI -->
<script src="<?= base_url(); ?>assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="<?= base_url(); ?>assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<script src="<?= base_url(); ?>assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<!-- jQuery Scrollbar -->
<script src="<?= base_url(); ?>assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?= base_url(); ?>assets/js/plugin/datatables/datatables.min.js"></script>
<script src="<?= base_url(); ?>assets/select2/js/select2.full.min.js"></script>
<script src="<?= base_url(); ?>assets/datepicker/js/bootstrap-datepicker.min.js"></script>
<!-- Atlantis JS -->
<script src="<?= base_url(); ?>assets/js/atlantis.min.js"></script>
<script src="<?= base_url(); ?>assets/js/script.js"></script>
<script src="<?= base_url(); ?>assets/js/plugin/chart.js/chart.min.js"></script>
<script src="<?= base_url(); ?>assets/js/plugin/chart-circle/circles.min.js"></script>