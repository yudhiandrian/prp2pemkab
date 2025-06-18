<?php
defined('BASEPATH') or exit('No direct script access allowed');
$temp_dakab = $this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?=$temp_dakab['title']?></title>
    <meta name="keyword" content ="prp2, progress, report, pengendalian, pembangunan, kabupaten, kota">
    <meta name="description" content="Progress Report Pengendalian Pembangunan">
    <meta name="author" content="Progress Report Pengendalian Pembangunan" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="all,follow">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('uploads/'.$temp_dakab['logo'])?>">

    <!-- Fonts and icons -->
    <script src="<?= base_url(); ?>assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['<?= base_url(); ?>assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/atlantis.min.css">
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/datepicker/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/default.css">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('images/preloader.png'); ?>">
</head>

<body style="overflow-y:scroll;">
    <div class="wrapper">
        <?php $user = detail_user(); ?>
        <?php $this->load->view("_partial/navbar", $user); ?>
        <?php $this->load->view("_partial/sidebar", $user); ?>
        <div class="main-panel">
            <div class="content">
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>