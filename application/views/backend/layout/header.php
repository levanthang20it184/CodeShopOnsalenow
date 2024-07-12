<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= isset($title) ? $title . ' - ' : 'ONSALENOW' ?> <?= general_settings('application_name'); ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="https://www.onsalenow.ie/favicon.ico" type="image/x-icon"/>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/adminlte.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/iCheck/flat/blue.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/morris/morris.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/daterangepicker/daterangepicker-bs3.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <!-- <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"> -->
        <!-- DropZone -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/dropzone/dropzone.css">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <!-- jQuery -->
        <script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>assets/frontend/css/custom.css">

        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datetimepicker/jquery.datetimepicker.css">
        <style>
            li#location,
            li#export,
            li#customer_history,
            li#subscription_history {
                display: none;
            }

            aside.main-sidebar img.brand-image {
                border-radius: 0 !important;
                width: 75%;
                float: none;
            }

            li#general_settings ul li:last-child {
                display: none;
            }

            a.brand-link {
                display: inline-block;
                width: 100%;
            }

            span.brand-text.font-weight-light {
                display: none;
            }

            .navbar-expand .navbar-nav {
                flex-direction: row;
                justify-content: space-between;
                width: 100%;
            }

            .navbar-expand .navbar-nav li.nav-item.d-none.d-sm-inline-block a.nav-link {
                border-radius: 5px;
                background: rgba(240, 103, 34, 1);
                background: -moz-linear-gradient(top, rgba(240, 103, 34, 1) 0%, rgba(255, 177, 82, 1) 100%);
                background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(240, 103, 34, 1)), color-stop(100%, rgba(255, 177, 82, 1)));
                background: -webkit-linear-gradient(top, rgba(240, 103, 34, 1) 0%, rgba(255, 177, 82, 1) 100%);
                background: -o-linear-gradient(top, rgba(240, 103, 34, 1) 0%, rgba(255, 177, 82, 1) 100%);
                background: -ms-linear-gradient(top, rgba(240, 103, 34, 1) 0%, rgba(255, 177, 82, 1) 100%);
                background: linear-gradient(to bottom, rgba(240, 103, 34, 1) 0%, rgba(255, 177, 82, 1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f06722', endColorstr='#ffb152', GradientType=0);
                color: #fff;
            }

            a.update.btn.btn-sm.btn-warning {
                display: flex;
                align-items: center;
                font-size: 16px;
            }
        </style>

    </head>

<body class="hold-transition sidebar-mini <?php if (@$title == 'Login') {
    echo 'bg-cover';
} ?> ">

    <!-- Main Wrapper Start -->
<div class="wrapper">

    <!-- Navbar -->

<?php if (!isset($navbar)) : ?>

    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>

            <li class="nav-item d-none d-sm-inline-block">
                <div class="d-flex">
                    <img style="height: 40px; width: 40px" src="<?= base_url('assets/img/profile.jpg') ?>" class="img-circle elevation-2" alt="User Image">
                    <a href="<?= base_url('backend/auth/logout') ?>" class="ml-3 nav-link"><?= trans('logout') ?> <i
                            class="fa fa-sign-out" aria-hidden="true"></i></a>
                </div>
            </li>
        </ul>


    </nav>

<?php endif; ?>

<?php if (!isset($sidebar)) : ?>

    <?php $this->load->view('backend/layout/sidebar'); ?>

<?php endif; ?>