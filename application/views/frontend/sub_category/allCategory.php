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
                <div class="product-Listing">
                    <div>
                        <div class="row">
                            <?php foreach ($categoryList as $key => $value) { ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 wow fadeInUp">
                                    <a href="<?= base_url('category/' . $value->slug) ?>">
                                        <div class="Category-ListingBox hoverAnimation">
                                            <div class="Category-image">
                                                <?php if (!empty($value->image)) { ?>
                                                    <img class="div-lazy-loader" alt="category image" data-src="<?php echo base_url('uploads/category/'); ?><?php echo $value->image; ?>">
                                                <?php } else { ?>
                                                    <img class="div-lazy-loader" alt="category default image" data-src="<?php echo base_url('assets/img/no-image.png'); ?>">
                                                <?php } ?>
                                            </div>
                                            <div class="Category-content">
                                                <h3><?php echo $value->name ?></h3>
                                                <p>From: €<?php showPrice($value->selling_price, $value->cost_price); ?></p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>


                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</section>

<?php $this->load->view('frontend/layout/footer'); ?>