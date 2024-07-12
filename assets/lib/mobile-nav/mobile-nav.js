(function ($) {

    "use strict";


    // Mobile Navigation

    if ($('.main-nav').length) {

        var $mobile_nav = $('.main-nav').clone().prop({

            class: 'mobile-nav d-lg-none'

        });

        $('header').append($mobile_nav);

        $('header').prepend('<button type="button" aria-label="menu-mobile-btn" class="mobile-nav-toggle d-lg-none"><i class="fa fa-bars"></i></button>');

        $('header').append('<div class="mobile-nav-overly"></div>');


        $(document).on('click', '.mobile-nav-toggle', function (e) {

            $('header').toggleClass('mobile-nav-active');

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

                if ($('header').hasClass('mobile-nav-active')) {

                    $('header').removeClass('mobile-nav-active');

                    $('.mobile-nav-toggle i').toggleClass('fa-times fa-bars');

                    $('.mobile-nav-overly').fadeOut();

                }

            }

        });

    } else if ($(".mobile-nav, .mobile-nav-toggle").length) {

        $(".mobile-nav, .mobile-nav-toggle").hide();

    }


})(jQuery);

