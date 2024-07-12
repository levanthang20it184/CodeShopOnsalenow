<?php if (count($result) > 0): foreach ($result as $key => $value) {

    // print_r($value); ?>


    <div class="col wow fadeInUp">

        <div class="product-ListingBox hoverAnimation">

            <a href="<?= base_url() ?><?php echo $value->category_slug; ?>/<?php echo $value->slug; ?>">
                <div class="product-image">
                    <?php if ($value->image != 'https://images2.productserve.com/noimage.gif' && $value->image != 'image' && $value->image != '') { ?>
                        <img class="div-lazy-loader" alt="<?php echo $value->alias; ?>" data-src="<?php echo $value->image; ?>">
                    <?php } else { ?>
                        <img class="div-lazy-loader" alt="default product image" data-src="https://test.onsalenow.ie/assets/img/no-image.png">
                    <?php } ?>
                </div>
            </a>

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

                <a href="<?= base_url() ?><?php echo $value->category_slug; ?>/<?php echo $value->slug ?>">
                    <h3><?php echo $value->name ?></h3></a>
                <?php
                echo '€' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price);
                ?>

                <div class="product_check">
                    <input class="compare-style-for-checkbox" <?php if (in_array($value->id, $compare)) {
                        echo "checked";
                    } ?> type="checkbox" id="compare_<?php echo $value->id ?>" name="compare"
                           value="<?php echo $value->id ?>">+ Compare
                </div>
            </div>

        </div>

    </div>

<?php } ?>

    <div class="row justify-content-end m-0">

        <nav aria-label="Page navigation example">
            <div id="pagination_link">
                <?= $pagination; ?>
            </div>
        </nav>

    </div>


<?php elseif (!empty($products)) : ?>
        <h4 style="no-products;width:100%;">No products found, please refer to the top 50 products.</h4>
    <?php foreach ($products as $key => $value) {
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

                    </div>

                </div>
            </a>

        </div>
    <?php }
else : ?>
    <h2>No Record Found!</h2>
<?php endif; ?>
