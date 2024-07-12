<?php $this->load->view('frontend/layout/header'); ?>
<?php header('Access-Control-Allow-Origin: *'); ?>
<?php error_reporting(0); ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<style>
    #loading {
        width: 100%;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-image: url(<?= base_url(); ?>assets/images/loader.gif);
        width: 100%;
        background-repeat: no-repeat;
        background-position: center;
    }
    .hide {
    opacity: 0;
    position: absolute;
    left: -9999px;
}
</style>

<form method="get" id="filterForm" name="filterForm">
    <section class="ListingPage">
        <section class="theme-padding">
            <div class="product-Listing">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-md-5   col-12">
                            <div class="Breadcrumb">
                                <ul class="list-unstyled d-flex">
                                    <li style="display: flex">
                                        <a href="<?= base_url() ?><?php echo $this->uri->segment(1) ?>"><h1 style="margin-bottom: 0; font-size: 13px; font-weight: bold"><?php echo $this->uri->segment(1) ?><span style="display: none"> Deals Ireland</span></h1></a>
                                    </li>
                                    <li><span><span style="display: none">Attractive </span><?php echo str_replace("_", " ", $this->uri->segment(2)) ?><span style="display: none"> Deals in Ireland</span></span></li>
                                </ul>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-2 col-12">
                            <div class="CompareBtn">
                                <button type="button" onclick="letsCompare(this)" class="mainbtn w-100"><i
                                            class="fa fa-exchange" aria-hidden="true"></i> Compare <span
                                            class="compare_count">0</span></button>
                            </div>
                        </div>

                        <!-- <input type="hidden" id="page" class="page" value="<?= @$page; ?>"> -->


                        <div class="col-lg-2 col-md-2 col-5">

                            <!-- <div class="SortBy">
                          <select class="stock is_stock"  onchange="getTopFIlter('desktop');" name="is_stock" id="In_Stock">
                            <option value="0">In Stock</option>
                            <option value="1">In Stock</option>
                            <option value="0">Out of Stock</option>
                          </select>
                        </div> -->
                            <div class="CompareBtn">
                                <div class="In-Stock-checkbox-box" id="InStock">
                                    <input type="checkbox"
                                           class="stock is_stock common_selector" <?php echo $_GET['In_Stock'] == 1 ? "checked" : ""; ?>
                                           name="is_stock" id="In_Stock" value="1" onclick="getTopFIlter('desktop');">
                                    <label class="radio_btn" for="In_Stock"><span class="checkmark"></span> In Stock</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-3 col-7">
                            <div class="SortBy" id="SortBy">
                                <select class="wide sort_by" name="sort_by" id="sort_by"
                                        onchange="getTopFIlter('desktop');">
                                    <option value="">Sort by Latest</option>
                                    <option value="asc">Low to High</option>
                                    <option value="desc">High to Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- <div class="col-12 hidden-lgBtn pl-lg-0">
                            <div class="FilterBtn">
                                <a class="btn btn-primary mainbtn nav-btn nav-slider  "
                                   style="color:#fff; line-height:48px">Filter results</a>
                            </div>
                        </div> -->

                    </div>

                    <!-- <div class="container">
                        <div class="row mb-4 filter-top">

                        </div>
                    </div> -->
                    
                    <div class="row mobile-filter-row">
                        <div class="mobile-filter col-12">
                            <?php if ($catid != '') { ?>
                                <?php if (is_array($brandList) && (($brandList[0] > 0 && count($brandList) > 0) || (is_array($subcategoryListData) && count($subcategoryListData) > 0)))  { ?>
                                    <ul class="label-filter">
                                        <h4>Filters:</h4>
                                        <?php if (count($subcategoryListData) > 0) {
                                            foreach ($subcategoryListData as $catval) { ?>
                                                <li>
                                                    <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $catval->id; ?>" data-catvalName="<?php echo $catval->name; ?>"><?php echo $catval->name; ?>
                                                        <i class="fa fa-times" aria-hidden="true"></i></a>
                                                </li>
                                        <?php }} ?>

                                        <?php if (count($brandListData) > 0) {
                                            foreach ($brandListData as $brandval) { ?>
                                                <li>
                                                    <a href="#" class="remveBrandFromFliter"
                                                       data-brandvalId="<?php echo $brandval->id; ?>"><?php echo $brandval->alias; ?>
                                                        <i class="fa fa-times" aria-hidden="true"></i></a>
                                                </li>
                                            <?php }
                                        } ?>
                                            <li class="remove-all-filter">
                                                <a href="<?php echo $current_url; ?>"> Remove All Filters </a>
                                            </li>
                                        </ul>
                                    <?php } ?>
                                <?php } ?>
                        </div>
                    </div>

                    <div class="row m-0">

                        <div class="overlay"></div>
                        <div class="col-lg-2 col-md-4 col-sm-12 col-12 px-0">
                            <nav class="sidebar" id="accordion-menu">
                                <div class="FiltersMain desktop">
                                    <div class="checkBoxFilter discount-range-panel" id="discountFilter">
                                        <h3 class="Filthead mb-0">Discount Range</h3>
                                        <input type="text" id="discount_range" name="discount_range" class="discount_range" />
                                    </div>

                                    <div class="checkBoxFilter price-range-panel" id="priceFilter">
                                        <h3 class="Filthead mb-0">Price Range</h3>
                                        <input type="text" id="rangedesktop" name="rangedesktop" class="price_range1"/>
                                    </div>

                                    <div class="priceFilter">
                                    </div>

                                    <!-- <div class="checkBoxFilter checkbox-none">
                    <?php if (empty($subCategorySlug)) { ?>
                      <h3 class="Filthead"><?php echo ucwords($category['name']); ?></h3>
                      <input type="hidden" name="category_id" id="category_id" value="<?= @$category_id; ?>">
                      <input type="hidden" name="subcategory_id" id="subcategory_id" value="<?= @$subcategory_id; ?>">
                      <?php
                        foreach ($subcategories as $key => $value) :

                            $count = chechProductsBySubCategory($value->id);
                            if (!$count) {
                                continue;
                            }

                            ?>
                        <a href="<?php echo base_url(); ?><?php echo $cat_slug; ?>/<?php echo $value->slug; ?>"><label class="CustomCheck"><?= $value->name; ?><span class="checkmark"></span>(<?php echo isset($count) ? $count : 0 ?>)
                          </label></a>
                      <?php endforeach ?>
                    <?php } ?>
                  </div> -->

                                    <div class="checkBoxFilter checkbox-none">
                                        <?php if (empty($subCategorySlug)) { ?>
                                            <h3 class="Filthead"><?php echo ucwords($category['name']); ?></h3>
                                            <input type="hidden" name="category_id" id="category_id"
                                                   value="<?= @$category_id; ?>">
                                            <input type="hidden" name="subcategory_id" id="subcategory_id"
                                                   value="<?= @$subcategory_id; ?>">
                                            <?php

                                            $_subCategories = [];
                                            $_counts = [];
                                            foreach ($subcategories as $subCategory) {
                                                $count = chechProductsBySubCategory($subCategory->id);
                                                if ($count == 0)
                                                    continue;
                                                $categoryName = $subCategory->name;
                                                if (strpos($categoryName, "Men") !== false)
                                                    $categoryName = "Men";
                                                else if (strpos($categoryName, "Women") !== false)
                                                    $categoryName = "Women";
                                                else if (strpos($categoryName, "Unisex") !== false)
                                                    $categoryName = "Unisex";
                                                if (!isset($_subCategories[$categoryName])) {
                                                    $_subCategories[$categoryName] = [];
                                                    $_counts[$categoryName] = 0;
                                                }
                                                $_subCategories[$categoryName][] = $subCategory;
                                                $_counts[$categoryName] += $count;
                                            }

                                            foreach ($_subCategories as $class => $classified) {
                                                if (count($classified) > 1) {
                                                ?>
                                                    <label class="CustomCheck" data-toggle='collapse' data-target="#category-<?= $class ?>" >
                                                        <?= $class ?>
                                                    </label>
                                                    <div id="category-<?= $class ?>" class="collapse">
                                                <?php
                                                }
                                                foreach ($classified as $key => $value) {
                                                
                                                    if (in_array(($value->id), $subcategoryList)) {
                                                        $check = 'checked="checked"';
                                                    }
                                                    else { $check = ""; }

                                                    if ($_GET['min_price']) {
                                                        $brand = explode(",", @$_GET['brand']);
                                                        $count = chechProductsBySubCategoryWith_price($value->id, $category_id, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock'], @$_GET['from_discount'], @$_GET['to_discount'], $brand);
                                                    } else {
                                                        $count = chechProductsBySubCategory($value->id);
                                                    }
                                                    //$count = chechProductsBySubCategory($value->id);
                                                    if (!$count) {
                                                        continue;
                                                    }

                                                    ?>
                                                    <label class="CustomCheck"
                                                        style="padding-left: 20px;"><?= $value->name; ?>
                                                        (<?php echo isset($count) ? $count : 0 ?>)
                                                        <input type="checkbox" name="subcategories"
                                                            id="subcategories<?= $value->id ?>"
                                                            class="common_selector subcategories"
                                                            value="<?= $value->id ?>" <?php echo $check; ?>
                                                            onclick="getTopFIlter('desktop');">
                                                        <span class="checkmark" style="display: block;"></span>
                                                    </label>
                                            <?php }
                                            if (count($classified) > 1) {
                                            ?>
                                                </div>
                                        <?php }
                                            } 
                                        }?>
                                    </div>

                                    <?php //echo "<pre>"; print_r($brands); die;
                                    ?>
                                    <div class="checkBoxFilter" id="filterBrand">
                                        <div id="filter_brand">
                                            <div class="show-brand show-more-height">
                                                <h3 class="Filthead">Brands</h3>
                                                <input type="text" name="brand" id="brand">
                                                <div id="brand1">
                                                    <?php
                                                    foreach ($brands as $key => $value) {
                                                        if (is_array($brandList) && in_array(($value->id), $brandList))
                                                            $check = 'checked="checked"';
                                                        else $check = "";
                                                        if (!empty($subcategory_id)) {
                                                            if ($_GET['min_price'] && $_GET['min_price'] != 'null') {
                                                                $count = chechProductsByBrandSubWith_price($category_id, @$subcategory_id, $value->id, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock'], $_GET['from_discount'], @$_GET['to_discount']);
                                                            } else {
                                                                $count = chechProductsByBrandSub($category_id, @$subcategory_id, $value->id);
                                                            }
                                                        } else {
                                                            if ($_GET['min_price']) {
                                                                $count = chechProductsByBrandWith_price($category_id, @$subcategory_id, $value->id, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock'], $_GET['from_discount'], @$_GET['to_discount']);
                                                            } else {
                                                                $count = chechProductsByBrand($category_id, $value->id);
                                                            }
                                                        }
                                                        if (!$count) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <label class="CustomCheck"><?php echo $value->alias; ?>
                                                            (<?php echo isset($count) ? $count : 0 ?>)
                                                            <input type="checkbox" name="brands"
                                                                   id="brands_<?php echo $value->id; ?>"
                                                                   class="common_selector brands"
                                                                   value="<?= $value->id ?>" <?php echo $check; ?>
                                                                   onclick="getTopFIlter('desktop');">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php if (count($brands) > 5) { ?>
                                                <div class="show-more">Show More</div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- <div class="checkBoxFilter" id="filter_brand">
                              </div> -->


                                </div>
                            </nav><!-- @end nav -->
                        </div>

                        <?php //echo $pagination;
                        ?>
                        <?php //echo "<pre>"; print_r($minmaxprice);
                        ?>

                        <div class="col-lg-10 col-md-8 col-sm-12 col-12">
                            <?php if ($catid != '') { ?>
                                    <?php if (($brandList[0] > 0 && count($brandList) > 0) || (is_array($subscategoryListData) && count($subcategoryListData) > 0))  { ?>
                                        <ul class="label-filter desktop-filter">
                                            <h4>Filters:</h4>
                                            <?php if (count($subcategoryListData) > 0) {
                                            foreach ($subcategoryListData as $catval) { ?>
                                                <li>
                                                    <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $catval->id; ?>" data-catvalName="<?php echo $catval->name; ?>"><?php echo $catval->name; ?>
                                                        <i class="fa fa-times" aria-hidden="true"></i></a>
                                                </li>
                                            <?php }} ?>

                                            <?php if ($brandList[0] > 0) {
                                                foreach ($brandListData as $brandval) { ?>
                                                    <li>
                                                        <a href="#" class="remveBrandFromFliter" data-brandvalId="<?php echo $brandval->id; ?>"><?php echo $brandval->alias; ?>
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </a>
                                                    </li>
                                            <?php }} ?>
                                                <li class="remove-all-filter">
                                                    <a href="<?php echo $current_url; ?>">Remove All Filters</a>
                                                </li>
                                        </ul>
                                    <?php } ?>
                                <?php } ?>
                            <div class="row" <?php echo count($result) == 0 ? '' : '' ?>>
                                <?php if (count($result) > 0) : foreach ($result as $key => $value) { ?>
                                    <div class="col wow fadeInUp">

                                        <div class="product-ListingBox hoverAnimation">

                                            <?php if ($value->selling_price < $value->cost_price) { ?>
                                                <div class="product-discount-percent">
                                                    -<?= round(100 - $value->selling_price / $value->cost_price * 100, 0) ?>%
                                                </div>
                                            <?php } ?>

                                            <a href="<?= base_url() ?><?php echo $value->category_slug; ?>/<?php echo $value->slug; ?>">
                                                <div class="product-image">
                                                    <?php if ($value->image != 'https://images2.productserve.com/noimage.gif' && $value->image != 'image'  && $value->image != '') { ?>
                                                        <img class="div-lazy-loader" data-src="<?php echo showImage($this->config->item('product'), $value->image); ?>"
                                                             alt="<?= $value->name ?>">
                                                    <?php } else { ?>
                                                        <img class="div-lazy-loader" data-src="https://www.onsalenow.ie/assets/img/no-image.png"
                                                             alt="<?= $value->name ?>">
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
                                                    <h3><?php echo $value->name ?></h3>
                                                </a>
                                                <p <?php echo ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) ? 'style="color:red"':""?>>
                                                <?php echo floatval($value->selling_price) > 0 ? '&euro;' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price) : ''; ?>
                                                <?php
                                                if ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) { ?>
                                                    <span style='color: black'><del><?php echo '&euro;' . (is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></del></span>
                                                <?php } ?>
                                                </p>

                                                <div class="product_check">
                                                    <input class="compare-style-for-checkbox" <?php if (is_array($compare) && in_array($value->id, $compare)) {
                                                        echo "checked";
                                                    } ?> type="checkbox" id="compare_<?php echo $value->id ?>"
                                                           name="compare" value="<?php echo $value->id ?>">+ Compare</div>
                                            </div>

                                        </div>

                                    </div>

                                <?php } ?>


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

                            </div>

                            <div class="row justify-content-center m-0">

                                <nav aria-label="Page navigation example">
                                    <div id="pagination_link">
                                        <?= $pagination; ?>
                                    </div>
                                </nav>

                            </div>


                        </div>


                    </div>

        </section>

    </section>
</form>

<?php $this->load->view('frontend/layout/footer'); ?>

<!-- <script src="<?php // base_url()
?>assets/js/filter.js"></script>  -->
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // Lấy giá trị của ID từ URL
        const urlParams = new URLSearchParams(window.location.search);
        // const pageId = <?= @$category_id; ?>;
        
        var currentPath = window.location.pathname;
            // Kiểm tra nếu đường dẫn chứa "/Fashion" và theo sau bởi một hoặc nhiều số
        var regex = /\/Fashion\/\d+$/;
 
        // Kiểm tra ID và ẩn/hiện các bộ lọc tương ứng
        if (regex.test(currentPath)||currentPath==="/Fashion") {
            document.getElementById('discountFilter').classList.add('hide');
            document.getElementById('priceFilter').classList.add('hide');
            document.getElementById('InStock').classList.add('hide');
            document.getElementById('SortBy').classList.add('hide');
        } else {
            document.getElementById('discountFilter').style.display = 'block';
            document.getElementById('priceFilter').style.display = 'block';
            document.getElementById('InStock').style.display = 'block';
            document.getElementById('SortBy').style.display = 'block';
        }
    });
    $(document).on('keyup', '#brand', function () {
        var text = this.value;
        // console.log(text);
        //alert(text)
        var catid = "<?php echo $category_id; ?>";
        // alert(catid);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'home/getAllBrand' ?>",
            data: {
                text: text,
                catid: catid
            },
            dataType: "html",
            cache: false,
            success: function (data) {
                console.log(data);
                //  var totalLength =  data['allbrand'].length;

                if (data) {

                    // $('#filterBrand').fadeIn();
                    $('#brand1').html(data);
                    // $(".brand1").css("display", "block");

                } else {
                    $('#brand1').fadeIn();
                    // $(".brand1").css("display", "none");
                }
            }
        });
    });

    function discountClicked() {
        setTimeout(() => {
            getTopFIlter('desktop');
        }, 100);
    }

    function extractPrice(price) {
        price = price.replace(' ', "");

        var matches = price.match(/\d+(\.\d+)?/);
        return matches ? matches[0] : null;
    }
    // Hàm để trích xuất giá trị từ chuỗi giá
    function extractPrice(priceString) {
        return parseFloat(priceString.replace(/[^0-9.-]+/g, ""));
    }

    $(document).ready(function () {
        var minPriceSet = false;
        var maxPriceSet = false;
        var currentMinPrice = 0;
        var currentMaxPrice = 100000;

        function extractPrice(priceStr) {
            return parseFloat(priceStr.replace(/[^\d.-]/g, ''));
        }

        var slider = $('#rangedesktop');

        var minprice11 = "<?php echo $minmaxprice[0]->min_price; ?>";
        var maxprice11 = "<?php echo $minmaxprice[0]->max_price; ?>";

        var minpricedefault = parseFloat(minprice11);
        var maxpricedefault = parseFloat(maxprice11);

        if (minpricedefault == maxpricedefault)
            minpricedefault = 1;

        var minprice111 = "<?php echo $filter_min_price; ?>";
        var maxprice111 = "<?php echo $filter_max_price; ?>";

        var minprice1 = minprice111.replace(" ", "");
        var maxprice1 = maxprice111.replace(" ", "");

        if (minprice1 == '') {
            minprice1 = minprice11;
        }

        if (maxprice1 == '') {
            maxprice1 = maxprice11;
        }

        if (minprice1 != '') {
            var minprice = parseFloat(minprice1);
        }

        if (maxprice1 != '') {
            var maxprice = parseFloat(maxprice1);
        }

        if (isNaN(minpricedefault)) {
            minpricedefault = 0;
        }
        if (isNaN(maxpricedefault)) {
            maxpricedefault = 0;
        }

        let from_k = Math.floor(minpricedefault) > Math.floor(minprice) ? Math.floor(minpricedefault) : Math.floor(minprice);
        let from_to = Math.ceil(maxpricedefault) < Math.ceil(maxprice) ? Math.ceil(maxpricedefault) : Math.ceil(maxprice);

        slider.ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: Math.floor(minpricedefault),
            max: Math.ceil(maxpricedefault),
            from: from_k,
            to: from_to,
            type: 'double',
            step: 1,
            prefix: "&euro;",
            grid: true,
            onFinish: function (data) {
                minPriceSet = true;
                maxPriceSet = true;
                currentMinPrice = data.from;
                currentMaxPrice = data.to;
                getTopFIlter('desktop');
            }
        });

        var discountSlider = $('#discount_range');
        discountSlider.ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: 0,
            max: 100,
            max_interval: Math.ceil(parseFloat('<?php echo $discount->percent_discount; ?>')) == 0 ? 0.1 : Math.ceil(parseFloat('<?php echo $discount->percent_discount; ?>')),
            from: parseInt('<?php echo $fromToDiscount[0]; ?>'),
            to: Math.ceil(parseFloat('<?php echo $fromToDiscount[1]; ?>')),
            type: 'double',
            step: 1,
            postfix: "%",
            grid: true,
            onFinish: function(data) {
                getTopFIlter('desktop');
            }
        });

        function getTopFIlter(abc) {
            var categories = [];
            var subcategories = [];
            var brands = [];
            var stock = 0;

            var max_price, min_price;
            if (minPriceSet && maxPriceSet) {
                min_price = currentMinPrice;
                max_price = currentMaxPrice;
            }

            var maxDiscount = $('.discount-range-panel .irs-to').text();
            var minDiscount = $('.discount-range-panel .irs-from').text();

            minDiscount = extractPrice(minDiscount);
            maxDiscount = extractPrice(maxDiscount);

            var sort_by = $('#sort_by').val();

            $('input[name="subcategories"]:checked').each(function () {
                subcategories.push($(this).val());
            });

            $('input[name="brands"]:checked').each(function () {
                brands.push($(this).val());
            });

            $('input[name="is_stock"]:checked').each(function () {
                stock = 1;
            });

            var params = "<?php echo $param; ?>";

            if (params != '') {
                var url = "<?php echo $current_url; ?>";
            } else {
                var last_uri_seg = "<?php echo $last_uri_seg; ?>";
                var url = "<?php echo $current_url; ?>";
                var subcategory_id = "<?php echo $subcategory_id; ?>";
                var categories = "<?php echo $category_id; ?>";

                if (subcategories.length === 0) {
                    subcategory_id = "<?php echo $subcategory_id; ?>";
                } else {
                    subcategory_id = subcategories.join(',');
                }

                var last = "<?php echo base_url() . $last_uri_seg . '/' . '1'; ?>";

                var query = '?category=' + categories + '&subcategory=' + subcategory_id + '&brand=' + brands.join(',') + '&from_discount=' + minDiscount + '&to_discount=' + maxDiscount + '&sort_by=' + sort_by + '&In_Stock=' + stock;

                if (minPriceSet && maxPriceSet) {
                    query += '&min_price=' + min_price + '&max_price=' + max_price;
                }

                if (subcategory_id != '') {
                    window.location.href = url + query;
                } else {
                    if (last_uri_seg != '') {
                        window.location.href = last + query;
                    } else {
                        window.location.href = url + query;
                    }
                }
            }
        }

        $(document).on('change', 'input[name="brands"]', function () {
            getTopFIlter('desktop');
        });

        $(document).on('change', 'input[name="subcategories"]', function () {
            getTopFIlter('desktop');
        });

        $(document).on('change', 'input[name="is_stock"]', function () {
            getTopFIlter('desktop');
        });

        $(document).on('submit', 'form#filterForm', function (ev) {
            ev.preventDefault();
            getTopFIlter('desktop');
        });

        // Đảm bảo rằng sự kiện onchange cho #sort_by hoạt động
        $('#sort_by').on('change', function() {
            getTopFIlter('desktop');
        });
    });

    function extractPrice(priceStr) {
        return parseInt(priceStr.replace(/[^0-9]/g, ''), 10);
    }
    // Hàm để đặt lại giá trị của phần tử select khi trang được tải lại
    function setSortByFromURL() {
        let params = new URLSearchParams(window.location.search);
        let sort_by = params.get('sort_by');
        if (sort_by) {
            $('#sort_by').val(sort_by);
        }
    }

    // Gọi hàm setSortByFromURL khi trang được tải
    $(document).ready(function() {
        setSortByFromURL();
    });

    $(".show-more").click(function () {

        if ($(".show-brand").hasClass("show-more-height")) {
            $(this).text("Show Less");
        } else {
            $(this).text("Show More");
        }

        $(".show-brand").toggleClass("show-more-height");
    });


    $(document).on('click', '.remveBrandFromFliter', function (event) {
        event.preventDefault();
        var brandId = $(this).attr('data-brandvalId')
        $('#brands_' + brandId).prop('checked', false);
        $(this).parent().remove();
        getTopFIlter('desktop');

    })

    $(document).on('click', '.remveCategoryFromFliter', function(event) {
        event.preventDefault();
        var catId = $(this).attr('data-catvalId')
        $('#subcategories' + catId).prop('checked', false);
        $(this).parent().remove();
        getTopFIlter('desktop');
    })
    
</script>
