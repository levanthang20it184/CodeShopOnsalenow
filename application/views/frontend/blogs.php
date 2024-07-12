<?php $this->load->view('frontend/layout/header'); ?>

<section class="ComparePage">
    <section class="theme-padding">
        <div class="product-Listing">
            <div class="container">
                <div class="Breadcrumb row">
                    <ul class="list-unstyled d-flex">
                        <li><a href="<?php echo base_url() ?>">home</a></li>
                        <li><h1 style="margin-bottom: 0; font-size: 13px; font-weight: bold; color: #8a8c8f;"><?php echo $title; ?></h1></li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0">

                        <div class="CompareMain">
                            <div class="row m-0">
                                <div class="col-lg-9">
                                    <div class="headingBox" style="padding:30px 30px;">
                                        <?php foreach ($blogs as $blog) { ?>
                                            <a href="/pages/<?= $blog['slug']; ?>"><?= $blog['title']; ?></a>
                                            <p>Date: <?= $blog['date']; ?></p>
                                            <div style="display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; overflow: hidden; text-overflow: ellipsis; margin-bottom: 50px;">
                                                <?php echo html_entity_decode($blog['description']); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <ul>
                                        <?php foreach($achieves as $blog) { ?>
                                            <li><a href="/pages/<?= $blog['slug']; ?>" target="_blank"><?= $blog['title']; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</section>

<?php $this->load->view('frontend/layout/footer'); ?>