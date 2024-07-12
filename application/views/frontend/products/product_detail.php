<!-- <?php echo $this->uri->segment(1); ?> -->


<?php $this->load->view('frontend/layout/header'); ?>
<style type="text/css">
    span.CompareBtn.text-right a i {
        color: #1980ff;
    }

    .disabled {
        pointer-events: none;
        cursor: not-allowed;
    }
</style>
<style>
    .addReadMore.showlesscontent .SecSec,
    .addReadMore.showlesscontent .readLess {
        display: none;
    }

    .addReadMore.showmorecontent .readMore {
        display: none;
    }

    .addReadMore .readMore,
    .addReadMore .readLess {
        font-weight: bold;
        margin-left: 2px;
        color: #c00607;
        cursor: pointer;
    }

    .addReadMoreWrapTxt.showmorecontent .SecSec,
    .addReadMoreWrapTxt.showmorecontent .readLess {
        display: block;
    }
    .promotion-text {
        color: red;
        margin-left: 30px;
    }
</style>

<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">Price history</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> -->
            <div class="modal-body">
                <ul class="nav nav-pills mb-3" id="history-pills-tab" role="tablist" style="justify-content: end;">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active modal-link" onclick="setTimeout(drawModalHistoryChart(3), 500)" id="modal-3m-tab" data-toggle="pill" data-target="#modal-chart-3m" type="button" role="tab" aria-controls="modal-chart-3m" aria-selected="true">3 M</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link modal-link" id="modal-6m-tab" onclick="setTimeout(function(){drawModalHistoryChart(6);}, 500)" data-toggle="pill" data-target="#modal-chart-6m" type="button" role="tab" aria-controls="modal-chart-6m" aria-selected="false">6 M</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link modal-link" id="modal-1y-tab" onclick="setTimeout(function(){drawModalHistoryChart(12);}, 500)" data-toggle="pill" data-target="#modal-chart-1y" type="button" role="tab" aria-controls="modal-chart-1y" aria-selected="false">1 Y</a>
                    </li>
                </ul>
                <div class="tab-content" id="history-pills-tabContent" style="height: 300px">
                    <div class="tab-pane fade show active" id="modal-chart-3m" role="tabpanel" aria-labelledby="modal-3m-tab" style="width: 100%; height: 100%; z-index: 0"></div>
                    <div class="tab-pane fade" id="modal-chart-6m" role="tabpanel" aria-labelledby="modal-6m-tab" style="width: 100%; height: 100%; z-index: 0"></div>
                    <div class="tab-pane fade" id="modal-chart-1y" role="tabpanel" aria-labelledby="modal-1y-tab" style="width: 100%; height: 100%; z-index: 0"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<section class="DetailPage">
    <section class="theme-padding">
        <div class="product-Listing">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="Breadcrumb">
                            <ul class="list-unstyled d-flex">
                                <li><a href="<?php echo base_url() ?>">Home</a></li>
                                <?php
                                $slug_name = $this->uri->segment(1);
                                $url = explode('/', $slug_name);
                                $articleurl = array_pop($url);
                                ?>
                                <li><a href="<?= base_url() ?><?php echo $articleurl; ?>"><?php echo $articleurl; ?></a></li>
                                <li>
                                    <h1 style="font-size: 13px; font-weight: bold; margin-bottom: 0; color: #8a8c8f;white-space:nowrap"><?php echo @$productdetail->name; ?></h1>
                                </li>
                            </ul>
                            <div class="col-lg-3 col-md-3 col-12">
                                <div class="row">
                                    <div class="CompareBtn">
                                        <button type="button" onclick="letsCompare(this)" class="mainbtn w-100"><i class="fa fa-exchange" aria-hidden="true"></i> Compare <span class="compare_count">0</span></button>
                                    </div>
                                    <div class="product_check">
                                        <input class="compare-style-for-checkbox" <?php if (in_array($productdetail->id, $compare)) {
                                                                                        echo "checked";
                                                                                    } ?> type="checkbox" id="compare_<?php echo $productdetail->id; ?>" name="compare" value="<?php echo $productdetail->id; ?>">+ Compare
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="ProdPAgeSlider">
                            <?php if($productdetail->selling_price < $productdetail->cost_price) { ?>
                                <div class="product-discount-percent" style="right: 75px !important; left: unset; z-index: 9;">
                                    -<?= round(100 - $productdetail->selling_price / $productdetail->cost_price * 100, 0) ?>%
                                </div>
                            <?php } ?>
                            <div class="ShareBadges">
                                <!-- <a href=""><div class="DownPrice"></div></a> -->
                                <div class="ShareBadge"></div>
                                <div class="footer-newsletter-soical">
                                    <!-- <h4>Social Media :</h4> -->
                                    <div class="footer_social">
                                        <ul>
                                            <li class="facebook">
                                                <a href="http://www.facebook.com/sharer.php?u=<?php echo base_url(); ?><?php echo $productdetail->categoryslug; ?>/<?php echo $productdetail->slug; ?>/" target="_blank"><i class="fa fa-facebook"> </i></a>

                                            </li>
                                            <li class="twitter">
                                                <a href="http://twitter.com/share?url=<?php echo base_url(); ?><?php echo $productdetail->categoryslug; ?>/<?php echo $productdetail->slug; ?>" target="_blank"><i class="fa fa-twitter"> </i></a>
                                            </li>
                                            <li class="youtube">
                                                <a href="http://pinterest.com/pin/create/button/?url=<?php echo base_url(); ?><?php echo $productdetail->categoryslug; ?>/<?php echo $productdetail->slug; ?>" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
                                            </li>
                                            <li class="google-plus">
                                                <a href="https://www.instagram.com/" target="_blank"><i class="fa fa-instagram"> </i></a>
                                            </li>
                                            <!-- <li class="whatsapp">
                                            <a href="" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                                        </li> -->
                                            <li class="whatsapp">
                                                <a href="https://web.whatsapp.com" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <?php if ($productdetail->image != 'https://images2.productserve.com/noimage.gif') { ?>
                                        <div class="zoom-show" href="<?= showImage($this->config->item('product'), $productdetail->image) ?>">

                                            <img class="div-lazy-loader" alt="<?php echo $productdetail->name; ?>" data-src="<?= showImage($this->config->item('product'), $productdetail->image) ?>" id="show-img">
                                        </div>
                                    <?php } else { ?>
                                        <img class="div-lazy-loader" alt="default product image" data-src="<?php echo base_url('uploads/product/default.png'); ?>" id="show-img">
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php // echo "<pre>"; print_r($productdetail); die;

                    //echo "<pre>"; print_r($price_comparison); die;
                    ?>

                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="DetailPageContent">
                            <div class="row">
                                <div class="col-6">
                                    <?php if ($productdetail->stock > 0) { ?>
                                        <div class="Instock">
                                            <span>In Stock</span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="Instock outstock">
                                            <span>Out of Stock</span>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!-- <div class="col-6 text-right">
                            <a class="ShareBadge" href=""></a>
                        </div> -->
                            </div>
                            <div class="DetailHeadDes">
                                <h2><?php echo $productdetail->name; ?></h2>
                                <p class="addReadMore showlesscontent"><?php echo $productdetail->description ?></p>
                            </div>
                            <!-- <div class="Rating">
                            <p>Rating: 
                            <span><i class="fa fa-star" aria-hidden="true"></i></span>
                            <span><i class="fa fa-star" aria-hidden="true"></i></span>
                            <span><i class="fa fa-star" aria-hidden="true"></i></span>
                            <span><i class="fa fa-star" aria-hidden="true"></i></span>
                            <span><i class="fa fa-star" aria-hidden="true"></i></span>
                            <b>6 From 100 (Trend:+1)</b>
                            </p>
                        </div> -->
                            <div class="d-flex priceBrand">
                                <div class="DPrice w-50">
                                    <p class="m-0">
                                        <span class="SellingPrice"><?php echo floatval($productdetail->selling_price) > 0 ? '&euro;' . (is_numeric($productdetail->selling_price) && floor($productdetail->selling_price) == $productdetail->selling_price ? intval($productdetail->selling_price) : number_format($productdetail->selling_price, 2, '.', '')) : ''; ?></span>
                                        <?php if ($productdetail->selling_price != $productdetail->cost_price && floatval($productdetail->cost_price) > 0) { ?>
                                            <span class="MRP"><del><?php echo '&euro;' . number_format(is_numeric($productdetail->cost_price) && floor($productdetail->cost_price) == $productdetail->cost_price ? intval($productdetail->cost_price) : $productdetail->cost_price, 2, '.', ''); ?></del></span>
                                        <?php } ?>
                                    </p>
                                    <p>
                                        <?php if ($priceDrop != "") { ?>
                                            <span class="text-danger"><i>Price Dropped On <?= date('d/m/Y', strtotime($priceDrop)); ?></i></span>
                                        <?php } ?>
                                    </p>
                                </div>
                                <div class="Ambesdor w-50 text-right">
                                </div>
                            </div>
                            <div class="DesMapMain d-flex">
                                <div class="DespMapinner w-70">
                                    <div class="DespD">
                                        <h4>Seller Information</h4>
                                        <?php if (!empty($merchant_data)) { ?>
                                            <p>
                                                <?php if ($merchant_data->image != '' && $merchant_data->image != 'image') { ?>
                                            <div class="merchant-logo">
                                                <img class="div-lazy-loader" alt="merchant logo" data-src="<?php echo base_url('/uploads/merchant/'); ?><?php echo $merchant_data->image; ?>" height="50px">
                                            </div>
                                        <?php } ?>
                                        <?php if ($merchant_data->eu_icon_status == 1) { ?>
                                            <img class="div-lazy-loader" alt="eu mark" data-src="<?= $this->config->item('images') . 'eu.png'; ?>" height="50px">
                                        <?php } ?>
                                        </p>
                                        <div class="d-flex w-100">
                                            <div class="VisitStore">
                                                <a href="<?= $merchant_data->merchant_store_url; ?>" class="mainbtn" target="_blank" rel="nofollow">Visit Store!</a>
                                            </div>
                                            
                                            <?php if ($products['specific_promotion'] && $products['specific_promotion'] != "") { ?>
                                                <div class="promotion-text">
                                                    <p class="text-warning">Specific Promotion!</p>
                                                    <p class="text-danger"><?= $products['specific_promotion']; ?></p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <p>Not Available</p>
                                        <div class="VisitStore">
                                            <a style="pointer-events: none; cursor: default;" href="javascript:void(0);" class="mainbtn" target="_blank">Not
                                                Available!</a>
                                        </div>
                                    <?php } ?>

                                    </div>
                                </div>
                                <div class="SellerMap w-30">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="FullDespSingle row">
                            <div class="col-lg-6 col-12 p-0">
                                <div class="TabBtn" style="margin: 0 15px 10px 15px">
                                    <h3>Product Information</h3>
                                </div>
                                <ul class="list-unstyled">
                                    <li><span><b>Category:</b></span><span><a href="<?php echo base_url() . $productdetail->categoryslug ?>"><?php echo $productdetail->categoryname ?></a></span>
                                    </li>
                                    <li><span><b>Sub-category:</b></span><span><a href="<?php echo base_url() . $productdetail->categoryslug . '/' . $productdetail->subcategoryslug ?>"><?php echo $productdetail->subcategorynam ?></a></span>
                                    </li>
                                    <li><span><b>Brand:</b></span><span><a href="<?php echo base_url() . $productdetail->brandslug ?>"><?php echo $productdetail->alias ?></a></span>
                                    </li>
                                    <li>
                                        <span><b> Price :</b></span><span><?php echo floatval($productdetail->selling_price) > 0 ? '&euro;' . (is_numeric($productdetail->selling_price) && floor($productdetail->selling_price) == $productdetail->selling_price ? intval($productdetail->selling_price) : number_format($productdetail->selling_price, 2)) : ''; ?></span>
                                    </li>
                                  
                                    <?php if(isset($productdetail->size) && !empty($productdetail->size)) { ?>
                                        <li>
                                            <span><b>Size :</b></span><span><?= trim($productdetail->size, "|"); ?></span>
                                        </li>
                                    <?php } ?>
                                    <?php if(isset($productdetail->color) && !empty($productdetail->color)) { ?>
                                        <li>
                                            <span><b>Colour :</b></span><span><?= trim($productdetail->color, "|"); ?></span>
                                        </li>
                                    <?php } ?>
                                    <?php if(isset($productdetail->option) && !empty($productdetail->option)) { ?>
                                        <li>
                                            <span><b>Option :</b></span><span><?= trim($productdetail->option, "|"); ?></span>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <span><b>Delivery Time :</b></span><span><?= $productdetail->shipping_days; ?></span>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-lg-6 col-12 p-0">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="justify-content: end;">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" onclick="draw3mChart()" id="pills-3m-tab" data-toggle="pill" data-target="#chart-3m" type="button" role="tab" aria-controls="chart-3m" aria-selected="true">3 M</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-6m-tab" onclick="setTimeout(function(){draw6mChart();}, 500)" data-toggle="pill" data-target="#chart-6m" type="button" role="tab" aria-controls="chart-6m" aria-selected="false">6 M</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-1y-tab" onclick="setTimeout(function(){draw1yChart();}, 500)" data-toggle="pill" data-target="#chart-1y" type="button" role="tab" aria-controls="chart-1y" aria-selected="false">1 Y</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent" style="height: 300px">
                                    <div class="tab-pane fade show active" id="chart-3m" role="tabpanel" aria-labelledby="pills-3m-tab" style="width: 100%; height: 100%; z-index: 0"></div>
                                    <div class="tab-pane fade" id="chart-6m" role="tabpanel" aria-labelledby="pills-6m-tab" style="width: 100%; height: 100%; z-index: 0"></div>
                                    <div class="tab-pane fade" id="chart-1y" role="tabpanel" aria-labelledby="pills-1y-tab" style="width: 100%; height: 100%; z-index: 0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?php if (count($price_comparison) > 1) : ?>
                    <div class="theme-headingBox" style="visibility: visible; animation-name: fadeInUp;">
                        <div class="row">
                            <div class="col-12">
                                <div class="headingBox">
                                    <h3>Compare other "<?php echo $products['name']; ?>"</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div>
                                <div class="DetailTablessInfo">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Price</th>
                                                <th scope="col">Shipping Cost</th>
                                                <th scope="col">Options</th>
                                                <th scope="col">Delivery Time</th>
                                                <th scope="col">In Stock</th>
                                                <th scope="col">Seller</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($price_comparison as $key => $value) {
                                                $product_option = implode(" | ", array_filter([$value->size, $value->color, $value->option]));
                                            ?>
                                                <tr>
                                                    <td>
                                                        <b style="color:#c00607;">
                                                            <?php echo floatval($value->selling_price) > 0 ? '&euro;' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? number_format($value->selling_price, 2, '.', '') : number_format((float) $value->selling_price, 2)) : ''; ?>
                                                            &nbsp;&nbsp;&nbsp;<i class="fa fa-line-chart" onclick="initModalChart('<?= $value->mproduct_id ?>')" data-toggle="modal" data-target="#historyModal" style="cursor: pointer"></i>
                                                        </b>
                                                    </td>
                                                    <td><?= $value->shipping_cost ? $value->shipping_cost : 0; ?></td>
                                                    <td><?= $product_option ? $product_option : 'N/a' ?></td>
                                                    <td><?= $value->shipping_days; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($value->stock > 0) {
                                                        ?>
                                                            <span class="instock"></span>
                                                        <?php } else { ?>
                                                            <span class="Outofstock"></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td><b style="color:#c00607;"><?php echo $value->merchant_name; ?></b>
                                                    </td>
                                                    <td><a href="<?php echo $value->merchant_store_url ?>" rel="nofollow" target="_blank" class="mainbtn">Shop Now!</a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($related_product)) : ?>
                    <div class="theme-headingBox" style="visibility: visible; animation-name: fadeInUp;">
                        <div class="row">
                            <div class="col-12">
                                <div class="headingBox">
                                    <h3>Related Products</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="row RelatedDeals">
                                <?php foreach ($related_product as $key => $value) { ?>
                                    <div class="col wow fadeInUp">
                                        <a href="<?= base_url() ?><?php echo $value->category_slug; ?>/<?php echo $value->slug ?>">
                                            <div class="product-ListingBox hoverAnimation">
                                                <div class="product-image" style="display: flex">
                                                    <img alt="<?php echo $value->name; ?>" data-src="<?= showImage($this->config->item('product'), $value->image) ?>" class="div-lazy-loader" style="margin: auto; width: 60%; object-fit: contain">
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
                                                    <div class="product-review">
                                                        <ul>
                                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                        </ul>
                                                    </div>
                                                    <h3><?php echo $value->name; ?></h3>
                                                    <?php
                                                    echo floatval($value->selling_price) > 0 ? '&euro;' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price) : '';
                                                    ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
    </section>
</section>

<?php $this->load->view('frontend/layout/footer'); ?>
<!-- <script type="text/javascript" src="https://fastly.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script> -->
<script type="text/javascript" src="/assets/js/echarts.js"></script>

<script>
    function AddReadMore() {
        //This limit you can set after how much characters you want to show Read More.
        var carLmt = 280;
        // Text to show when text is collapsed
        var readMoreTxt = " ... Read More";
        // Text to show when text is expanded
        var readLessTxt = " Read Less";


        //Traverse all selectors with this class and manupulate HTML part to show Read More
        $(".addReadMore").each(function() {
            if ($(this).find(".firstSec").length)
                return;

            var allstr = $(this).text();
            if (allstr.length > carLmt) {
                var firstSet = allstr.substring(0, carLmt);
                var secdHalf = allstr.substring(carLmt, allstr.length);
                var strtoadd = firstSet + "<span class='SecSec'>" + secdHalf + "</span><span class='readMore'  title='Click to Show More'>" + readMoreTxt + "</span><span class='readLess' title='Click to Show Less'>" + readLessTxt + "</span>";
                $(this).html(strtoadd);
            }

        });
        //Read More and Read Less Click Event binding
        $(document).on("click", ".readMore,.readLess", function() {
            $(this).closest(".addReadMore").toggleClass("showlesscontent showmorecontent");
        });
    }

    $(function() {
        //Calling function after Page Load
        AddReadMore();
    });
</script>

<script type="text/javascript">
    function abd(abc) {
        window.location.href = (abc);
    }
</script>

<script type="text/javascript">
    const ydata = JSON.parse('<?php echo (json_encode($sellingPriceArray)); ?>');
    let before = 0;
    for (let i = 0; i < ydata.length; i++) {
        if (!ydata[i])
            ydata[i] = before;
        else before = ydata[i];
    }

    function adjustPriceArray(priceArray) {
        var lastIndex = priceArray.length - 1;
        var firstNonEmptyValue = "";

        for (var i = lastIndex; i >= 0; i--) {
        if (priceArray[i] !== "") {
            firstNonEmptyValue = priceArray[i];
            break;
        }
        }

        for (var j = 0; j < priceArray.length; j++) {
            if (priceArray[j] === "") {
                priceArray[j] = firstNonEmptyValue;
            }
        }

        return priceArray;
    }

    var modalPriceArray = [];

    function initModalChart(id) {
        $('#historyModal').modal('show');

        setTimeout(function() {
            setModalPriceArray(id);

        }, 500);
    }

    function setModalPriceArray(id) {
        $.ajax({
            url: '<?= base_url('backend/Cron_Config/getPriceHistoryArray') ?>',
            method: 'POST',
            data: { // add any desired parameters here
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: id
            },
            success: function(data) {
                modalPriceArray = JSON.parse(data);

                const activeLink = $('.modal-link.active').text();

                if (activeLink == '3 M') {
                    drawModalHistoryChart(3);
                } else if (activeLink == '6 M') {
                    drawModalHistoryChart(6);
                } else {
                    drawModalHistoryChart(12);
                }
            }
        });
    }

    function drawModalHistoryChart(monthCnt) {
        var chart3m;
        const data = JSON.parse('<?php echo (json_encode($historyDateArray)); ?>').slice(366 - differenceInDays);
        console.log(data);

        if (monthCnt == 3) {
            chart3m = document.getElementById('modal-chart-3m');
        } else if (monthCnt == 6) {
            chart3m = document.getElementById('modal-chart-6m');
        } else {
            chart3m = document.getElementById('modal-chart-1y');
        }
        var myChart3m = echarts.init(chart3m, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var app = {};

        var option;

        // Get today's date
        var today = new Date();

        // Subtract 3 months from today
        var threeMonthsAgo = new Date();
        threeMonthsAgo.setMonth(threeMonthsAgo.getMonth() - monthCnt);

        // Calculate the difference in days
        var differenceInTime = today.getTime() - threeMonthsAgo.getTime();
        var differenceInDays = Math.floor(differenceInTime / (1000 * 3600 * 24));

        option = {
            title: {
                text: ''
            },
            grid: {
                left: '17%',
            },
            label: {
                color: 'rgba(255, 255, 255, 1)'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {},
            toolbox: {
                show: true,
                feature: {
                    // dataZoom: {
                    //     yAxisIndex: 'none'
                    // },
                    dataView: {
                        readOnly: false
                    },
                    magicType: {
                        type: ['line', 'bar']
                    },
                    // restore: {},
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: JSON.parse('<?php echo (json_encode($historyDateArray)); ?>').slice(366 - differenceInDays)
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    formatter: '{value} \u20AC'
                }
            },
            series: [{
                name: 'Price',
                type: 'line',
                areaStyle: {
                    color: 'rgba(104, 216, 214, 0.4)'
                },
                data: adjustPriceArray(modalPriceArray).slice(366 - differenceInDays),
                markLine: {
                    data: [{
                        type: 'average',
                        name: 'Avg'
                    }]
                },
            }]
        };

        if (option && typeof option === 'object') {
            myChart3m.setOption(option);
        }

        window.addEventListener('resize', function() {
            setTimeout(myChart3m.resize, 500)
        });
    }

    function draw3mChart() {
        // 3m chart
        
        var chart3m = document.getElementById('chart-3m');
        var myChart3m = echarts.init(chart3m, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var app = {};

        var option;

        // Get today's date
        var today = new Date();

        // Subtract 3 months from today
        var threeMonthsAgo = new Date();
        threeMonthsAgo.setMonth(threeMonthsAgo.getMonth() - 3);

        // Calculate the difference in days
        var differenceInTime = today.getTime() - threeMonthsAgo.getTime();
        var differenceInDays = Math.floor(differenceInTime / (1000 * 3600 * 24));

        option = {
            tooltip: {
                trigger: 'axis',
                valueFormatter: (value) => parseFloat(value).toFixed(2),
            },
            grid: {
                left: '17%',
            },
            label: {
                color: 'rgba(255, 255, 255, 1)'
            },
            legend: {},
            toolbox: {
                show: true,
                feature: {
                    // dataZoom: {
                    //     yAxisIndex: 'none'
                    // },
                    dataView: {
                        readOnly: false
                    },
                    magicType: {
                        type: ['line', 'bar']
                    },
                    // restore: {},
                    saveAsImage: {}
                },
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: JSON.parse('<?php echo (json_encode($historyDateArray)); ?>').slice(366 - differenceInDays)
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    formatter: '{value} \u20AC',
                    // inside: true,
                    // margin: -12
                },
            },
            series: [{
                name: 'Price',
                type: 'line',
                areaStyle: {
                    color: 'rgba(104, 216, 214, 0.4)'
                },
                data: adjustPriceArray(ydata).slice(366 - differenceInDays),
                markLine: {
                    data: [{
                        type: 'average',
                        name: 'Avg'
                    }]
                }
            }]
        };

        if (option && typeof option === 'object') {
            myChart3m.setOption(option);
        }

        window.addEventListener('resize', function() {
            setTimeout(myChart3m.resize, 500)
        });
    }

    function draw6mChart() {
        // 3m chart
        var chart6m = document.getElementById('chart-6m');
        var myChart6m = echarts.init(chart6m, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var app = {};

        var option;

        // Get today's date
        var today = new Date();

        // Subtract 3 months from today
        var threeMonthsAgo = new Date();
        threeMonthsAgo.setMonth(threeMonthsAgo.getMonth() - 6);

        // Calculate the difference in days
        var differenceInTime = today.getTime() - threeMonthsAgo.getTime();
        var differenceInDays = Math.floor(differenceInTime / (1000 * 3600 * 24));

        option = {
            title: {
                text: ''
            },
            grid: {
                left: '17%',
            },
            label: {
                color: 'rgba(255, 255, 255, 1)'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {},
            toolbox: {
                show: true,
                feature: {
                    // dataZoom: {
                    //     yAxisIndex: 'none'
                    // },
                    dataView: {
                        readOnly: false
                    },
                    magicType: {
                        type: ['line', 'bar']
                    },
                    // restore: {},
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: JSON.parse('<?php echo (json_encode($historyDateArray)); ?>').slice(366 - differenceInDays)
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    formatter: '{value} \u20AC;'
                }
            },
            series: [{
                name: 'Price',
                type: 'line',
                areaStyle: {
                    color: 'rgba(104, 216, 214, 0.4)'
                },
                data: adjustPriceArray(ydata).slice(366 - differenceInDays),
                markLine: {
                    data: [{
                        type: 'average',
                        name: 'Avg'
                    }]
                }
            }]
        };

        if (option && typeof option === 'object') {
            myChart6m.setOption(option);
        }

        window.addEventListener('resize', function() {
            setTimeout(myChart6m.resize, 500)
        });
    }

    function draw1yChart() {
        // 1y chart
        var chart1y = document.getElementById('chart-1y');
        var myChart1y = echarts.init(chart1y, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var app = {};

        var option;

        option = {
            title: {
                text: ''
            },
            grid: {
                left: '17%',
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {},
            toolbox: {
                show: true,
                feature: {
                    // dataZoom: {
                    //     yAxisIndex: 'none'
                    // },
                    dataView: {
                        readOnly: false
                    },
                    magicType: {
                        type: ['line', 'bar']
                    },
                    // restore: {},
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: JSON.parse('<?php echo (json_encode($historyDateArray)); ?>')
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    formatter: '{value} \u20AC;'
                }
            },
            series: [{
                name: 'Price',
                type: 'line',
                areaStyle: {
                    color: 'rgba(104, 216, 214, 0.4)'
                },
                data: adjustPriceArray(ydata),
                markLine: {
                    data: [{
                        type: 'average',
                        name: 'Avg'
                    }]
                }
            }]
        };

        if (option && typeof option === 'object') {
            myChart1y.setOption(option);
        }

        window.addEventListener('resize', function() {
            setTimeout(myChart1y.resize, 500)
        });
    }

    draw3mChart()
</script>