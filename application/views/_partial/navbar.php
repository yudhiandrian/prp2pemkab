<?php
    $temp_dakab1 = $this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
?>
<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="blue">
        <a href="<?= site_url("dashboard"); ?>" class="logo">
            <img src="<?= base_url('uploads/'.$temp_dakab1['logo']); ?>" alt="Icon" style="width: 25px; margin-right: 5px;">
            <h3 class="navbar-brand text-white" style="font-size: 18px; font-weight: bold;"><?=$temp_dakab1['judul2']?></h3>
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="<?= cek_file("uploads/users/" . $foto); ?>" alt="users" class="avatar-img rounded-circle">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg"><img src="<?= cek_file("uploads/users/" . $foto); ?>" alt="image profile" class="avatar-img rounded"></div>
                                    <div class="u-text">
                                        <h4><?= $username; ?></h4>
                                        <p class="text-muted"><?= $nama_level; ?></p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= site_url('profil-user') ?>">My Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= site_url('logout'); ?>">Logout</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>