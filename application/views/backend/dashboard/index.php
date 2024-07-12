<?php $this->load->view('backend/layout/header'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><?= trans('dashboard') ?> </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#"><?= trans('home') ?></a></li>
                            <li class="breadcrumb-item active"><?= trans('dashboard') ?></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">


                    <div title="Dispensary Deals" class="clearfix hidden-md-up"></div>

                    <div title="Brands" class="col-12 col-sm-6 col-md-3">
                        <a href="<?= base_url('backend/brand') ?>">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-meetup"
                                                                                     aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Brands</span>
                                    <span class="info-box-number"><?= $all_brands; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>


                    <div title="All Posts" class="col-12 col-sm-6 col-md-3">
                        <a href="<?= base_url('backend/category') ?>">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fa fa-tasks"
                                                                                      aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">All Category</span>
                                    <span class="info-box-number"><?= $all_cataegory; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>


                    <div title="Services" class="col-12 col-sm-6 col-md-3">
                        <a href="<?= base_url('backend/product') ?>">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fa fa-linode"
                                                                                   aria-hidden="true"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">All Product</span>
                                    <span class="info-box-number"><?= $all_products; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>


                </div>


            </div><!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <!-- PAGE PLUGINS -->
    <!-- SparkLine -->
    <script src="<?= base_url() ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jVectorMap -->
    <script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="<?= base_url() ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- ChartJS 1.0.2 -->
    <script src="<?= base_url() ?>assets/plugins/chartjs-old/Chart.min.js"></script>

    <!-- PAGE SCRIPTS -->
    <script src="<?= base_url() ?>assets/dist/js/pages/dashboard2.js"></script>

<?php $this->load->view('backend/layout/footer'); ?>