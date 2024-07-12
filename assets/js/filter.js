var pathparts = location.pathname.split('/');
if (location.host == 'localhost') {
    var base_url = location.origin + '/' + pathparts[1].trim('/') + '/';
} else {
    var base_url = location.origin + '/';
}

$(document).ready(function () {

    var slider = $('#range');
    slider.ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 10000,
        from: '0',
        to: '10000',
        type: 'double',
        step: 10,
        prefix: "€",
        grid: true,
        onFinish: function (data) {
            filters();
        },
    });

    filters();

    $(".categories, .subcategories, .brands, .discount-input, .stock, .sort_by").on("change", function () {
        $("#page").val(1);
        filters();
    });

    $(document).on('submit', 'form#filterForm', function (ev) {
        ev.preventDefault();
        filters();
    })

    /*** get page number on click ***/
    $(document).on('click', '.page_number', function (ev) {
        ev.preventDefault();
        filters();
    });

    /*** get page number on click ***/
    $(document).on('click', '.getPage', function (ev) {
        ev.preventDefault();
        var page = $(this).find('a').attr('data-ci-pagination-page');
        $("#page").val(page);
        filters(page);
    });


    function filters(page, sort_by, sidebar = 0) {

        var filter_by = [];
        var discount = [];
        var categories = [];
        var subcategories = [];
        var subCat = [[], []];
        var brands = [];

        var max_price = $('.irs-to').text();
        var min_price = $('.irs-from').text();


        /* get and store selected discount filters in discount vriable */
        $('input[name="discount"]:checked').each(function () {
            // alert($(this).val());
            discount.push($(this).val());
        });
        if (discount != '') {
            filter_by.push({'key': 'discount', 'value': discount});
        }


        /* get and store selected categories filters in categories vriable */
        $('input[name="categories"]:checked').each(function () {
            categories.push($(this).val());
        });
        if (categories != '') {
            filter_by.push({'key': 'categories', 'value': categories});
        } else {
            categories.push($("#category_id").val());
            filter_by.push({'key': 'categories', 'value': categories});
        }

        /* get and store selected subcategories filters in categories vriable */
        var i = 0;
        $('input[name="subcategories"]:checked').each(function (index, data) {
            parent = $(this).attr('parent');

            subCat = [parent, $(this).val()];
            subcategories.push(subCat);
            //  console.log(subcategories);
            i = i + 1;
        });
        if (subcategories != '') {
            filter_by.push({'key': 'sub_categories', 'parent': parent, 'value': subcategories});
        }

        /* get and store selected brands filters in brands vriable */
        $('input[name="brands"]:checked').each(function () {
            console.log("hlo");
            brands.push($(this).val());
        });
        if (brands != '') {
            filter_by.push({'key': 'brands', 'value': brands});
        }


        filter_data(page, filter_by, sort_by, max_price, min_price);
        updateSidebar(page, filter_by, sort_by, max_price, min_price);
    }


    function filter_data(page = 1, filter_by = [], sort_by = '', max_price = 200, min_price = 0) {

        var currentRequest = null;
        $('.filter_data').html('<div id="loading" style="" ></div>');
        var max_price = max_price;
        var min_price = min_price;
        var stock = $('input[name="is_stock"]:checked').val();
        var page = $("#page").val();
        var sort_by = $('#sort_by').val();
        var searchkey = $('#searchdata').val();
        var curl = window.location.pathname;
        currentRequest = $.ajax({
            url: base_url + "products/products_ajax/" + page,
            method: "POST",
            dataType: "JSON",
            data: {
                url: curl,
                min_price: min_price,
                max_price: max_price,
                sort_by: sort_by,
                filter_by: filter_by,
                page: page,
                stock: stock,
                searchkey: searchkey
            },
            beforeSend: function () {
                if (currentRequest != null) {
                    currentRequest.abort();
                }
                $('html, body').animate({scrollTop: 300}, 'slow');
            },
            success: function (data) {
                $('.filter_data').html(data.view_html);
                var instance = $("#range").data("ionRangeSlider");

                instance.update({
                    max: data.maxPrice
                });
            }
        });

    }


    function updateSidebar(page = 1, filter_by = [], sort_by = '', max_price = 200, min_price = 0) {
        //    alert("hi");
        var currentRequest = null;
        var searchkey = $('#searchdata').val();
        var brands = '<h3 class="Filthead">Brand</h3><input type="text" name="brand" id="brand"><div class ="brand1">';
        currentRequest = $.ajax({
            url: base_url + "products/products_sidebar/",
            method: "POST",
            dataType: "JSON",
            data: {
                min_price: min_price,
                max_price: max_price,
                sort_by: sort_by,
                filter_by: filter_by,
                page: page,
                searchkey: searchkey
            },
            beforeSend: function () {
                if (currentRequest != null) {
                    currentRequest.abort();
                }
            },
            success: function (data) {
                // console.log(data)
                $("#filterBrand").css("display", "block");
                $("#filterBrand").html('');


                data.result.forEach(element => {

                    brands += '<label class="CustomCheck">' + element.brand_name + ' (' + element.totalProduct + ')<input type="checkbox" name="brands" class="common_selector brands" id="brands_' + element.id + '" value="' + element.id + '" onclick="getTopFIlter();"><span class="checkmark"></span></label>';

                    $("#filterBrand").html(brands);
                });

                brands += '</div>';
            }
        });
    }

});

