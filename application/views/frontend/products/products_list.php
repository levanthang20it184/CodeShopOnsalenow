<?php $this->load->view('frontend/layout/header'); ?>
<div class="container">
    <div class="Breadcrumb row">
        <div class="col-lg-7 col-md-6 col-sm-12 col-12  ">
            <ul class="list-unstyled d-flex">
                <li><a href="<?php echo base_url() ?>">Home</a></li>
                <li>
                    <h1 style="margin-bottom: 0; font-size: 13px; font-weight: bold; color: #8a8c8f;"><?= $title; ?></h1>
                </li>
            </ul>
        </div>
        <div class="col-lg-3 col-md-4 col-7">
            <div class="SortBy" style="margin-top: 30px">
                <select class="wide sort_by" id="view-by-category" onchange="categoryChanged(event);">
                    <option value="">All</option>
                    <?php
                        $categorywithsub = json_decode($categorywithsub, true);
                        foreach ($categorywithsub as $category) { print_r($category); ?>
                            <option value="<?= $category['slug']; ?>" <?= $category==$slug?"selected":""; ?> ><?= $category['categoryName']; ?></option>
                            <?php if ($category['categoryName'] == "Fashion") { ?>
                                <option value="Men" <?= $category==$slug?"selected":""; ?> >Men's Fashion</option>
                                <option value="Women" <?= $category==$slug?"selected":""; ?> >Women's Fashion</option>
                                <option value="Unisex" <?= $category==$slug?"selected":""; ?> >Unisex Fashion</option>
                            <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-12">
            <div class="CompareBtn" style="margin-top: 30px">
                <button type="button" onclick="letsCompare(this)" class="mainbtn w-100"><i class="fa fa-exchange" aria-hidden="true"></i>
                    Compare <span class="compare_count">0</span></button>
            </div>
        </div>
    </div>
</div>

<section class="theme-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="product-Listing">
                    <div>
                        <div class="row">
                            <?php
                            error_reporting(0);
                            if (!empty($products)) :
                                foreach ($products as $key => $value) {
                            ?>
                                    <div class="col wow fadeInUp">

                                        <a href="<?= base_url() ?><?php echo $value->category_slug; ?>/<?php echo $value->slug ?>">
                                            <div class="product-ListingBox hoverAnimation">
                                                <div class="product-Nbr">
                                                    <p> <?= $key + 1 ?></p>
                                                </div>
                                                <?php if ($value->selling_price < $value->cost_price) { ?>
                                                    <div class="product-discount-percent">
                                                        -<?= round(100 - $value->selling_price / $value->cost_price * 100, 0) ?>%
                                                    </div>
                                                <?php } ?>
                                                <div class="product-image">
                                                    <?php if ($value->image != 'image') { ?>
                                                        <img class="div-lazy-loader" alt="<?php echo $value->name; ?>" data-src="<?php echo $value->image ?>">
                                                    <?php } else { ?>
                                                        <img class="div-lazy-loader" alt="product default image" data-src="<?php echo base_url('uploads/product/default.png'); ?>">
                                                    <?php } ?>
                                                </div>
                                                <div class="product-content">
                                                    <div class="product-brand">
                                                        <?php
                                                        if (isset($value->brandimage) && $value->brandimage != 'image' && !empty(trim($value->brandimage)) && $value->is_image == 1) {
                                                            echo '<img class="div-lazy-loader" data-src="' . $value->brandimage . '" alt="' . $value->alias . '">';
                                                        } else {
                                                            echo '<p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-family: revert;font-weight: 700;font-style: italic;margin-bottom: 0">' . $value->alias . '</p>';
                                                        }
                                                        ?>

                                                    </div>

                                                    <h3><?php echo $value->name ?></h3>
                                                    <p <?php echo ($value->selling_price != $value->cost_price) ? "style='color: red'" : ""  ?>><?php echo '€' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price); ?>
                                                        <?php
                                                        if ($value->selling_price != $value->cost_price) { ?>
                                                            <span style="color: black"><del><?php echo '€' . (is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></del></span>
                                                        <?php } ?>
                                                    </p>

                                                    <div class="product_check" style="text-align: center;">
                                                        <input class="compare-style-for-checkbox" <?php if (in_array($value->id, $compare)) {
                                                                                                        echo "checked";
                                                                                                    } ?> type="checkbox" id="compare_<?php echo $value->id ?>" name="compare" value="<?php echo $value->id ?>">+ Compare
                                                    </div>
                                                </div>

                                            </div>
                                        </a>

                                    </div>
                                <?php }
                            else : ?>
                                <h2>No Record Found!</h2>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script>
    function categoryChanged(e) {
        location.href="/products/products_list/"+e.target.value;
    }
</script>

<?php $this->load->view('frontend/layout/footer'); ?>