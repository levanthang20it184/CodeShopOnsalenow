<?php $this->load->view('frontend/layout/header'); ?>
    <div class="container">
        <div class="Breadcrumb row" style="background: inherit">
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
                                        <a href="<?= base_url($value->slug) ?>">
                                            <div class="Category-ListingBox hoverAnimation">
                                                <div class="Category-image">
                                                    <?php if (!empty($value->image)) { ?>
                                                        <div>
                                                            <img class="div-lazy-loader" data-src="<?php echo $this->config->item('asset_cdn_server'); ?>/uploads/category/<?php echo $value->image; ?>"
                                                                alt="<?php echo $value->image; ?>">
                                                        </div>
                                                    <?php } else { ?>
                                                        <div>
                                                            <img class="div-lazy-loader" alt="default category image" data-src="<?php echo base_url('uploads/category/default.jpg'); ?>">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="Category-content">
                                                    <span style="color: black"><?php echo $value->name ?></h3>
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