<?php $this->load->view('frontend/layout/header'); ?>
<?php header('Access-Control-Allow-Origin: *'); ?>
<?php error_reporting(0); ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
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
                    <div class="row align-items-center">
                        <!-- <div class="col-lg-4 col-md-5   col-12">
          <div class="Breadcrumb">
            <ul class="list-unstyled d-flex">
              <li><a href="#"><?php echo $this->uri->segment(1) ?></a></li>
              <li><span><?php echo str_replace("_", " ", $this->uri->segment(2)) ?></span></li>
            </ul>
          </div>
        </div>
         -->
                        <div class="col-lg-6 col-md-6 col-12">
                            <?php if (!empty($searchkey)) { ?>
                                <div class="SearchCate CompareBtn">

                                    <b>Search by:</b>&nbsp;<span><?php echo $searchkey; ?></span>
                                    <button class="close-filter"><i class="fa fa-times" aria-hidden="true"></i></button>

                                </div><?php } ?>
                        </div>

                        <div class="col-lg-2 col-md-2 col-12 p-15">
                            <div class="CompareBtn">
                                <button type="button" onclick="letsCompare(this)" class="mainbtn w-100"><i class="fa fa-exchange" aria-hidden="true"></i> Compare <span class="compare_count">0</span></button>
                            </div>
                        </div>

                        <input type="hidden" id="page" class="page" value="<?= $page; ?>">


                        <div class="col-lg-2 col-md-2 col-5">
                            <div>
                                <div class="In-Stock-checkbox-box CompareBtn">
                                    <input type="checkbox" <?php echo $_GET['In_Stock'] == 1 ? "checked" : ""; ?> class="stock is_stock common_selector" name="is_stock" id="In_Stock" value="" onclick="setTimeout(getTopFIlter, 500)">
                                    <label class="radio_btn" for="In_Stock"><span class="checkmark"></span> In
                                        Stock</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-3 col-7 pl-0 CompareBtn">
                            <div class="SortBy">
                                <select class="wide sort_by" name="sort_by" id="sort_by" onchange="getTopFIlter();">
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

                    <div class="row m-0">

                        <div class="overlay"></div>
                        <div class="col-lg-2 col-md-4 col-sm-12 col-12 px-0">
                            <nav class="sidebar" id="accordion-menu">
                                <div class="FiltersMain desktop">
                                    <div class="checkBoxFilter discount-range-panel">
                                        <h3 class="Filthead mb-0">Discount Range</h3>
                                        <input type="text" id="discount_range" name="discount_range" class="discount_range" />
                                    </div>

                                    <div class="checkBoxFilter price-range-panel">
                                        <h3 class="Filthead mb-0">Price Range</h3>
                                        <input type="text" id="rangedesktop" name="rangedesktop" class="price_range1" />
                                    </div>

                                    <div class="priceFilter">
                                    </div>

                                    <div class="checkBoxFilter">
                                        <h3 class="Filthead">Categories</h3>
                                        <?php foreach ($category as $key => $value) :
                                            if (!empty($categoryList)) {
                                                if (in_array(($value->id), $categoryList)) :
                                                    $check = 'checked="checked"';
                                                else : $check = "";
                                                endif;
                                            }

                                        ?>
                                            <label class="CustomCheck"><?= ucwords($value->name); ?>
                                                <input type="checkbox" name="categories" id="categoryID<?= $value->id ?>" class="common_selector categories" value="<?= $value->id ?>" <?php echo $check; ?> onclick="getTopFIlter('desktop');">

                                                <span class="checkmark"></span>(<?php echo isset($value->prd_cnt) ? $value->prd_cnt : 0 ?>)
                                                <span class="category_<?= $value->id; ?> childClass"></span>
                                            </label>
                                        <?php endforeach ?>
                                    </div>
                                    <div class="checkBoxFilter">
                                        <div>
                                            <div class="show-brand show-more-height">
                                                <h3 class="Filthead">Brands</h3>
                                                <?php if (count($brands) > 5) { ?>
                                                    <input type="text" style="margin-bottom: 15px" name="brand" id="brand">
                                                <?php } ?>
                                                <div>
                                                    <?php foreach ($brands as $key => $value) {
                                                        if (in_array(($value->id), $brandList)) :
                                                            $check = 'checked="checked"';
                                                        else : $check = "";
                                                        endif;
                                                        $count = chechProductsByBrandSearch($value->id, $searchkey, floor($minmaxprice[0]->min_price), ceil($minmaxprice[0]->max_price));
                                                    ?>
                                                        <label class="CustomCheck"><?php echo $value->brand_name; ?>(<?php echo isset($count) ? $count : 0 ?>)
                                                            <input type="checkbox" name="brands" id="brands_<?php echo $value->id; ?>" class="common_selector brands" value="<?= $value->id ?>" <?php echo $check; ?> onclick="getTopFIlter('desktop');">
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
                                </div>
                            </nav>
                        </div>

                        <div class="col-lg-10 col-md-8 col-sm-12 col-12">
                            <?php if ($brandListData[0] > 0 || $categoryListData[0] > 0 || (!empty($filter_min_price) && !empty($filter_max_price))) { ?>
                                <?php if ((count($brandListData) > 0 || count($categoryListData) > 0)) { ?>
                                    <ul class="label-filter">
                                        <h4>Filters:</h4>
                                        <?php if ($categoryListData[0] > 0) {
                                            foreach ($categoryListData as $categoryval) { ?>
                                                <li>
                                                    <a href="#" class="remveCategoryFromFliter" data-catvalId="<?php echo $categoryval->id; ?>"><?php echo $categoryval->name; ?>
                                                        <i class="fa fa-times" aria-hidden="true"></i></a>
                                                </li>
                                        <?php }
                                        } ?>
                                        <?php if ($brandListData[0] > 0) {
                                            foreach ($brandListData as $brandval) { ?>
                                                <li>
                                                    <a href="#" class="remveBrandFromFliter" data-brandvalId="<?php echo $brandval->id; ?>"><?php echo $brandval->alias; ?>
                                                        <i class="fa fa-times" aria-hidden="true"></i></a>
                                                </li>

                                        <?php }
                                        } ?>

                                        <li class="remove-all-filter">
                                            <a href="<?php echo $current_url . '?search=' . $searchkey; ?>"> Remove All Filters </a>
                                        </li>
                                    </ul>
                                <?php } ?>

                            <?php } ?>
                            <div class="row" <?php echo count($result) == 0 ? '' : '' ?>>
                                <?php if (count($result) > 0) : foreach ($result as $key => $value) { ?>
                                        <div class="col wow fadeInUp">
                                            <div class="product-ListingBox hoverAnimation">
                                                <a href="<?= base_url() ?><?php echo $value->category_slug; ?>/<?php echo $value->slug; ?>">
                                                    <div class="product-image">
                                                        <?php if ($value->image != 'https://images2.productserve.com/noimage.gif' && $value->image != 'image' && $value->image != '') { ?>
                                                            <img class="div-lazy-loader" alt="<?php echo $value->name; ?>" data-src="<?php echo $value->image; ?>">
                                                        <?php } else { ?>
                                                            <img class="div-lazy-loader" alt="product default image" data-src="https://test.onsalenow.ie/assets/img/no-image.png">
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

                                                    <div class="product_check">
                                                        <input class="compare-style-for-checkbox" <?php if (in_array($value->id, $compare)) {
                                                                                                        echo "checked";
                                                                                                    } ?> type="checkbox" id="compare_<?php echo $value->id ?>" name="compare" value="<?php echo $value->id ?>">+ Compare
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    <?php } ?>
                                    <div class="col-12 justify-content-end m-0 d-flex">
                                        <nav style="margin: auto" aria-label="Page navigation example">
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
                            </div>
                        </div>
                    </div>
        </section>
    </section>
</form>

<?php $this->load->view('frontend/layout/footer'); ?>

<script type="text/javascript">
    $(document).on('keyup', '#brand', function() {
        var text = this.value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'home/getAllBrand' ?>",
            data: {
                text: text
            },
            dataType: "html",
            cache: false,
            success: function(data) {
                if (data) {
                    $('#filter_brand').fadeIn();
                    $('#filter_brand').html(data);
                    $(".brand1").css("display", "none");
                } else {
                    $('#filter_brand').fadeIn();
                    $(".brand1").css("display", "none");
                }
            }
        });
    });

    function extractPrice(price) {
        price = price.replace(' ', "");

        var matches = price.match(/\d+(\.\d+)?/);
        return matches ? matches[0] : null;
    }
    // Hàm để trích xuất giá trị từ chuỗi giá
    function extractPrice(priceString) {
        return parseFloat(priceString.replace(/[^0-9.-]+/g, ""));
    }
    function getTopFIlter(abc, isRemovePrice = false) {
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

        if (isRemovePrice) {
            min_price = null;
            max_price = null;
        }

        var maxDiscount = $('.discount-range-panel .irs-to').text();
        var minDiscount = $('.discount-range-panel .irs-from').text();

        minDiscount = extractPrice(minDiscount);
        maxDiscount = extractPrice(maxDiscount);

        var sort_by = $('#sort_by').val();

        $('input[name="brands"]:checked').each(function() {
            brands.push($(this).val());
        });

        if (brands != '') {
            filter_by.push({
                'key': 'brands',
                'value': brands
            });
        }

        var params = "<?php echo $param; ?>";

        if (params != '') {
            var url = "<?php echo $current_url; ?>";
        } else {
            var last_uri_seg = "<?php echo $last_uri_seg; ?>";
            var url = "<?php echo $current_url; ?>";
            var subcategory_id = "<?php echo $subcategory_id; ?>";

            // categories
            $('input[name="is_stock"]:checked').each(function() {
                stock = 1;
            });

            $('input[name="categories"]:checked').each(function() {
                categories.push($(this).val());
            });

            const searchKey = `<?php echo $searchkey; ?>`;


            var last = "<?php echo base_url() . $last_uri_seg . '/' . '1'; ?>";
            if (subcategory_id != '') {
                window.location.href = url + '?category=' + categories + '&subcategory=' + subcategory_id + '&brand=' + brands + '&min_price=' + min_price + '&max_price=' + max_price + '&sort_by=' + sort_by + '&In_Stock=' + stock + '&search=' + searchKey;
            } else {
                if (last_uri_seg != '') {
                    window.location.href = last + '?category=' + categories + '&subcategory=' + subcategory_id + '&brand=' + brands + '&from_discount=' + minDiscount + '&to_discount=' + maxDiscount + '&min_price=' + min_price + '&max_price=' + max_price + '&sort_by=' + sort_by + '&In_Stock=' + stock + '&search=' + searchKey;
                } else {
                    window.location.href = url + '?category=' + categories + '&subcategory=' + subcategory_id + '&brand=' + brands + '&from_discount=' + minDiscount + '&to_discount=' + maxDiscount + '&min_price=' + min_price + '&max_price=' + max_price + '&sort_by=' + sort_by + '&In_Stock=' + stock + '&search=' + searchKey;
                }
            }
        }
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

    $(".show-more").click(function() {
        if ($(".show-brand").hasClass("show-more-height")) {
            $(this).text("Show Less");
        } else {
            $(this).text("Show More");
        }

        $(".show-brand").toggleClass("show-more-height");
    });

    $(document).ready(function() {
        var slider = $('#rangedesktop');

        var minprice11 = "<?php echo $minmaxprice[0]->min_price; ?>";
        var maxprice11 = "<?php echo $minmaxprice[0]->max_price; ?>";

        var minpricedefault = parseFloat(minprice11.replace(" ", ""));
        var maxpricedefault = parseFloat(maxprice11.replace(" ", ""));

        if (minpricedefault == maxpricedefault)
            minpricedefault = 1;

        var minprice111 = "<?php echo $filter_min_price; ?>";
        var maxprice111 = "<?php echo $filter_max_price; ?>";

        var minprice1 = minprice111.replace(" ", "");
        var maxprice1 = maxprice111.replace(" ", "");

        if (minprice1 != '') {
            let minprice2 = minprice1.replace("&euro;", "");
            var minprice = parseFloat(minprice2);
        }

        if (maxprice1 != '') {
            let maxprice2 = maxprice1.replace("&euro;", "");
            var maxprice = parseFloat(maxprice2);
        }

        let from_k = Math.floor(minpricedefault) > Math.floor(minprice) ? Math.floor(minpricedefault) : Math.floor(minprice);
        let from_to = Math.ceil(maxpricedefault) < Math.ceil(maxprice) ? Math.ceil(maxpricedefault) : Math.ceil(maxprice);

        if (isNaN(minpricedefault)) {
            minpricedefault = 0;
        }
        if (isNaN(maxpricedefault)) {
            maxpricedefault = 0;
        }
        
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
            onFinish: function(data) {
                getTopFIlter('desktop');
            },
        });

        var slider = $('#discount_range');
        slider.ionRangeSlider({
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
                if (data.from_percent !== Math.floor(data.to_percent)) {
                    getTopFIlter();
                }
            },
        });

        $(document).on('submit', 'form#filterForm', function(ev) {
            ev.preventDefault();
            getTopFIlter('desktop');
        })
    });

    $(document).on('click', '.remveBrandFromFliter', function(event) {
        event.preventDefault();
        var brandId = $(this).attr('data-brandvalId')
        $('#brands_' + brandId).prop('checked', false);
        $(this).parent().remove();
        getTopFIlter('desktop');

    })

    $(document).on('click', '.removeDiscountFromFilter', function(event) {
        event.preventDefault();

        var disId = $('.removeDiscountFromFilter').attr('data-disvalId');
        $('#discount_' + disId).removeAttr('checked');


        $(this).parent().remove();
        getTopFIlter('desktop');

    })
    $(document).on('click', '.remvePriceFromFliter', function(event) {
        event.preventDefault();
        var disId = $('.removeDiscountFromFilter').attr('data-disvalId');

        $('#discount_' + disId).removeAttr('checked');
        $(this).parent().remove();
        getTopFIlter('desktop', true);
    })

    $(document).on('click', '.remveCategoryFromFliter', function(event) {
        event.preventDefault();
        var catId = $(this).attr('data-catvalId')
        $('#categoryID' + catId).prop('checked', false);
        $(this).parent().remove();
        getTopFIlter();
    })
</script>