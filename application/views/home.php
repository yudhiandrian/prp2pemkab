<?php
    $temp = $this->mquery->select_id('tbl_tampilan', ['id_data' => 1]);
    $cek_data = $this->mquery->count_data('tbl_dana_desa_log');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keyword" content ="prp2, progress, report, pengendalian, pembangunan, kabupaten, kota">
    <meta name="description" content="Progress Report Pengendalian Pembangunan">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$temp['title']?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('uploads/'.$temp['logo'])?>">
    
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/animate.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/owl.carousel.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/owl.theme.default.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/magnific-popup.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/font-awesome.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/swiper.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/assets/plugins/dimon-icons/style.css')?>">

    <!-- template styles -->
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/style.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/assets/css/responsive.css')?>">
</head>

<body>
    <div class="preloader">
        <img src="<?= base_url('uploads/'.$temp['logo'])?>" class="preloader__image" alt="">
    </div><!-- /.preloader -->
    <div class="page-wrapper">
        <header class="site-header site-header__header-one ">
            <nav class="navbar navbar-expand-lg navbar-light header-navigation stricky">
                <div class="container clearfix">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="logo-box clearfix">
                        <a class="navbar-brand" href="<?= base_url()?>">
                            <img src="<?= base_url('uploads/'.$temp['banner'])?>" class="main-logo" width="250" alt="Logo" />
                        </a>
                        <button class="menu-toggler" data-target=".main-navigation">
                            <span class="fa fa-bars"></span>
                        </button>
                    </div><!-- /.logo-box -->
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="main-navigation">
                        <ul class="one-page-scroll-menu navigation-box">
                            <li class="scrollToLink">
                                <a href="<?= base_url()?>">Beranda</a>
                            </li>
                            <li class="scrollToLink">
                                <a href="<?=$temp['link']?>">Website</a>
                            </li>
                            <li class="scrollToLink">
                                <a href="<?= base_url('apbd-'.date('Y'))?>">APBD</a>
                            </li>
                            <li class="scrollToLink">
                                <a href="<?= base_url('kegiatan-fisik-'.date('Y'))?>">Kegiatan Fisik</a>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                    <div class="right-side-box">
                        <a class="thm-btn header__cta-btn" href="<?= base_url('login')?>">
                                 <span> Login</span></a>
                    </div><!-- /.right-side-box -->
                    <!-- /.right-side-box -->
                </div>
                <!-- /.container -->
            </nav>
        </header><!-- /.site-header -->
        <section class="banner-one" id="banner">
            <span class="banner-one__shape-1"></span>
            <span class="banner-one__shape-2"></span>
            <span class="banner-one__shape-3"></span>
            <span class="banner-one__shape-4"></span>
            <div class="container">
                <div class="banner-one__moc">
                    <img src="<?= base_url('uploads/'.$temp['foto'])?>" alt="Awesome Image" />
                </div><!-- /.banner-one__moc -->
                <div class="row">
                    <div class="col-xl-6 col-lg-8">
                        <div class="banner-one__content">
                            <h3 class="banner-one__title"><span><?=$temp['judul1']?></span> <br><?=$temp['judul2']?></h3><!-- /.banner-one__title -->
                            <br>
                            <h4><?=$temp['sub1']?> <br><?=$temp['sub2']?></h4><!-- /.banner-one__title -->
                            <p class="banner-one__text"><?=$temp['bagian1']?><br> <?=$temp['bagian2']?></p>
                            <!-- /.banner-one__text -->

                        </div><!-- /.banner-one__content -->
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
                
            </div><!-- /.container -->
        </section><!-- /.banner-one -->

        <section class="service-one" id="features">
            <div class="container">
                <div class="row">
                    <?php if($cek_data!=0){ ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-duration="1500ms">
                            <div class="service-one__single text-center">
                                <div class="service-one__inner">
                                    <i class="service-one__icon fa fa-line-chart"></i>
                                    <p><?=$temp['sub1']?> <br><?=$temp['sub2']?></p>
                                    <a href="<?= base_url('apbd-'.date('Y'))?>" class="service-one__link"> APBD <i class="dimon-icon-right-arrow"></i></a>
                                </div>
                            </div>
                        </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->
                        <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInDown" data-wow-duration="1500ms">
                            <div class="service-one__single text-center">
                                <div class="service-one__inner">
                                    <i class="service-one__icon  fa fa-line-chart"></i>
                                    <p><?=$temp['sub1']?> <br><?=$temp['sub2']?></p>
                                    <a href="<?= base_url('kegiatan-fisik-'.date('Y'))?>" class="service-one__link"> Kegiatan Fisik <i class="dimon-icon-right-arrow"></i></a>
                                </div><!-- /.service-one__inner -->
                            </div><!-- /.service-one__single -->
                        </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->
                        <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInDown" data-wow-duration="1500ms">
                            <div class="service-one__single text-center">
                                <div class="service-one__inner">
                                    <i class="service-one__icon  fa fa-line-chart"></i>
                                    <p><?=$temp['sub1']?> <br><?=$temp['sub2']?></p>
                                    <a href="<?= base_url('dana-desa-'.date('Y'))?>" class="service-one__link"> Dana Desa <i class="dimon-icon-right-arrow"></i></a>
                                </div><!-- /.service-one__inner -->
                            </div><!-- /.service-one__single -->
                        </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->
                    <?php } else { ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-duration="1500ms">
                            <div class="service-one__single text-center">
                                <div class="service-one__inner">
                                    <i class="service-one__icon fa fa-line-chart"></i>
                                    <p><?=$temp['sub1']?> <br><?=$temp['sub2']?></p>
                                    <a href="<?= base_url('apbd-'.date('Y'))?>" class="service-one__link"> APBD <i class="dimon-icon-right-arrow"></i></a>
                                </div>
                            </div>
                        </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->
                        <div class="col-lg-6 col-md-6 col-sm-12 wow fadeInDown" data-wow-duration="1500ms">
                            <div class="service-one__single text-center">
                                <div class="service-one__inner">
                                    <i class="service-one__icon  fa fa-line-chart"></i>
                                    <p><?=$temp['sub1']?> <br><?=$temp['sub2']?></p>
                                    <a href="<?= base_url('kegiatan-fisik-'.date('Y'))?>" class="service-one__link"> Kegiatan Fisik <i class="dimon-icon-right-arrow"></i></a>
                                </div><!-- /.service-one__inner -->
                            </div><!-- /.service-one__single -->
                        </div><!-- /.col-lg-3 col-md-6 col-sm-12 -->
                    <?php } ?>
                </div>
                <!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.service-one -->
        <footer class="site-footer">
            <!-- /.site-footer__upper -->
            <div class="site-footer__bottom">
                <div class="container">
                    <div class="inner-container text-center">
                        <p class="site-footer__copy">&copy; copyright <?=date('Y')?> By <a href="#"><?=$temp['copyright']?></a></p>
                        <!-- /.site-footer__copy -->
                    </div><!-- /.inner-container -->
                </div><!-- /.container -->
            </div><!-- /.site-footer__bottom -->
        </footer><!-- /.site-footer -->
    </div><!-- /.page-wrapper -->


    <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>


    <script src="<?= base_url('assets/assets/js/jquery.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/bootstrap.bundle.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/owl.carousel.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/waypoints.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/jquery.counterup.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/TweenMax.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/wow.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/jquery.magnific-popup.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/jquery.ajaxchimp.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/swiper.min.js')?>"></script>
    <script src="<?= base_url('assets/assets/js/jquery.easing.min.js')?>"></script>

    <!-- template scripts -->
    <script src="<?= base_url('assets/assets/js/theme.js')?>"></script>
</body>

</html>