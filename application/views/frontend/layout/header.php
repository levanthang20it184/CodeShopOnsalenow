<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NTDJ2NZ');
    </script> -->
    <meta name="google-site-verification" content="tcHpCydYLMndbzznwoA6B8_vEUDofPL1pjvo5C_0r_o" />
    <!-- End Google Tag Manager -->

    <?php //echo "<pre>"; print_r($metadetails);
    ?>
    <meta charset="utf-8" />
    <title><?php echo @$meta_title ? @$meta_title : 'Comparison Shopping Ireland, Discounts, Sales: on sale now - Ireland' ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <!-- <meta content="" name="keywords" />
        <meta content="" name="description" /> -->
    <meta name="description" content="<?php echo @$meta_description ? @$meta_description : 'Online Sales & Discount Comparison Shopping & Branding Filter - Ireland. Compare Products for sale online in Ireland.'; ?>" />
    <meta name="keywords" content="<?php echo @$meta_tag?@$meta_tag:'comparison shopping, online sales, discount online shopping, discount store, for sale, bargain finder, smart bargain, sale, best deals, great deals, hot deals, specials, shop sales, cheap products online, January sales, New Year sales'; ?>" />
    <?php
    $currentUrl = current_url();
    $parsedUrl = parse_url($currentUrl);
    $urlWithoutQuery = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
    ?>
    <link rel="canonical" href="<?php echo $urlWithoutQuery; ?>">
    <!-- Google Fonts -->
    <?php $logo_data = web_logo(); ?>
    <link rel="shortcut icon" href="<?php echo $this->config->item('asset_cdn_server') . $logo_data->favicon_icon ?>" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!-- Bootstrap CSS File -->
    <link href="<?= base_url() ?>assets/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/lib/owl/css/owl.carousel.css" rel="stylesheet" />
    <!-- Main Stylesheet File -->
    <link href="<?= base_url() ?>assets/css/zoom-main.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/css/responsive.css" rel="stylesheet" />
    <!--<link href="<?= base_url() ?>assets/frontend/css/style.css" rel="stylesheet" />-->
    <!--<link href="<?= base_url() ?>assets/frontend/css/responsive.css" rel="stylesheet" />-->
    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-BZG5ZPTFKR"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-BZG5ZPTFKR');
    </script> -->
    <style>
        .childClass {
            display: none;
            padding-left: 10px;
            padding-top: 10px;
        }
    </style>
    <style>
        .All-Categories-box .container {
            padding-left: 0;
        }

        .All-Categories-tabs {
            height: 100%;
            padding: 10px;
        }

        .All-Categories-tabs ul.nav.nav-tabs {
            display: block;
            border: none;
        }

        .All-Categories-tabs ul.nav.nav-tabs li a {
            padding: 10px;
            display: block;
            border-radius: 7px;
            font-size: 14px;
            color: #303030;
            font-weight: 600;
        }

        .All-Categories-tabs ul.nav.nav-tabs li a:hover,
        .All-Categories-tabs ul.nav.nav-tabs a.active {
            background: #c00607;
            color: #fff;
        }

        .All-Categories-box#All-Categories .tab-content {
            padding: 25px 20px;
        }

        .All-Categories-box#All-Categories {
            padding: 0;
        }
    </style>
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top header-transparent header sticky sticky--top js-header">
        <div id="topbar">
            <div class="container">
                <div class="row justify-content-right align-items-right">
                    <div class="col-md-12 col-sm-12">
                        <div class="soical_icon ">
                            <ul class="list-unstyled list-inline">
                                <li><a href="<?= base_url() ?>">Home</a></li>
                                <li><a href="<?php echo base_url('pages/about-us') ?>">About Us</a></li>
                                <li><a href="<?php echo base_url('contact_us') ?>">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container d-flex align-items-center">
            <a aria-label="Logo" class="navbar-brand" href="<?= base_url() ?>"><img src="<?= $this->config->item('asset_cdn_server') .$logo_data->logo ?>" height="100px" alt="logo-image" /></a>
            <div class="search_menuBox ">
                <div class="search_mainBox ">
                    <div class="search_Box">
                        <form class="search-form-box" method="post" action="<?php echo base_url('products/products_list') ?>" onkeydown="return event.key != 'Enter';">
                            <input type="text" name="search" value="<?php echo (isset($searchkey) ? $searchkey : ''); ?>" id="seaarhbar" placeholder="Search Products..." class="search-form-input" onclick="searchButton()">
                            <button class="search-form-button search_icon" type="button" aria-label="search-button">
                                <img src="<?= base_url() ?>assets/images/search.png" alt="search-button-image">
                            </button>
                        </form>
                        <div id="searched_list" style="display: flex; padding:0px;"></div>
                    </div>
                    <div class="allCategory_Box ">
                        <button aria-label="menu-button" onclick="myFunction()" class="allCategory-btn"><img alt="bar-image" src="<?= base_url() ?>assets/images/bar.png">
                            <span>All Categories</span></button>
                    </div>
                </div>
                <?php $menu_data = menu_data(); ?>
                <nav class="main-nav d-none d-lg-flex navbar navbar-expand-lg navbar-light p-0">
                    <ul class="navbar-nav">
                        <?php foreach ($menu_data as $key => $val) {
                            $url = $val->static_link ? $val->static_link : 'pages/' . $val->llink; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url($url) ?>"><?php echo $val->name ?></a>
                            </li>
                        <?php } ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/blogs">Our Sales Blog</a>
                        </li>
                        <?php 
                            $categorywithsub = json_decode($categorywithsub, true);
                            echo "<div class='d-block d-lg-none' style='width: 100%; height: 1px; background: black;'></div>";
                        foreach ($categorywithsub as $index => $category) {
                                if ($category["product_cnt"] == 0)
                                    continue;

                                echo "<li class='nav-item d-block d-lg-none'><a class='nav-link' href='/" . $category["slug"] . "'>" . $category["categoryName"] . "</a></li>";
                        }
                    ?>
                        
                    </ul>
                </nav>
                <!-- .main-nav-->
            </div>
        </div>

        <div class="All-Categories-box" id="All-Categories" style="display: none;">
            <div class="container">
                <div class="row">
                    
                    <div class="col-lg-2 col-md-3 col-sm-5" style="border-right: 1px solid">
                        <div class="All-Categories-tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                foreach ($categorywithsub as $index => $category) {
                                    if ($category["product_cnt"] > 0)
                                        echo "<li><a" . ($index == 0 ? " class='active'" : "") . " data-toggle='tab' href='#" . $category["slug"] . "'>" . $category["categoryName"] . "</a></li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-10 col-md-9 col-sm-7">
                        <div class="tab-content">
                            <?php
                            foreach ($categorywithsub as $index => $category) {
                                if ($category["product_cnt"] == 0)
                                    continue;

                                echo "<div id='" . $category["slug"] . "'" . " class='tab-pane fade in" . ($index == 0 ? " active show" : "") . "'>";
                                echo "<h4><a href='/" . $category["slug"] . "'>" . $category["categoryName"] . "</a></h4>";
                                echo "<button aria-label='menu-button' style='margin-right: 200px; background:#BB0000; color:#EEEEEE' class='btn btn float-right' onclick='myFunction()'>Back</button>";
                                $className = $category["categoryName"] == "Fashion"?"row":"header-category";
                                echo "<div class='$className'>";

                                if ($category["categoryName"] == "Fashion") {
                                    $_subCategories = [];
                                    $_counts = [];
                                    
                                    // Lặp qua danh mục con và nhóm lại theo từ khóa Men, Women, Unisex
                                    foreach ($category["subCategories"] as $subCategory) {
                                        if ($subCategory["product_cnt"] == 0)
                                            continue;
                                        
                                        $start_with = "";
                                        if (strpos($subCategory["name"], "Men") === 0) {
                                            $start_with = "Men";
                                        } elseif (strpos($subCategory["name"], "Women") === 0) {
                                            $start_with = "Women";
                                        } elseif (strpos($subCategory["name"], "Unisex") === 0) {
                                            $start_with = "Unisex";
                                        }
                            
                                        if ($start_with !== "") {
                                            if (!isset($_subCategories[$start_with])) {
                                                $_subCategories[$start_with] = [];
                                                $_counts[$start_with] = 0;
                                            }
                                            $_subCategories[$start_with][] = $subCategory;
                                            $_counts[$start_with] += $subCategory['product_cnt'];
                                        }
                                    }
                            
                                    // Hiển thị danh mục con theo từng nhóm (Men, Women, Unisex)
                                    foreach ($_subCategories as $key => $_categories) {
                                        echo "<div class='col-lg-3 col-md-4 col-sm-6 drop-down-Categories'>";
                                        echo "<ul><li><a href='#' data-toggle='collapse' data-target='#category-$key'>$key (".$_counts[$key].")</a></li></ul>";
                                        echo "<ul id='category-$key' class='collapse'>";
                                        foreach ($_categories as $subCategory) {
                                            echo "<li><a href='" . base_url() . $category["slug"] . "/" . $subCategory["slug"] . "'>" . $subCategory["name"] . " (" . $subCategory["product_cnt"] . ")</a></li>";
                                        }
                                        echo "</ul></div>";
                                    }
                                } else {
                                    // Hiển thị các danh mục con cho các danh mục khác
                                    foreach ($category["subCategories"] as $subCategory) {
                                        if ($subCategory["product_cnt"] == 0)
                                            continue;
                            
                                        echo "<div><div class='drop-down-Categories'><ul><li>";
                                        echo "<a href='" . base_url() . $category["slug"] . "/" . $subCategory["slug"] . "'>" . $subCategory["name"] . " (" . $subCategory["product_cnt"] . ")</a>";
                                        echo "</li></ul></div></div>";
                                    }
                                }

                                echo "</div></div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <script>
        function searchButton() {
            document.getElementById('All-Categories').style.display = 'none';
        }
    </script>