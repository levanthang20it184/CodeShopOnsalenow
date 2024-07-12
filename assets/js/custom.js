var pathparts = location.pathname.split('/');
if (location.host == 'localhost') {
    var base_url = location.origin + '/' + pathparts[1].trim('/') + '/';
} else {
    var base_url = location.origin + '/';
}

var ajaxRequest = null;
$('#seaarhbar').keyup(function () {
    var product = $(this).val();
    if (product != '') {
        if (ajaxRequest && ajaxRequest.readyState !== 4) {
            ajaxRequest.abort();
          }
          ajaxRequest = $.ajax({
            url: "/products/search_list",
            method: "POST",
            data: {'product': product},
            success: function (data) {
                if (data) {
                    $('#searched_list').fadeIn();
                    $('#searched_list').html(data);
                } else {
                    $('#searched_list').fadeIn();
                    $('#searched_list').html('<li style="list-style-type: none; color: black; font-size: 17;">Please try another selection</li>');

                }
            }
        })

    } else {
        $('#searched_list').fadeOut();
        $('#searched_list').html("");
    }
});

$(document).on('click', '.SearchItem', function () {
    $('#seaarhbar').val($(this).find('li').attr('pname'));
    $('#searched_list').fadeOut();
});


$("#seaarhbar").focus(function () {
    $("#searched_list").show();
});

$("#seaarhbar").on('blur', function () {
    setTimeout(function () {
        $("#searched_list").hide();
    }, 200)
});


$(document).on("change", ".compare-style-for-checkbox", function (event) {
    event.preventDefault();
    var pid = $(this).val();
    var ischecked = $(this).is(":checked");
    add_compare(pid, ischecked);
})


function add_compare(pid = '', ischecked = 0) {
    action = 'add';
    if (!ischecked) {
        action = 'remove';
    }
    if (pid == '') {
        action = '';
    }

    $.ajax({
        url: "/products/product_compare_data",
        method: "POST",
        data: {action: action, pid: pid},
        success: function (data) {
            if (!data) {
                // alert("You cannot compare more than 3 products at a time");
                $("#compare_" + pid).prop('checked', false);
                return false;
            }
            $('.compare_count').html(data);
        }
    });
}

function letsCompare(obj) {
    url = "/products/product_compare";
    var num = $(obj).find('span').text();
    if (num < 2) {
        alert("Please select at-least 2 products");
        return false;
    }
    window.location.href = url;
}

(function ($) {
    "use strict";


    // Smooth scroll for the navigation and links with .scrollto classes
    var scrolltoOffset = $('#header').outerHeight() - 1;
    $(document).on('click', '.main-nav a, .mobile-nav a, .scrollto', function (e) {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();

                var scrollto = target.offset().top - scrolltoOffset;

                if ($(this).attr("href") == '#header') {
                    scrollto = 0;
                }

                $('html, body').animate({
                    scrollTop: scrollto
                }, 1500, 'easeInOutExpo');

                if ($(this).parents('.main-nav, .mobile-nav').length) {
                    $('.main-nav .active, .mobile-nav .active').removeClass('active');
                    $(this).closest('li').addClass('active');
                }

                if ($('body').hasClass('mobile-nav-active')) {
                    $('body').removeClass('mobile-nav-active');
                    $('.mobile-nav-toggle i').toggleClass('fa-times fa-bars');
                    $('.mobile-nav-overly').fadeOut();
                }
                return false;
            }
        }
    });


    // Mobile Navigation
    if ($('.main-nav').length) {
        var $mobile_nav = $('.main-nav').clone().prop({
            class: 'mobile-nav d-lg-none'
        });
        $('body').append($mobile_nav);
        $('body').prepend('<button aria-label="menu-mobile-btn" type="button" class="mobile-nav-toggle d-lg-none"><i class="fa fa-bars"></i></button>');
        $('body').append('<div class="mobile-nav-overly"></div>');

        $(document).on('click', '.mobile-nav-toggle', function (e) {
            $('body').toggleClass('mobile-nav-active');
            $('.mobile-nav-toggle i').toggleClass('fa-times fa-bars');
            $('.mobile-nav-overly').toggle();
        });

        $(document).on('click', '.mobile-nav .drop-down > a', function (e) {
            e.preventDefault();
            $(this).next().slideToggle(300);
            $(this).parent().toggleClass('active');
        });

        $(document).click(function (e) {
            var container = $(".mobile-nav, .mobile-nav-toggle");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                if ($('body').hasClass('mobile-nav-active')) {
                    $('body').removeClass('mobile-nav-active');
                    $('.mobile-nav-toggle i').toggleClass('fa-times fa-bars');
                    $('.mobile-nav-overly').fadeOut();
                }
            }
        });
    } else if ($(".mobile-nav, .mobile-nav-toggle").length) {
        $(".mobile-nav, .mobile-nav-toggle").hide();
    }

    // Navigation active state on scroll
    var nav_sections = $('section');
    var main_nav = $('.main-nav, .mobile-nav');
    var main_nav_height = $('#header').outerHeight();

    $(window).on('scroll', function () {
        var cur_pos = $(this).scrollTop() + 200;

        nav_sections.each(function () {
            var top = $(this).offset().top - main_nav_height,
                bottom = top + $(this).outerHeight();

            if (cur_pos >= top && cur_pos <= bottom) {
                main_nav.find('li').removeClass('active');
                main_nav.find('a[href="#' + $(this).attr('id') + '"]').parent('li').addClass('active');
            }

            if (cur_pos < 300) {
                $(".nav-menu ul:first li:first").addClass('active');
            }

        });
    });


})(jQuery);

/* $(document).ready(function(){
  add_compare();
  $(".btn.btn-primary").click(function(){
    $(".FiltersMain").toggle();
  });
}); */

$('.nav-btn.nav-slider').on('click', function () {
    $('.overlay').show();
    $('nav').toggleClass("open");
});

$('.overlay').on('click', function () {
    if ($('nav').hasClass('open')) {
        $('nav').removeClass('open');
    }
    $(this).hide();
});