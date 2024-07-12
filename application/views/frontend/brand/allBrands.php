<?php $this->load->view('frontend/layout/header'); ?>
<div class="container">
    <div class="Breadcrumb row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12  ">
            <ul class="list-unstyled d-flex">
                <li><a href="<?php echo base_url() ?>">Home</a></li>
                <li><span> <?= $title; ?></span></li>
            </ul>
        </div>
    </div>
</div>

<section class="theme-padding">

    <div class="container">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12  ">
                <div class="theme-headingBox  wow fadeInUp">

                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-12">
                            <div class="headingBox">
                                <h3><?= $title; ?></h3>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-12">
                            <div class="headingBoxLink d-flex ">

                            </div>
                        </div>

                    </div>
                </div>
                <div class="product-Listing">

                    <div>
                        <div class="row">

                            <?php foreach ($brands as $key => $value) { ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 wow fadeInUp">
                                    <a href="<?= base_url($value->slug) ?>">
                                        <div class="Category-ListingBox hoverAnimation">
                                            <div class="Category-image">
                                                <?php
                                                if (isset($value->image) && !empty($value->image) && !($value->image == 'image')) {

                                                    $pos = strpos($value->image, "/");
                                                    if ($pos === false) {

                                                        $brand_img_path = $value->image;

                                                    } else {

                                                        $brand_img_path = $value->image;

                                                    }
                                                } else {

                                                    $brand_img_path = "http://test.onsalenow.ie/uploads/brand/default.jpg";
                                                }

                                                //   $brand_img_path = "http://onsalenow.ie/images_db/brand_image/".$image; ?>
                                                <img class="div-lazy-loader" data-src="<?= @$brand_img_path; ?>" alt="<?= ucwords(@$name); ?>">
                                                <!-- </div> -->
                                            </div>
                                            <div class="Category-content">
                                                <h3><?php echo $value->alias ?></h3>
                                                <p>From: €<?php echo(is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>


                        </div>

                        <div class="row justify-content-end m-0">

                            <nav aria-label="Page navigation example">
                                <div id="pagination_link">
                                    <?php echo $this->pagination->create_links(); ?>
                                </div>
                            </nav>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</section>

<?php $this->load->view('frontend/layout/footer'); ?>