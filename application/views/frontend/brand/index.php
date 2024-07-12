<?php $this->load->view('frontend/layout/header'); ?>
<?php header('Access-Control-Allow-Origin: *'); ?>
<style>
    #loading {
        width: 100%;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background-repeat: no-repeat;
        background-position: center;
        background-image: url(<?= base_url(); ?>assets/images/loader.gif);
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
                                        <a href="<?= base_url() ?><?php echo $this->uri->segment(1) ?>">
                                            <h1 style="margin-bottom: 0; font-size: 13px; font-weight: bold"><?= ucwords(@$name); ?><span style="display: none"> Sales Ireland</span></h1>
                                        </a>
                                    </li>
                                    <?php if ($this->uri->segment(2)) : ?>
                                        <li><span><?php echo str_replace("_", " ", $this->uri->segment(2)) ?></span></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-12">
                            <div class="CompareBtn">
                                <button type="button" onclick="letsCompare(this)" class="mainbtn w-100"><i class="fa fa-exchange" aria-hidden="true"></i> Compare <span class="compare_count">0</span></button>
                            </div>
                        </div>

                        <!-- <input type="hidden" id="page" class="page" value="<?= @$page; ?>"> -->


                        <div class="col-lg-2 col-md-2 col-5">
                            <div class="CompareBtn">
                                <div class="In-Stock-checkbox-box">
                                    <input type="checkbox" class="stock is_stock common_selector" <?php echo $_GET['In_Stock'] == 1 ? "checked" : ""; ?> name="is_stock" id="In_Stock" value="1" onclick="setTimeout(getTopFIlter, 500);">
                                    <label class="radio_btn" for="In_Stock"><span class="checkmark"></span> In Stock</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-3 col-7">
                            <div class="SortBy">
                                <select class="wide sort_by" name="sort_by" id="sort_by" onchange="getTopFIlter('desktop');">
                                    <option value="">Sort by Latest</option>
                                    <option value="asc">Low to High</option>
                                    <option value="desc">High to Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- <div class="col-12 hidden-lgBtn pl-lg-0">
                            <div class="FilterBtn">
                                <a class="btn btn-primary mainbtn nav-btn nav-slider  " style="color:#fff; line-height:48px">Filter results</a>
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="container">
                        <div class="row mb-4 filter-top">
                        </div>
                    </div> -->

                    <!-- </div> -->
                    <div class="row mobile-filter-row">
                        <div class="mobile-filter col-12">
                            <?php if ((!empty(@$brandId))) {
                                if ((!empty($subcategoryListData))) { ?>
                                    <?php if (!empty($subcategoryListData)) { ?>
                                        <ul class="label-filter">
                                            <h4>Filters:</h4>
                                            <?php if (!empty($subcategoryListData)) {
                                                foreach ($subcategoryListData as $catval) { ?>
                                                    <li>
                                                        <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $catval->id; ?>" data-catvalName="<?php echo $catval->name; ?>"><?php echo $catval->name; ?>
                                                            <i class="fa fa-times" aria-hidden="true"></i></a>
                                                    </li>
                                            <?php }
                                            } ?>

                                            <li class="remove-all-filter">
                                                <a href="<?php echo $current_url; ?>"> Remove All Filters </a>
                                            </li>
                                        </ul>
                                    <?php } ?>
                            <?php }
                            } ?>
                        </div>
                    </div>
                    <div class="row mb-4 mt-3 upperSec align-items-center" style="padding-bottom: 0!important; padding-top: 10px">
                        <?php
                        if (!isset($image) || empty($image) || $image == 'image') {
                            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="DetailPageContent pl-0" style="padding-top: 0">
                                <div class="DetailHeadDes mt-0">
                                <h2 style="padding-bottom: 10px">' . ucwords(@$name) . '</h2>
                                </div>
                                </div>
                                </div>';
                        }
                        ?>

                        <?php
                        if ($brand['is_image'] && isset($image) && !empty($image) && !($image == 'image')) {
                            echo '<div style="margin-bottom: 10px!important" class="col-lg-2 col-md-4 col-sm-12 col-12">
                            <div class="TOpProductImage"><img class="div-lazy-loader" data-src="' . $image . '" alt="' . ucwords(@$name) . '"></div></div>';
                        }
                        ?>

                        <?php
                        if (!isset($description) || empty(trim($description))) {
                            $description = $meta_description;
                        }

                        echo '<div class="col-lg-10 col-md-8 col-sm-12 col-12">
                                <div class="DetailPageContent">
                                    <div class="DetailHeadDes mt-0">
                                        <p style="margin-bottom: 0">' . strip_tags($description) . '</p>
                                    </div>
                                </div>
                            </div>';
                        ?>

                    </div>

                    <div class="row m-0">
                        <div class="overlay"></div>

                        <div class="col-lg-2 col-md-4 col-sm-12 col-12 px-0">
                            <nav class="sidebar" id="accordion-menu">
                                <div class="FiltersMain">
                                    <div class="checkBoxFilter discount-range-panel">
                                        <h3 class="Filthead mb-0">Discount Range</h3>
                                        <input type="text" id="discount_range" name="discount_range" class="discount_range" />
                                    </div>
                                    <div class="checkBoxFilter price-range-panel">
                                        <h3 class="Filthead mb-0">Price Range</h3>
                                        <input type="text" id="range" name="range" class="price_range" />
                                    </div>
                                    <div class="priceFilter">
                                    </div>
                                    <div class="checkBoxFilter">
                                        <?php foreach ($category as $key => $value) :
                                            $cat_slug = $value->slug;
                                            $category['name'] = ucwords($value->name);
                                            if (!empty($categoryList)) {
                                                if (in_array(($value->id), $categoryList)) :
                                                    $check = 'checked="checked"';
                                                else : $check = "";
                                                endif;
                                            }

                                            $count = getProductsByCategory($value->id, $brand_id);
                                            if (!$count) {
                                                continue;
                                            }

                                        ?>
                                        <?php endforeach ?>
                                    </div>
                                    <div class="checkBoxFilter">
                                        <?php if (empty($subCategorySlug)) { ?>
                                            <h3 class="Filthead">Categories</h3>
                                            <input type="hidden" name="category_id" id="category_id" value="<?= @$category_id; ?>">
                                            <input type="hidden" name="subcategory_id" id="subcategory_id" value="<?= @$subcategory_id; ?>">
                                            <?php
                                            foreach ($allsubcategory as $key => $value) :
                                                if (!empty($subcategoryList)) {
                                                    if (in_array(($value->id), $subcategoryList)) :
                                                        $check = 'checked="checked"';
                                                    else : $check = "";
                                                    endif;
                                                }
                                            ?>
                                                <label class="CustomCheck"><?= ucwords($value->name); ?>
                                                    <input type="checkbox" name="subcategories" data-parent="<?= $value->category_id ?>" id="categoryID<?= $value->id ?>" class="common_selector categories" value="<?= $value->id ?>" <?php if (!empty($subcategoryListData)) {
                                                                                                                                                                                                                                            echo $check;
                                                                                                                                                                                                                                        } ?> onclick="getTopFIlter();">
                                                    <span class="checkmark"></span>(<?php echo isset($value->prd_cnt) ? $value->prd_cnt : 0 ?>)
                                                    <span class="category_<?= $value->id; ?> childClass"></span>
                                                </label>

                                            <?php endforeach ?>
                                        <?php } ?>
                                    </div>
                                </div>
                        </div>
                        <div class="col-lg-10 col-md-8 col-sm-12 col-12">
                            <?php if ((!empty(@$brandId))) {
                                if ((!empty($categoryListData))) {
                            ?>
                                    <?php if (!empty($categoryListData) || !empty($subcategoryListData)) { ?>
                                        <ul class="label-filter desktop-filter">
                                            <h4>Filters:</h4>
                                            <?php if (!empty($subcategoryListData)) {
                                                foreach ($subcategoryListData as $catval) { ?>
                                                    <li>
                                                        <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $catval->id; ?>" data-catvalName="<?php echo $catval->name; ?>"><?php echo $catval->name; ?>
                                                            <i class="fa fa-times" aria-hidden="true"></i></a>
                                                    </li>
                                            <?php }
                                            } ?>

                                            <li class="remove-all-filter">
                                                <a href="<?php echo $current_url; ?>">Remove All Filters </a>
                                            </li>
                                        </ul>
                                    <?php } ?>
                            <?php }
                            } ?>
                            <div class="row" <?php echo count($brandData) == 0 ? '' : '' ?>>

                                <?php if (count($brandData) > 0) : foreach ($brandData as $key => $value) {
                                        error_reporting(0);
                                ?>
                                        <div class="col wow fadeInUp">

                                            <div class="product-ListingBox hoverAnimation">

                                                <?php if ($value->selling_price < $value->cost_price) { ?>
                                                    <div class="product-discount-percent">
                                                        -<?= round(100 - $value->selling_price / $value->cost_price * 100, 0) ?>%
                                                    </div>
                                                <?php } ?>

                                                <a href="<?= base_url() ?><?php echo @$value->category_slug; ?>/<?php echo @$value->slug; ?>">
                                                    <div class="product-image">
                                                        <?php if ($value->image != 'https://images2.productserve.com/noimage.gif' && $value->image != 'image' && $value->image != '') { ?>
                                                            <img class="div-lazy-loader" data-src="<?php echo $value->image; ?>" alt="<?= $value->name ?>">
                                                        <?php } else { ?>
                                                            <img class="div-lazy-loader" alt="default image" data-src="<?php echo base_url('uploads/product/default.png'); ?>" id="show-img">
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
                                                    <p <?php echo ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) ? 'style="color:red"' : "" ?>>
                                                        <?php echo floatval($value->selling_price) > 0 ? '€' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price) : ''; ?>
                                                        <?php
                                                        if ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) { ?>
                                                            <span style='color: black'><del><?php echo '€' . (is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></del></span>
                                                        <?php } ?>
                                                    </p>

                                                    <div class="product_check">
                                                        <input class="compare-style-for-checkbox" <?php if (in_array($value->id, $compare)) {
                                                                                                        echo "checked";
                                                                                                    } ?> type="checkbox" id="compare_<?php echo $value->id ?>" name="compare" value="<?php echo $value->id ?>">+ Compare
                                                    </div>
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

                </div>
        </section>
    </section>
</form>

<?php $this->load->view('frontend/layout/footer'); ?>

<script>
    function extractPrice(price) {
        price = price.replace(' ', "");

        var matches = price.match(/\d+(\.\d+)?/);
        return matches ? matches[0] : null;
    }
// Hàm để trích xuất giá trị từ chuỗi giá
    function extractPrice(priceString) {
        return parseFloat(priceString.replace(/[^0-9.-]+/g, ""));
    }
    function getTopFIlter() {
        var filter_by = [];
        var categories = [];
        var subcategories = [];
        var subCat = [
            [],
            []
        ];
        var brands = [];
        var stock = 0;

        var max_price = $('.price-range-panel .irs-to').text();
        var min_price = $('.price-range-panel .irs-from').text();

        min_price = extractPrice(min_price);
        max_price = extractPrice(max_price);

        var maxDiscount = $('.discount-range-panel .irs-to').text();
        var minDiscount = $('.discount-range-panel .irs-from').text();

        minDiscount = extractPrice(minDiscount);
        maxDiscount = extractPrice(maxDiscount);

        var sort_by = $('#sort_by').val();

        $('input[name="is_stock"]:checked').each(function() {
            stock = 1;
        });

        $('input[name="categories"]:checked').each(function() {
            categories.push($(this).val());
        });

        if (categories != '') {
            filter_by.push({
                'key': 'categories',
                'value': categories
            });
        } else {
            categories.push($("#category_id").val());
            filter_by.push({
                'key': 'categories',
                'value': categories
            });
        }

        var i = 0;
        $('input[name="subcategories"]:checked').each(function(index, data) {
            parent = $(this).attr('data-parent');
            subcategories.push($(this).val());
            categories.push(parent);
        });
        if (subcategories != '') {
            filter_by.push({
                'key': 'sub_categories',
                'value': subcategories
            });
            filter_by.push({
                'key': 'categories',
                'value': categories
            });
        }

        var brands = "<?php echo $brand_id; ?>";

        var params = "<?php echo $param; ?>";

        var url = "<?php echo $current_url; ?>";

        var last_uri_seg = "<?php echo $last_uri_seg; ?>";

        var last = "<?php echo base_url() . $last_uri_seg . '/' . '1'; ?>";
        let final_url;
        if (last_uri_seg != '') {
            final_url = last + '?brand=' + brands + '&category=' + categories + '&subcategory=' + subcategories + '&min_price=' + min_price + '&max_price=' + max_price + '&from_discount=' + minDiscount + '&to_discount=' + maxDiscount + '&sort_by=' + sort_by + '&In_Stock=' + stock;
        } else {
            final_url = url + '?brand=' + brands + '&category=' + categories + '&subcategory=' + subcategories + '&min_price=' + min_price + '&max_price=' + max_price + '&from_discount=' + minDiscount + '&to_discount=' + maxDiscount + '&sort_by=' + sort_by + '&In_Stock=' + stock;
        }
        window.location.href = final_url;
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

    $(document).ready(function() {
        var minprice11 = "<?php echo $minmaxprice[0]->min_price ?? 0; ?>";
        var maxprice11 = "<?php echo $minmaxprice[0]->max_price ?? 0; ?>";

        var minpricedefault = parseFloat(minprice11.replace(" ", ""));
        var maxpricedefault = parseFloat(maxprice11.replace(" ", ""));

        var minprice111 = "<?php echo $filter_min_price; ?>";
        var maxprice111 = "<?php echo $filter_max_price; ?>";

        var minprice1 = minprice111.replace(" ", "");
        var maxprice1 = maxprice111.replace(" ", "");

        if (minprice1 != '') {
            let minprice2 = minprice1.replace("€", "");
            var minprice = parseFloat(minprice2);
        }

        if (maxprice1 != '') {
            let maxprice2 = maxprice1.replace("€", "");
            var maxprice = parseFloat(maxprice2);
        }

        let from_k = Math.floor(minpricedefault) > Math.floor(minprice) ? Math.floor(minpricedefault) : Math.floor(minprice);
        let from_to = Math.ceil(maxpricedefault) < Math.ceil(maxprice) ? Math.ceil(maxpricedefault) : Math.ceil(maxprice);

        var slider = $('#range');
        slider.ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: Math.floor(minpricedefault),
            max: Math.ceil(maxpricedefault),
            from: from_k,
            to: from_to,
            type: 'double',
            step: 1,
            prefix: "€",
            grid: true,
            onFinish: function(data) {
                getTopFIlter();
            },
        });

        var slider = $('#discount_range');
        slider.ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: 0,
            max: 100,
            max_interval: Math.ceil(parseFloat('<?php echo $discount->percent_discount; ?>')) == 0 ? 0.1 : Math.ceil(parseFloat('<?php echo $discount->percent_discount; ?>')),
            from: parseInt('<?php echo $fromToDiscount[0] ?? '0'; ?>'),
            to: Math.ceil(parseFloat('<?php echo $fromToDiscount[1] ?? '0'; ?>')),
            type: 'double',
            step: 1,
            postfix: "%",
            grid: true,
            onFinish: function(data) {
                if (data.from_percent !== Math.floor(data.to_percent)) {
                    getTopFIlter();
                }
            },
        });

        $(document).on('submit', 'form#filterForm', function(ev) {
            ev.preventDefault();
            getTopFIlter();
        })
    });

    $(document).on('click', '.remveCategoryFromFliter', function(event) {
        event.preventDefault();
        var catId = $(this).attr('data-catvalId')
        $('#categoryID' + catId).prop('checked', false);
        $(this).parent().remove();
        getTopFIlter();
    })

    $(document).on('click', '.removeDiscountFromFilter', function(event) {
        event.preventDefault();
        var disId = $(this).attr('data-disvalId')
        $('#discount_' + disId).prop('checked', false);
        $(this).parent().remove();
        getTopFIlter();
    })

    $(document).on('click', '.remvePriceFromFliter', function(event) {
        event.preventDefault();
        $(this).parent().remove();
        getTopFIlter();
    })
</script>