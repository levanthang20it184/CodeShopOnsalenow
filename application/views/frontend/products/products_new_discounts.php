<?php $this->load->view('frontend/layout/header'); ?>

<section class="theme-padding">
    <div class="container">
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
                                return $_category->id === $categoryId;
                            }));?>
                            <li>
                                <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $category->id; ?>" data-catvalName="<?php echo $category->name; ?>"><?php echo $category->name; ?>
                                    <i class="fa fa-times" aria-hidden="true"></i></a>
                            </li>
                    <?php }
                    } ?>

                    <?php if (!empty($categoryList)) {
                        foreach ($brandList as $brandId) {
                            if ($brandId == "") continue;
                            $brand = reset(array_filter($brands, function ($_brand) use ($brandId) {
                                return $_brand->id === $brandId;
                            }));?>
                            <li>
                                <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $brand->id; ?>" data-catvalName="<?php echo $brand->brand_name; ?>"><?php echo $brand->brand_name; ?>
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
    </div>
    <div class="container ListingPage">
        <div class="row">
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

                        <div class="checkBoxFilter">
                            <?php if (!empty($categories)) { ?>
                                <h3 class="Filthead">Categories</h3>
                                <?php
                                foreach ($categories as $key => $value) :
                                ?>
                                    <label class="CustomCheck pl-2" data-toggle="collapse" data-target="#category-<?= $value->id; ?>"><?= ucwords($value->name); ?> (<?= $value->product_count; ?>)</label>
                                    <div id="category-<?= $value->id; ?>" class="collapse <?= $value->name != "Fashion"?"":"pl-2"; ?>">
                                        <?php
                                            $_subCategories = [];
                                            foreach ($subCategories as $category) {
                                                if ($category->category_id == $value->id) {
                                                    $_subCategories[] = $category;
                                                }
                                            }
                                            if ($value->name != "Fashion") {
                                                foreach ($_subCategories as $subCategory) {
                                        ?>
                                            <label class="CustomCheck ml-3"><?= ucwords($subCategory->name); ?> (<?= $subCategory->product_count; ?>)
                                                <input type="checkbox" <?php echo in_array(($subCategory->id), $categoryList)?'checked':''; ?> name="category" data-parent="<?= $value->category_id ?>" id="categoryID<?= $subCategory->id ?>" class="common_selector categories" value="<?= $subCategory->id ?>" onclick="getTopFIlter();">
                                                <span class="checkmark"></span>
                                                <span class="category_<?= $subCategory->id; ?> childClass"></span>
                                            </label>
                                        <?php } }
                                        else { 
                                            $subcats = ["Men's", "Women's", "Unisex"];
                                            $count = 0;
                                            foreach ($subcats as $subcat) {
                                                $product_count = 0;
                                                foreach ($_subCategories as $subCategory) { 
                                                    if (strpos($subCategory->name, $subcat) !== false) {
                                                        $product_count += $subCategory->product_count;
                                                    }
                                                }
                                        ?>
                                            <label class="CustomCheck pl-2" data-toggle="collapse" data-target="#subcategory-<?= $count;?>"><?= $subcat; ?> (<?= $product_count; ?>)</label>                                            
                                            <div id="subcategory-<?= $count;?>" class="collapse">
                                                <?php foreach ($_subCategories as $subCategory) { 
                                                    if (strpos($subCategory->name, $subcat) !== false) { ?>
                                                    <label class="CustomCheck ml-3"><?= ucwords($subCategory->name); ?> (<?= $subCategory->product_count; ?>)
                                                        <input type="checkbox" <?php echo in_array(($subCategory->id), $categoryList)?'checked':''; ?> name="category" data-parent="<?= $value->category_id ?>" id="categoryID<?= $subCategory->id ?>" class="common_selector categories" value="<?= $subCategory->id ?>" onclick="getTopFIlter();">
                                                        <span class="checkmark"></span>
                                                        <span class="category_<?= $subCategory->id; ?> childClass"></span>
                                                    </label>
                                                    <?php } 
                                                        $count++;?>
                                                <?php } ?>
                                            </div>
                                        <?php } } ?>
                                    </div>

                                <?php endforeach ?>
                            <?php } ?>
                        </div>

                        <div class="checkBoxFilter" id="filterBrand">
                            <div id="filter_brand">
                                <div class="show-brand show-more-height">
                                    <?php if (!empty($brands)) { ?>
                                        <h3 class="Filthead">Brands</h3>
                                        <?php
                                        foreach ($brands as $key => $value) :
                                        ?>
                                            <label class="CustomCheck"><?= ucwords($value->brand_name); ?> (<?= $value->product_count; ?>)
                                                <input type="checkbox" <?= in_array($value->id, $brandList)?'checked':''; ?> name="brand" data-parent="<?= $value->category_id ?>" id="categoryID<?= $value->id ?>" class="common_selector categories" value="<?= $value->id ?>" onclick="getTopFIlter();">
                                                <span class="checkmark"></span>
                                                <span class="category_<?= $value->id; ?> childClass"></span>
                                            </label>

                                        <?php endforeach ?>
                                    <?php } ?>
                                </div>
                                <?php if (count($brands) > 5) { ?>
                                    <div class="show-more">Show More</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-lg-10 col-md-8 col-sm-12 col-12">
                <?php if ((!empty($categoryList) && $categoryList[0]!='') || (!empty($brandList) && $brandList[0] != '')) { ?>
                    <ul class="label-filter desktop-filter">
                        <h4>Filters:</h4>
                        <?php if (!empty($categoryList)) {
                            foreach ($categoryList as $categoryId) {
                                if ($categoryId == "") continue;
                                $category = reset(array_filter($subCategories, function ($_category) use ($categoryId) {
                                    return $_category->id === $categoryId;
                                }));?>
                                <li>
                                    <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $category->id; ?>" data-catvalName="<?php echo $category->name; ?>"><?php echo $category->name; ?>
                                        <i class="fa fa-times" aria-hidden="true"></i></a>
                                </li>
                        <?php }
                        } ?>

                        <?php if (!empty($categoryList)) {
                            foreach ($brandList as $brandId) {
                                if ($brandId == "") continue;
                                $brand = reset(array_filter($brands, function ($_brand) use ($brandId) {
                                    return $_brand->id === $brandId;
                                }));?>
                                <li>
                                    <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $brand->id; ?>" data-catvalName="<?php echo $brand->brand_name; ?>"><?php echo $brand->brand_name; ?>
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
                                    $priceDrop = getPriceDrop($value->product_id);
                            ?>
                                    <div class="col wow fadeInUp">
                                        <div class="product-ListingBox hoverAnimation">
                                            <div class="product-discount-percent">
                                                -<?= round(100 - $value->selling_price / $value->cost_price * 100, 0) ?>%
                                            </div>
                                            <a href="<?= base_url() ?><?php echo $value->category_slug; ?>/<?php echo $value->slug; ?>">
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
                                                    <?php echo floatval($value->selling_price) > 0 ? '&euro;' . (is_numeric($value->selling_price) && floor($value->selling_price) == $value->selling_price ? intval($value->selling_price) : $value->selling_price) : ''; ?>
                                                    <?php
                                                    if ($value->selling_price != $value->cost_price && floatval($value->cost_price) > 0) { ?>
                                                        <span style='color: black'><del><?php echo '&euro;' . (is_numeric($value->cost_price) && floor($value->cost_price) == $value->cost_price ? intval($value->cost_price) : $value->cost_price); ?></del></span>
                                                    <?php } ?>
                                                </p>
                                                <?php if ($priceDrop != "") { ?>
                                                    <p class="text-danger"><i>Price Drop On : <?= date('d/m/Y', strtotime($priceDrop)); ?></i></p>
                                                <?php } ?>

                                                <div class="product_check">
                                                    <input class="compare-style-for-checkbox" <?php if (in_array($value->id, $compare)) {
                                                                                                    echo "checked";
                                                                                                } ?> type="checkbox" id="compare_<?php echo $value->id ?>" name="compare" value="<?php echo $value->id ?>">+ Compare
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
    function extractPrice(price) {
        price = price.replace(' ', "");

        var matches = price.match(/\d+(\.\d+)?/);
        return matches ? matches[0] : null;
    }

    $(".show-more").click(function () {

        if ($(".show-brand").hasClass("show-more-height")) {
            $(this).text("Show Less");
        } else {
            $(this).text("Show More");
        }

        $(".show-brand").toggleClass("show-more-height");
    });
    // Hàm để trích xuất giá trị từ chuỗi giá
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

    // Hàm để đặt lại giá trị của phần tử select khi trang được tải lại
    function setSortByFromURL() {
        let params = new URLSearchParams(window.location.search);
        let sort_by = params.get('sort_by');
        if (sort_by) {
            $('#sort_by').val(sort_by);
        }
    }

    // Hàm để đặt lại giá trị của thanh trượt giá khi trang được tải lại
    function setPriceRangeFromURL(slider) {
        let params = new URLSearchParams(window.location.search);
        let minPrice = params.get('minPrice');
        let maxPrice = params.get('maxPrice');

        if (minPrice !== null && maxPrice !== null) {
            slider.update({
                from: minPrice,
                to: maxPrice
            });
        }
    }
    // Hàm để đặt lại giá trị của thanh trượt giảm giá khi trang được tải lại
    function setDiscountRangeFromURL(slider) {
        let params = new URLSearchParams(window.location.search);
        let minDiscount = params.get('minDiscount');
        let maxDiscount = params.get('maxDiscount');

        if (minDiscount !== null && maxDiscount !== null) {
            slider.update({
                from: minDiscount,
                to: maxDiscount
            });
        }
    }

    $(document).ready(function() {
        setSortByFromURL(); // Đặt lại giá trị của phần tử select khi trang được tải lại

        // Khởi tạo thanh trượt giá
        var priceSlider = $('#range').ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: <?= $fromToDiscount[5] ?? 0; ?>,
            max: <?= $fromToDiscount[4] ?? 0; ?>,
            from: <?= $fromToDiscount[3] ?? 0; ?>,
            to: <?= $fromToDiscount[2] ?? 0; ?>,
            type: 'double',
            step: 1,
            prefix: "&euro;",
            grid: true,
            onFinish: function(data) {
                getTopFIlter();
            },
        }).data("ionRangeSlider");
        // alert(<?= $fromToDiscount[5] ?? 0; ?>+"max"+<?= $fromToDiscount[4] ?? 0; ?>);
        setPriceRangeFromURL(priceSlider); // Đặt lại giá trị của thanh trượt giá khi trang được tải lại
        

        // Khởi tạo thanh trượt giảm giá
        var discountSlider = $('#discount_range').ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: 0,
            max: 100,
            max_interval: Math.ceil(parseFloat('<?php echo $discount->percent_discount; ?>')) == 0 ? 0.1 : Math.ceil(parseFloat('<?php echo $discount->percent_discount; ?>')),
            from: parseInt('<?php echo 100 - $fromToDiscount[0]; ?>'),
            to: Math.ceil(parseFloat('<?php echo 100 - $fromToDiscount[1]; ?>')),
            type: 'double',
            step: 1,
            postfix: "%",
            grid: true,
            onFinish: function(data) {
                if (data.from_percent !== Math.floor(data.to_percent)) {
                    getTopFIlter();
                }
            },
        }).data("ionRangeSlider");
        setDiscountRangeFromURL(discountSlider); // Đặt lại giá trị của thanh trượt giảm giá khi trang được tải lại

        $(document).on('submit', 'form#filterForm', function(ev) {
            ev.preventDefault();
            getTopFIlter();
            setSortByFromURL();
        });

        $(document).on('click', '.remveCategoryFromFliter', function(event) {
            event.preventDefault();
            var catId = $(this).attr('data-catvalId');
            $('#categoryID' + catId).prop('checked', false);
            $(this).parent().remove();
            getTopFIlter();
        });

        $(document).on('click', '.removeDiscountFromFilter', function(event) {
            event.preventDefault();
            var disId = $(this).attr('data-disvalId');
            $('#discount_' + disId).prop('checked', false);
            $(this).parent().remove();
            getTopFIlter();
        });
    });
</script>