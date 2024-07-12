<?php $this->load->view('frontend/layout/header');
$banner_test = banner_screen('1');
$banner_test1 = banner_screen('2');
$banner_test2 = banner_screen('3');
$banner_test3 = banner_screen('4');
$banner_test4 = banner_screen('5');
// echo "<pre>"; print_r($brands); die;
?>

<style>
    @media (max-width: 767px) {
        .auth-mobile {
            background-image: url("<?= $this->config->item('asset_cdn_server_banner') . $banner_test3->banner_image; ?>") !important;
        }
    }
</style>

<div class="Homebanner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-8">
                <?php if ($banner_test->button_link != '') { ?>
                    <a aria-label="Banner1" href="<?php echo $banner_test->button_link; ?>"><?php } ?>
                    <div class="HomebannerBig" style="background-image: url(<?php echo $this->config->item('asset_cdn_server_banner') . $banner_test->banner_image ?>)">
                        <div class="HomebannerBigCOntent">
                            <span><?php echo $banner_test->title ?></span>
                            <h2><?= $banner_test->banner_heading; ?></h2>
                            <p><?php echo $banner_test->description ?></p>
                            <?php if ($banner_test->button_label != '') { ?><button class="mainbtn"><?php echo $banner_test->button_label ?></button> <?php } ?>
                            <?php // echo $banner_test->button_label
                            ?>
                        </div>
                    </div>
                    <?php if ($banner_test->button_link != '') { ?>
                    </a><?php } ?>

            </div>
            <div class="col-md-12 col-lg-4">
                <div class="row">
                    <div class="col-md-6 col-lg-12 col-12 col-sm-6">
                        <?php if ($banner_test1->button_link != '') { ?>
                            <a aria-label="Banner2" href="<?php echo $banner_test1->button_link ?>"> <?php } ?>
                            <div class="HomebannerSmall" style="background-image: url(<?php echo $this->config->item('asset_cdn_server_banner') . $banner_test1->banner_image ?>)" style="background-position-x: left;">
                                <div class="HomebannerSmallCOntent">
                                    <span><?php echo $banner_test1->title ?></span>
                                    <h2><?= $banner_test1->banner_heading; ?></h2>
                                    <p><?php echo $banner_test1->description ?></p>
                                    <?php if ($banner_test1->button_label != '') { ?> <button class="mainbtn"><?php echo $banner_test1->button_label ?></button><?php } ?>
                                </div>
                            </div>
                            <?php if ($banner_test1->button_link != '') { ?>
                            </a><?php } ?>
                    </div>
                    <div class="col-md-6 col-lg-12 col-12 col-sm-6">
                        <?php if ($banner_test2->button_link != '') { ?>
                            <a aria-label="Banner3" href="<?php echo $banner_test2->button_link ?>"><?php } ?>
                            <div class="HomebannerSmall white-text auth-mobile" style="background-image: url(<?php echo $this->config->item('asset_cdn_server_banner') . $banner_test2->banner_image ?>)">
                                <div class="HomebannerSmallCOntent">
                                    <span><?php echo $banner_test2->title ?></span>
                                    <h2><?= $banner_test2->banner_heading; ?></h2>
                                    <p><?php echo $banner_test2->description ?></p>
                                    <?php if ($banner_test2->button_label != '') { ?> <button class="mainbtn"><?php echo $banner_test2->button_label ?></button><?php } ?>
                                </div>
                            </div>
                            <?php if ($banner_test2->button_link != '') { ?>
                            </a><?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="theme-padding">
    <div class="theme-headingBox  wow fadeInUp">
        <div class="container">
            <div class="row" style="display: flex; align-items: center; margin-bottom: 3px">
                <div class="col-lg-8 col-md-8 col-sm-9 col-7">
                    <div class="headingBox">
                        <h3>Shop by Category</h3>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-3 col-5">
                    <div class="headingBoxLink">
                        <button class="home-red-btn" aria-label="topdeal_product" onclick="window.location.href='<?= base_url('category') ?>'">View all <img class="div-lazy-loader" alt="top50-view-all" data-src="<?= $this->config->item('images') . 'right-arrow.png'; ?>"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Category-Listing">
        <div class="row">
            <?php
            foreach ($topcategory as $key => $value) {
                if ($key > 5)
                    break;
            ?>
                <div class="col-lg-2 col-md-4 col-sm-r col-6 wow fadeInUp">
                    <?php if ($value->id==24) {?>
                        <a href="javascript:void(0);" onclick="selectFashionTab()">
                    <?php }else{ ?>
                        <a href="<?=base_url($value->slug)?>">
                    <?php } ?>
                        <div class="Category-ListingBox hoverAnimation">
                            <div class="Category-image">
                                <?php if ($value->image != '') { ?>
                                    <div>
                                        <img class="div-lazy-loader" alt="category-image" data-src="<?php echo $this->config->item('asset_cdn_server'); ?>/uploads/category/<?php echo $value->image; ?>" alt="<?php echo $value->image; ?>">
                                    </div>

                                <?php } else { ?>
                                    <div>
                                        <img class="div-lazy-loader" alt="default-category-image" data-src="<?php echo base_url('uploads/category/default.jpg'); ?>">
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="Category-content">
                                <span class="category-name"><?php echo $value->name ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
        
    </div>
</section>
<?php if (!empty($brands)) : ?>
    <section class="theme-padding">
        <div class="theme-headingBox wow fadeInUp">
            <div class="container">
                <div class="row" style="display: flex; align-items: center; margin-bottom: 3px">
                    <div class="col-lg-8 col-md-8 col-sm-9 col-7">
                        <div class="headingBox">
                            <h3>Shop by Brands</h3>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-5">
                        <div class="headingBoxLink">
                            <button class="home-red-btn" aria-label="topdeal_product" onclick="window.location.href='<?= base_url('brand') ?>'">View all <img class="div-lazy-loader" alt="top50-view-all" data-src="<?= $this->config->item('images') . 'right-arrow.png'; ?>"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php // echo "<pre>"; print_r($brands); die;
        ?>
        <div class="Category-Listing brands-Listing ">
            <div>
                <div class="row">
                    <?php
                    foreach ($brands as $key => $value) {
                        // if(isset($value->image) && !empty($value->image) && !($value->image == 'image')){
                    ?>
                        <div class="col-lg-2 col-md-4 col-sm-4 col-6 wow fadeInUp">
                            <a href="<?= base_url($value->slug) ?>">
                                <div class="Category-ListingBox hoverAnimation">
                                    <div class="brand-image">

                                        <?php if (isset($value->image) && !empty($value->image) && !($value->image == 'image')) {
                                            $brand_img_path = $value->image;
                                        } else {

                                            $brand_img_path = "http://test.onsalenow.ie/uploads/brand/default.jpg";
                                        }

                                        ?>
                                        <?php
                                        if ($value->is_image == 1) {
                                            echo '<img class="div-lazy-loader" alt="brand-image" style="padding: 10px" data-src="' . $brand_img_path . '" alt="' . $brand_img_path . '">';
                                        } else {
                                            echo '<p style="font-size: 1.5rem;text-align: center;font-family: revert;font-weight: 700;font-style: italic;display: flex; justify-content: center; align-items: center; height: 160px; margin-bottom: 0">' . $value->alias . '</p>';
                                        }
                                        ?>

                                    </div>
                                    <div class="Category-content">
                                        <h3><?php echo $value->alias ?></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </section>
<?php endif;
if (!empty($topdeals)) : ?>
    <section class="theme-padding">
        <div class="theme-headingBox  wow fadeInUp">
            <div class="container">
                <div class="row" style="display: flex; align-items: center; margin-bottom: 3px">
                    <div class="col-lg-8 col-md-8 col-sm-9 col-7">
                        <div class="headingBox">
                            <h3>Top Deals</h3>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-5">
                        <div class="headingBoxLink">
                            <button class="home-red-btn" aria-label="topdeal_product" onclick="window.location.href='<?= base_url('products/products_list') ?>'">View all <img class="div-lazy-loader" alt="top50-view-all" data-src="<?= $this->config->item('images') . 'right-arrow.png'; ?>"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-Listing">
            <div class="container">
                <div class="row">
                    <?php
                    // echo '<pre>@';print_r($topdeals); die;
                    foreach ($topdeals as $key => $value) {
                        //echo '<pre>@';print_r($value);
                    ?>
                        <div class="col-lg-2 col-md-4 col-sm-4 col-6 wow fadeInUp">
                            <a href="<?= base_url() ?><?php echo $value->category_slug ?>/<?php echo $value->slug ?>">
                                <div class="product-ListingBox hoverAnimation">
                                    <?php if ($value->selling_price < $value->cost_price) { ?>
                                        <div class="product-discount-percent">
                                            -<?= round(100 - $value->selling_price / $value->cost_price * 100, 0) ?>%
                                        </div>
                                    <?php } ?>
                                    <div class="product-image">
                                        <img alt="product-image" class="div-lazy-loader" data-src="<?= showImage($this->config->item('product'), $value->image) ?>" alt="<?= $value->image ?>">
                                    </div>
                                    <div class="product-content">
                                        <div class="product-brand">
                                            <?php
                                            if ($value->is_image == 1 && $value->brandimage != '' && $value->brandimage != 'image') {
                                                echo '<img class="div-lazy-loader" alt="brand-image" data-src="' . $value->brandimage . '" alt="' . $value->alias . '">';
                                            } else {
                                                echo '<p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-family: revert;font-weight: 700;font-style: italic;margin-bottom: 0">' . $value->alias . '</p>';
                                            }
                                            ?>
                                        </div>

                                        <h3><?php echo $value->name ?></h3>

                                        <p <?php echo ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) ? 'style="color:red"' : "" ?>>
                                            <?php echo floatval($value->selling_price) > 0 ? '&euro;' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price) : ''; ?>
                                            <?php
                                            if ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) { ?>
                                                <span style='color: black'><del><?php echo '&euro;' . (is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></del></span>
                                            <?php } ?>
                                        </p>

                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </section>
    <section class="about-section">
        <div class="container">
            <div class="row" style="padding: 0 15px">
                <div class="col-lg-6 col-md-12">
                    <div class="Home-about-image hoverAnimation">
                        <img class="div-lazy-loader" alt="bottom-banner" data-src="<?= $this->config->item('asset_cdn_server_banner') . $banner_test4->banner_image; ?>">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 hoverAnimation">
                    <div class="Home-about-box wow fadeInRight">
                        <div class="Home-about-content">
                            <span style="font-size: 34px"><?php echo $banner_test4->title ?></span>
                            <h2 style="font-size: 45px; line-height: 1"><?= $banner_test4->banner_heading; ?> </h2>
                            <p><?php echo $banner_test4->description ?>..</p>
                            <button onclick="window.open('<?php echo $banner_test4->button_link ?>')" class="mainbtn" style="height: auto; font-size: 55px; padding: 16px 45px;"><?php echo $banner_test4->button_label ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($topfifty)) : ?>
        <section class="theme-padding">
            <div class="theme-headingBox  wow fadeInUp">
                <div class="container">
                    <div class="row" style="display: flex; align-items: center">
                        <div class="col-lg-8 col-md-8 col-sm-9 col-7">
                            <div class="headingBox">
                                <h1 style="font-size: 22px; font-weight: 800">Top 50 Products, Top Deals, Products Search Results</h1>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-3 col-5">
                            <div class="headingBoxLink">
                                <button class="home-red-btn" onclick="window.location.href='<?= base_url('products/products_list') ?>'">View all <img class="div-lazy-loader" alt="top50-view-all" data-src="<?= $this->config->item('images') . 'right-arrow.png'; ?>"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-Listing">
                <div class="container">
                    <div class="row">
                        <?php
                        foreach ($topfifty as $key => $value) {
                        ?>
                            <div class="col-lg-2 col-md-4 col-sm-4 col-6 wow fadeInUp">
                                <a href="<?= base_url() ?><?php echo $value->category_slug ?>/<?php echo $value->slug ?>">
                                    <div class="product-ListingBox hoverAnimation">
                                        <?php if ($value->selling_price < $value->cost_price) { ?>
                                            <div class="product-discount-percent">
                                                -<?= round(100 - $value->selling_price / $value->cost_price * 100, 0) ?>%
                                            </div>
                                        <?php } ?>
                                        <div class="product-image">
                                            <img class="div-lazy-loader" alt="product-image" data-src="<?= showImage($this->config->item('product'), $value->image) ?>" alt="<?= $value->image ?>">
                                        </div>
                                        <div class="product-content">
                                            <div class="product-brand">
                                                <?php
                                                if ($value->is_image == 1 && $value->brandimage != '' && $value->brandimage != 'image') {
                                                    echo '<img class="div-lazy-loader" data-src="' . $value->brandimage . '" alt="' . $value->alias . '">';
                                                } else {
                                                    echo '<p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-family: revert;font-weight: 700;font-style: italic;margin-bottom: 0">' . $value->alias . '</p>';
                                                }
                                                ?>
                                            </div>
                                            <div class="product-review">
                                                <ul>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                </ul>
                                            </div>
                                            <h3><?php echo $value->name ?></h3>
                                            <p <?php echo ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) ? 'style="color:red"' : "" ?>>
                                                <?php echo floatval($value->selling_price) > 0 ? '&euro;' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price) : ''; ?>
                                                <?php
                                                if ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) { ?>
                                                    <span style='color: black'><del><?php echo '&euro;' . (is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></del></span>
                                                <?php } ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <script>
        function selectFashionTab() {
            var allCategoriesBox = document.getElementById('All-Categories');
            var fashionTab = document.querySelector("a[data-toggle='tab'][href='#Fashion']");
            var fashionTabContent = document.getElementById('Fashion');

            // Hiển thị hộp All-Categories
            allCategoriesBox.style.display = 'block';

            // Loại bỏ lớp 'active' từ tất cả các tab và nội dung tab
            var allTabs = document.querySelectorAll('.All-Categories-tabs .nav-tabs li a');
            var allTabContents = document.querySelectorAll('.tab-content .tab-pane');

            allTabs.forEach(function(tab) {
                tab.classList.remove('active');
            });

            allTabContents.forEach(function(content) {
                content.classList.remove('active');
                content.classList.remove('show');
            });

            // Thêm lớp 'active' vào tab Thời trang và nội dung của nó
            fashionTab.classList.add('active');
            fashionTabContent.classList.add('active');
            fashionTabContent.classList.add('show');
        }
    </script>   
    <?php $this->load->view('frontend/layout/footer'); ?>