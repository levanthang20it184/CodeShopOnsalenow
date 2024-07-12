<?php $this->load->view('frontend/layout/header'); ?>
<style>
    /* .hide {
    opacity: 0;
    position: absolute;
    left: -9999px;
    } */
</style>
<section class="theme-padding">
    <div class="container ListingPage">
        <form method="get" id="filterForm" name="filterForm">
            <div class="Breadcrumb row">
                <div class="col-lg-6 col-md-5 col-12">
                    <ul class="list-unstyled d-flex">
                        <li><a href="<?php echo base_url() ?>">Home</a></li>
                        <li>
                            <h1 style="margin-bottom: 0; font-size: 13px; font-weight: bold; color: #8a8c8f;"><?= $title; ?></h1>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="CompareBtn">
                        <button type="button" onclick="letsCompare(this)" class="mainbtn w-100"><i class="fa fa-exchange" aria-hidden="true"></i>
                            Compare <span class="compare_count">0</span></button>
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
            </div>
        </form>
        <div class="row mobile-filter-row">
            <div class="mobile-filter col-12">
                <ul class="label-filter desktop-filter">
                    <h4>Filters:</h4>
                    <?php if (!empty($categoryList)) {
                        foreach ($categoryList as $categoryId) {
                            if ($categoryId == "") continue;
                            $category = reset(array_filter($subCategories, function ($_category) use ($categoryId) {
                                return $_category->subcategory_id == $categoryId;
                            })); ?>
                            <li>
                                <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $category->subcategory_id; ?>" data-catvalName="<?php echo $category->subcategory_name; ?>"><?php echo $category->subcategory_name; ?>
                                    <i class="fa fa-times" aria-hidden="true"></i></a>
                            </li>
                    <?php }
                    } ?>

                    <?php if (!empty($brandList)) {
                        foreach ($brandList as $brandId) {
                            if ($brandId == "") continue;
                            $brand = reset(array_filter($brands, function ($_brand) use ($brandId) {
                                return $_brand->brand_name == $brandId;
                            })); ?>
                            <li>
                                <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $brand->brand_name; ?>" data-catvalName="<?php echo $brand->brand_name; ?>"><?php echo $brand->brand_name; ?>
                                    <i class="fa fa-times" aria-hidden="true"></i></a>
                            </li>
                    <?php }
                    } ?>

                    <li class="remove-all-filter">
                        <a href="<?php echo $current_url; ?>">Remove All Filters </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-12 col-12 px-0">
                <nav class="sidebar" id="accordion-menu">
                    <div class="FiltersMain">
                        <div class="checkBoxFilter discount-range-panel" id="discountFilter">
                            <h3 class="Filthead mb-0">Discount Range</h3>
                            <input type="text" id="discount_range" name="discount_range" class="discount_range" />
                        </div>

                        <div class="checkBoxFilter price-range-panel" id="priceFilter">
                            <h3 class="Filthead mb-0">Price Range</h3>
                            <input type="text" id="range" name="range" class="price_range"/>
                        </div>

                        <div class="checkBoxFilter">
                            <?php if (!empty($categories)) { ?>
                                <h3 class="Filthead">Categories</h3>
                                <?php foreach ($categories as $key => $value) : ?>
                                    <label class="CustomCheck pl-2" data-toggle="collapse" data-target="#category-<?= $value->category_id; ?>"><?= ucwords($value->category_name); ?> (<?= $value->product_count; ?>)</label>
                                    <div id="category-<?= $value->category_id; ?>" class="collapse">
                                        <?php $_subCategories = array_filter($subCategories, function($category) use ($value) {
                                            return $category->category_id == $value->category_id;
                                        });
                                        foreach ($_subCategories as $subCategory) { ?>
                                            <label class="CustomCheck ml-3"><?= ucwords($subCategory->subcategory_name); ?> (<?= $subCategory->product_count; ?>)
                                                <input type="checkbox" <?php echo in_array(($subCategory->subcategory_id), $categoryList) ? 'checked' : ''; ?> name="category" id="categoryID<?= $subCategory->subcategory_id ?>" class="common_selector categories" value="<?= $subCategory->subcategory_id ?>" onclick="getTopFIlter();">
                                                <span class="checkmark"></span>
                                            </label>
                                                <?php } ?>
                                            </div>
                                <?php endforeach ?>
                            <?php } ?>
                        </div>

                        <div class="checkBoxFilter" id="filterBrand">
                            <div id="filter_brand">
                                <div class="show-brand show-more-height">
                                    <?php if (!empty($brands)) { ?>
                                        <h3 class="Filthead">Brands</h3>
                                        <?php foreach ($brands as $key => $value) : ?>
                                            <label class="CustomCheck"><?= ucwords($value->brand_name); ?> (<?= $value->product_count; ?>)
                                                <input type="checkbox" <?= in_array($value->brand_name, $brandList) ? 'checked' : ''; ?> name="brand" id="brandID<?= $value->brand_name ?>" class="common_selector brands" value="<?= $value->brand_name ?>" onclick="getTopFIlter();">
                                                <span class="checkmark"></span>
                                            </label>
                                        <?php endforeach ?>
                                    <?php } ?>
                                </div>
                                <?php if (count($brands) + count($categories) > 5) { ?>
                                    <div class="show-more">Show More</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-lg-10 col-md-8 col-sm-12 col-12">
                <?php if (!empty($categoryList) || !empty($brandList)) { ?>
                    <ul class="label-filter desktop-filter">
                        <h4>Filters:</h4>
                        <?php if (!empty($categoryList)) {
                            foreach ($categoryList as $categoryId) {
                                if ($categoryId == "") continue;
                                $category = reset(array_filter($subCategories, function ($_category) use ($categoryId) {
                                    return $_category->subcategory_id == $categoryId;
                                })); ?>
                                <li>
                                    <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $category->subcategory_id; ?>" data-catvalName="<?php echo $category->subcategory_name; ?>"><?php echo $category->subcategory_name; ?>
                                        <i class="fa fa-times" aria-hidden="true"></i></a>
                                </li>
                        <?php }
                        } ?>

                        <?php if (!empty($brandList)) {
                            foreach ($brandList as $brandId) {
                                if ($brandId == "") continue;
                                $brand = reset(array_filter($brands, function ($_brand) use ($brandId) {
                                    return $_brand->brand_name == $brandId;
                                })); ?>
                                <li>
                                    <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $brand->brand_name; ?>" data-catvalName="<?php echo $brand->brand_name; ?>"><?php echo $brand->brand_name; ?>
                                        <i class="fa fa-times" aria-hidden="true"></i></a>
                                </li>
                        <?php }
                        } ?>

                        <li class="remove-all-filter">
                            <a href="<?php echo $current_url; ?>">Remove All Filters </a>
                        </li>
                    </ul>
                <?php } ?>
                <div class="product-Listing">
                    <div>
                        <div class="row">
                            <?php
                            error_reporting(0);
                            if (!empty($products)) :
                                foreach ($products as $key => $value) {
                            ?>
                                    <div class="col wow fadeInUp">

                                        <div class="product-ListingBox hoverAnimation">
                                            <div class="product-discount-percent">
                                                -<?= round(100 - $value->selling_price / $value->cost_price * 100, 0) ?>%
                                            </div>
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
                                                    <?php if (isset($value->brandimage) && $value->brandimage != 'image' && !empty(trim($value->brandimage))) {
                                                        echo '<img class="div-lazy-loader" data-src="' . $value->brandimage . '" alt="' . $value->brand_name . '">';
                                                    } else {
                                                        echo '<p style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-family: revert;font-weight: 700;font-style: italic;margin-bottom: 0">' . $value->brand_name . '</p>';
                                                    } ?>
                                                </div>

                                                <a href="<?= base_url() ?><?php echo $value->category_slug; ?>/<?php echo $value->slug ?>">
                                                    <h3><?php echo $value->name ?></h3>
                                                </a>
                                                <p <?php echo ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) ? 'style="color:red"' : "" ?>>
                                                    <?php echo floatval($value->selling_price) > 0 ? '&euro;' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price) : ''; ?>
                                                    <?php if ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) { ?>
                                                        <span style='color: black'><del><?php echo '&euro;' . (is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></del></span>
                                                    <?php } ?>
                                                </p>

                                                <div class="product_check">
                                                    <input class="compare-style-for-checkbox" <?php if (in_array($value->product_id, $compare)) {
                                                                                                    echo "checked";
                                                                                                } ?> type="checkbox" id="compare_<?php echo $value->product_id ?>" name="compare" value="<?php echo $value->product_id ?>">+ Compare
                                                </div>
                                            </div>
                                        </div>
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
        </div>
</section>
<?php $this->load->view('frontend/layout/footer'); ?>

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     // Lấy giá trị của ID từ URL
    //     const urlParams = new URLSearchParams(window.location.search);
        
    //     var currentPath = window.location.pathname;
    //     // Kiểm tra nếu đường dẫn chứa "/Fashion" và theo sau bởi một hoặc nhiều số
        
    //     var bigSaleRegex = /\/products\/products_bigsale.*/;
        
    //     // Kiểm tra ID và ẩn/hiện các bộ lọc tương ứng
    //     if (bigSaleRegex.test(currentPath)) {
    //         document.getElementById('discountFilter').classList.add('hide');
    //         // document.getElementById('priceFilter').classList.add('hide');
    //     } else {
    //         document.getElementById('discountFilter').style.display = 'block';
    //         // document.getElementById('priceFilter').style.display = 'block';
    //     }
    // });

    $(".show-more").click(function () {
        if ($(".show-brand").hasClass("show-more-height")) {
            $(this).text("Show Less");
        } else {
            $(this).text("Show More");
        }
        $(".show-brand").toggleClass("show-more-height");
    });

    function extractPrice(price) {
        price = price.replace(' ', "");
        var matches = price.match(/\d+(\.\d+)?/);
        return matches ? matches[0] : null;
    }

    function extractPrice(priceString) {
    return parseFloat(priceString.replace(/[^0-9.-]+/g, ""));
    }

    function getTopFIlter() {
        var categories = [];
        var brands = [];
        var maxDiscount = $('.discount-range-panel .irs-to').text();
        var minDiscount = $('.discount-range-panel .irs-from').text();
        var maxPrice = $('.price-range-panel .irs-to').text();
        var minPrice = $('.price-range-panel .irs-from').text();

        minDiscount = extractPrice(minDiscount);
        maxDiscount = extractPrice(maxDiscount);
        minPrice = extractPrice(minPrice);
        maxPrice = extractPrice(maxPrice);

        var sort_by = $('#sort_by').val();
        var stock = 0;

        $('input[name="is_stock"]:checked').each(function() {
            stock = 1;
        });

        $('input[name="category"]:checked').each(function() {
            categories.push($(this).val());
        });

        $('input[name="brand"]:checked').each(function() {
            brands.push($(this).val());
        });

        let final_url = new URL(location.href);
        final_url.searchParams.set('minDiscount', minDiscount);
        final_url.searchParams.set('maxDiscount', maxDiscount);
        final_url.searchParams.set('minPrice', minPrice);
        final_url.searchParams.set('maxPrice', maxPrice);
        final_url.searchParams.set('In_Stock', stock);
        final_url.searchParams.set('sort_by', sort_by);
        final_url.searchParams.set('categories', categories);
        final_url.searchParams.set('brands', brands);
        location.href = final_url.href;
    
    }

    function setSortByFromURL() {
        let params = new URLSearchParams(window.location.search);
        let sort_by = params.get('sort_by');
        if (sort_by) {
            $('#sort_by').val(sort_by);
        }
    }
    

    $(document).ready(function() {
        setSortByFromURL();
    });
    
    $(document).ready(function() {
        var slider = $('#range');
        slider.ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: <?= $minMaxPrice[3]??0; ?>,
            max: <?= $minMaxPrice[2]??0; ?>,
            from: <?= $minMaxPrice[1]??0; ?>,
            to: <?= $minMaxPrice[0]??0; ?>,
            type: 'double',
            step: 1,
            prefix: "&euro;",
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
            from_min: 50,
            to_max: 100,
            from: 100 - <?= $minMaxPercent[0]??0 ?>,
            to: 100 - <?= $minMaxPercent[1]??0 ?>,
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

        $(document).on('click', '.remveCategoryFromFliter', function(event) {
            event.preventDefault();
            var catId = $(this).attr('data-catvalId');
            $('#categoryID' + catId).prop('checked', false);
            $(this).parent().remove();
            getTopFIlter();
        })

        $(document).on('click', '.removeDiscountFromFilter', function(event) {
            event.preventDefault();
            var disId = $(this).attr('data-disvalId');
            $('#discount_' + disId).prop('checked', false);
            $(this).parent().remove();
            getTopFIlter();
        })
    });
</script>
