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
                                    <li>
                                        <a href="<?= base_url() ?><?php echo $this->uri->segment(1) ?>"><?php echo $this->uri->segment(1) ?></a>
                                    </li>
                                    <li><span><?php echo str_replace("_", " ", $this->uri->segment(2)) ?></span></li>
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

                        <input type="hidden" id="page" class="page" value="<?= @$page; ?>">


                        <div class="col-lg-2 col-md-2 col-5">
                            <div class="CompareBtn">
                                <div class="In-Stock-checkbox-box">
                                    <input type="checkbox" class="stock is_stock common_selector" name="is_stock"
                                           id="In_Stock" value="">
                                    <label class="radio_btn" for="In_Stock"><span class="checkmark"></span> In
                                        Stock</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-3 col-7">
                            <div class="SortBy">
                                <select class="wide sort_by" name="sort_by" id="sort_by">
                                    <option value="">Sort by Latest</option>
                                    <option value="asc">Low to High</option>
                                    <option value="desc">High to Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- <div class="col-5 hidden-lgBtn pl-0">
                            <div class="FilterBtn">
                                <button class="btn btn-primary mainbtn mobile-Filters">Filter results</button>
                            </div>
                        </div> -->

                    </div>
                    <!-- <div class="container">
                        <div class="row mb-4 filter-top">

                        </div>
                    </div> -->
                    <div class="row mb-4 mt-3 upperSec">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="DetailPageContent pl-0">
                                <div class="DetailHeadDes mt-0">
                                    <?php //echo 'ranu'.$image;die;
                                    ?>
                                    <h2><?= ucwords(@$name); ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-8 col-sm-12 col-12">
                            <div class="DetailPageContent">
                                <div class="DetailHeadDes mt-0">
                                    <p><?= @$description; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-lg-2 col-md-4 col-sm-12 col-12 px-0">
                            <div class="FiltersMain">
                                <div class="checkBoxFilter discount-range-panel">
                                    <h3 class="Filthead mb-0">Discount Range</h3>
                                    <input type="text" id="discount_range" name="discount_range" class="discount_range" />
                                </div>

                                <div class="checkBoxFilter">
                                    <h3 class="Filthead mb-0">Price Range</h3>
                                    <input type="text" id="range" name="range" class="price_range"/>
                                </div>

                                <div class="priceFilter">
                                </div>

                                <div class="checkBoxFilter">
                                    <h3 class="Filthead"><?php echo ucwords($category['name']); ?></h3>
                                    <input type="hidden" name="category_id" id="category_id"
                                           value="<?= @$category_id; ?>">
                                    <input type="hidden" name="subcategory_id" id="subcategory_id"
                                           value="<?= @$subcategory_id; ?>">
                                    <?php

                                    foreach ($subcategories as $key => $value) :

                                        $count = chechProductsBySubCategory($value->id);
                                        if (!$count) {
                                            continue;
                                        }

                                        ?>
                                        <a href="<?php echo base_url(); ?><?php echo $cat_slug; ?>/<?php echo $value->slug; ?>"><label
                                                    class="CustomCheck"><?= $value->name; ?><input type="text"
                                                                                                   name="subcategories"
                                                                                                   parent="<?= @$category['id']; ?>"
                                                                                                   id="subcategories_<?php echo $value->id; ?>"
                                                                                                   class="common_selector subcategories"
                                                                                                   value="<?= $value->id; ?>" <?php if (@$subcategory_id == $value->id) {
                                                    echo "checked disabled";
                                                } ?> onclick="getTopFIlter();"> <span
                                                        class="checkmark"></span>(<?php echo isset($count) ? $count : 0 ?>
                                                )
                                            </label></a>
                                    <?php endforeach ?>
                                </div>

                                <div class="checkBoxFilter" id="filterBrand">
                                    <div class="show-brand show-more-height">
                                        <h3 class="Filthead">Brands</h3>
                                        <input type="text" name="brand" id="brand">
                                        <div id="brand1">
                                            <?php foreach ($brands as $key => $value) {

                                                if (in_array(($value->id), $brandList)) :
                                                    $check = 'checked="checked"';
                                                else : $check = "";
                                                endif;

                                                $count = chechProductsByBrand($category_id, $value->id);

                                                if (!$count) {
                                                    continue;
                                                }
                                                ?>
                                                <label class="CustomCheck"><?php echo $value->alias; ?>
                                                    (<?php echo isset($count) ? $count : 0 ?>)
                                                    <input type="checkbox" name="brands"
                                                           id="brands_<?php echo $value->id; ?>"
                                                           class="common_selector brands"
                                                           value="<?= $value->id ?>" <?php echo $check; //if(isset($_GET['brand'])){ if($value->id == $_GET['brand']){ echo "checked"; }}
                                                    ?> onclick="showBrand(<?php echo $value->id ?>)"
                                                           onclick="getTopFIlter();">
                                                    <span class="checkmark"></span>
                                                </label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="show-more">Show More</div>
                                </div>
                                <div class="checkBoxFilter" id="filter_brand">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-10 col-md-8 col-sm-12 col-12">
                            <div class="row filter_data">
                            </div>
                        </div>
                    </div>
        </section>

    </section>
</form>

<?php $this->load->view('frontend/layout/footer'); ?>

<script src="<?= base_url() ?>assets/js/filter.js"></script>
<script type="text/javascript">
    $(document).on('keyup', '#brand', function () {
        var text = this.value;

        //alert(text)
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'home/getAllBrand' ?>",
            data: {
                text: text
            },
            dataType: "html",
            cache: false,
            success: function (data) {
                //console.log(data);
                //  var totalLength =  data['allbrand'].length;

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


    function showBrand(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'productbrand' ?>",
            data: {
                id: id
            },
            dataType: "json",
            cache: false,
            success: function (data) {
                console.log(data);
                $.each(data['product'], function (index, value) {
                    $('.filter_data').append('<div class="col-md-6"><img class="div-lazy-loader" alt="sub category image" width="50px" data-src="' + value.image + '"><p>' + value.description + '</p></div>')
                });

            }
        });
    }

    $(document).on('click', '.brands', function () {

        var currentRequest = null;

        //alert("hi");

        $('.filter_data').html('<div id="loading" style="" ></div>');

        var filter_by = [];
        var categories = [];
        var subcategories = [];
        var subCat = [
            [],
            []
        ];
        var brands = [];

        var max_price = $('.irs-to').text();
        var min_price = $('.irs-from').text();

        min_price = extractPrice(min_price);
        max_price = extractPrice(max_price);

        $('input[name="brands"]:checked').each(function () {
            brands.push($(this).val());
            console.log(brands);
        });

        if (brands != '') {
            filter_by.push({
                'key': 'brands',
                'value': brands
            });
            console.log(filter_by);
        }

        // alert(brands);

        var sort_by = $('#sort_by').val();
        var page = $("#page").val();
        var stock = $('input[name="is_stock"]:checked').val();

        var params = "<?php echo $param; ?>";
        // alert("hi"+params);
        if (params != '') {

            // alert("hi");
            var url = "<?php echo $current_url; ?>";
            // alert(url);
        } else {
            var url = "<?php echo $current_url; ?>";
            // alert(url);
            // alert("no");

            // console.log(brands);
            // exit();
            window.location.href = url + '?brand=' + brands;
        }

    });

    function extractPrice(price) {
        price = price.replace(' ', "");

        var matches = price.match(/\d+(\.\d+)?/);
        return matches ? matches[0] : null;
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

        $('input[name="categories"]:checked').each(function () {
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
        $('input[name="subcategories"]:checked').each(function (index, data) {
            parent = $(this).attr('parent');

            subCat = [parent, $(this).val()];
            subcategories.push(subCat);
            console.log(subcategories);
            i = i + 1;
        });
        if (subcategories != '') {
            filter_by.push({
                'key': 'sub_categories',
                'parent': parent,
                'value': subcategories
            });
        }

        $('input[name="brands"]:checked').each(function () {
            brands.push($(this).val());
        });
        if (brands != '') {
            filter_by.push({
                'key': 'brands',
                'value': brands
            });
        }


        var currentRequest = null;
        var curl = window.location.pathname;
        currentRequest = $.ajax({
            url: base_url + "products/products_filter_top/",
            method: "POST",
            dataType: "JSON",
            data: {
                url: curl,
                filter_by: filter_by
            },
            beforeSend: function () {
                if (currentRequest != null) {
                    currentRequest.abort();
                }
                $('html, body').animate({
                    scrollTop: 300
                }, 'slow');
            },
            success: function (data) {
                $('.filter-top').html(data.view_html);
                var instance = $("#range").data("ionRangeSlider");

                instance.update({
                    max: data.maxPrice
                });
            }
        });
    }

    $(document).on('click', '.close-filter2', function () {
        var curl = window.location.pathname;
        window.location.replace("https://test.onsalenow.ie" + curl);
    });

    function getFilterClear(id, key) {
        if (key == 'subcat') {

            $("#subcat_" + id).remove();
            var sub = $("#subcategories_" + id).attr("value");
            if (sub == id) {
                $("#subcategories_" + sub).click();
            }
        }

        if (key == 'brand') {

            $("#brand_" + id).remove();
            // $(".brands").prop( "checked", false );
            var brand = $("#brands_" + id).attr("value");
            // alert(brand);
            if (brand == id) {

                $("#brands_" + brand).click();
            }
        }

        if (key == 'price') {
            $("#price_" + id).remove();
        }
    }
</script>
<script>
    $(".show-more").click(function () {
        // alert("show");
        if ($(".show-brand").hasClass("show-more-height")) {
            $(this).text("Show Less");
        } else {
            $(this).text("Show More");
        }

        $(".show-brand").toggleClass("show-more-height");
    });
</script>