<?php $this->load->view('frontend/layout/header'); ?>
<section class="ComparePage">
    <section class="theme-padding">
        <div class="product-Listing">
            <div class="container">
                <div class="Breadcrumb row">
                    <ul class="list-unstyled d-flex">
                        <li><a href="<?php echo base_url() ?>"><?php echo $this->uri->segment(1) ?></a></li>
                        <li><h1 style="font-size: 13px; font-weight: bold; margin-bottom: 0; color: #8a8c8f;"><?php echo str_replace("_", " ", $this->uri->segment(2)) ?></h1></li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0">
                        <div class="CompareMain">
                            <div class="CompareDes row m-0 table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <th scope="row"></th>
                                            <?php foreach ($products as $key => $value) {
                                            ?>
                                                <td>
                                                    <a href="<?php echo base_url(); ?><?php echo $value->category_slug; ?>/<?php echo $value->slug; ?>">
                                                        <div class="product-ListingBox compareBox  ">
                                                            <div class="product-image">
                                                                <img class="div-lazy-loader" data-src="<?= showImage($this->config->item('product'), $value->image) ?>" alt="<?= $value->name ?>">
                                                            </div>
                                                            <div class="product-content text-center">
                                                                <div class="product-brand mb-2">
                                                                    <?php
                                                                    if (isset($value->brandimage) && $value->brandimage != 'image' && !empty(trim($value->brandimage)) && $value->is_image == 1) {
                                                                        echo '<img class="div-lazy-loader" data-src="' . $value->brandimage . '" alt="' . $value->alias . '">';
                                                                    } else {
                                                                        echo '<p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-family: revert;font-weight: 700;font-style: italic;margin-bottom: 0">' . $value->alias . '</p>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <h3><?php echo $value->name ?>
                                                                </h3>
                                                                <p <?php echo ($value->selling_price != $value->cost_price) ? "style='color: red'" : ""  ?>><?php echo '€' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price); ?>
                                                                    <?php
                                                                    if ($value->selling_price != $value->cost_price) { ?>
                                                                        <span style="color: black"><del><?php echo '€' . (is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></del></span>
                                                                    <?php } ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                            <?php } ?>

                                        </tr>

                                        <tr>
                                            <th scope="row">Seller Rating:</th>
                                            <?php foreach ($products as $key => $value) { ?>
                                                <td><i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">Description</th>
                                            <?php foreach ($products as $key => $value) { ?>
                                                <input type="hidden" name="des_<?php echo $key; ?>" id="set_<?php echo $key; ?>" value="<?php echo $value->description; ?>">
                                                <td id="des_<?php echo $key; ?>"><?php echo substr($value->description, 0, 150); ?>
                                                    ...<a href="javascript:void(0)" onclick="opendes(<?php echo $key; ?>)"><b>More</b></a>
                                                </td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">Options:</th>
                                            <?php foreach ($products as $key => $value) { ?>
                                                <td><?php echo $value->options ? $value->options : 'N/a' ?></td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">Delivery Rates:</th>
                                            <?php foreach ($products as $key => $value) { ?>
                                                <td style="color:#c00607"><?php echo $value->shipping_cost; ?></td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row">Seller Name:</th>
                                            <?php foreach ($products as $key => $value) { ?>
                                                <td><?php echo $value->merchant_name ?></td>
                                            <?php } ?>
                                        </tr>

                                        <tr>
                                            <th scope="row"></th>
                                            <?php foreach ($products as $key => $value) { ?>
                                                <td>
                                                    <div class="product-content">
                                                        <a href="<?php echo $value->merchant_store_url ?>" class="mainbtn">Visit
                                                            Store!</a>
                                                    </div>
                                                </td>
                                            <?php } ?>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </section>
</section>

<?php $this->load->view('frontend/layout/footer'); ?>
<script type="text/javascript">
    function opendes(key) {
        var set = $("#set_" + key).val();
        $("#des_" + key).html(set);
    }
</script>