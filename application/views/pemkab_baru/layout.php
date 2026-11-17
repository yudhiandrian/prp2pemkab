<?php defined('BASEPATH') OR exit('No direct script access allowed');
$esc=function($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');};
$url=function($path='') {return site_url('pemkab-baru'.($path?'/'.$path:''));};
$token='<input type="hidden" name="pk_csrf" value="'.$esc($csrf).'">';
$rupiah=function($v){return number_format((float)$v,2,',','.');};
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>PRP2 Pemkab · <?= $esc($inst['nama_daerah']) ?></title>
<link rel="stylesheet" href="<?= base_url('assets/css/pemkab-baru.css') ?>"></head><body>
<aside><a class="brand" href="<?= $url() ?>">PRP2<span>PEMKAB / PEMKO</span></a><p class="region"><?= $esc($inst['nama_daerah']) ?></p><nav><a href="<?= $url() ?>">Kegiatan &amp; realisasi</a><a href="<?= $url('opd') ?>">OPD per tahun</a><?php if($actor['role']!=='opd'): ?><a href="<?= $url('finance') ?>">Keuangan daerah</a><?php endif; ?><?php if($actor['role']==='admin'): ?><a href="<?= $url('sync') ?>">Sinkronisasi Sumut</a><?php endif; ?><a href="<?= site_url('dashboard') ?>">Aplikasi sebelumnya</a></nav><div class="account"><?= $esc($actor['nama_user']) ?><small><?= $esc($actor['role']) ?></small></div></aside>
<main><header><div><span class="eyebrow">PENGENDALIAN PEMBANGUNAN</span><h1>PRP2 <?= $esc($inst['nama_daerah']) ?></h1></div><span class="badge">Persetujuan: <?= $inst['approval_mode']==='auto'?'Otomatis':'Admin daerah' ?></span></header>
<?php if($message): ?><div class="notice" role="status"><?= $esc($message) ?></div><?php endif; ?>
<?php $this->load->view('pemkab_baru/'.$view, get_defined_vars()); ?>
<footer>PRP2 Pemkab · Input daerah, pelaporan terintegrasi Sumatera Utara.</footer></main>
<script>document.querySelectorAll('form[data-confirm]').forEach(function(f){f.addEventListener('submit',function(e){if(!confirm(f.dataset.confirm))e.preventDefault();});});</script>
</body></html>
