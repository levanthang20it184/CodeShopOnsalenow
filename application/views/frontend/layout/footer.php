<?php
$logo_data = web_logo();
?>

<footer class="footer space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="footer-newsletter">
                    <div class="footer-heading">
                        <h3>SIGN UP TO OUR NEWSLETTER TO RECEIVE ADVANCE NOTIFICATION OF MAJOR SALES EVENTS</h3>
                    </div>
                    <!-- <div class="footer-newsletter-box">
                        <form class="Newsletter-form-box" method="post" action="<?php echo base_url('home/add_newsletter') ?>">
                            <input type="email" required placeholder="Enter email address*" id="subscriber_email" name="email" class="Newsletter-form-input">
                            <button id="subscribe" class="Newsletter-form-button" aria-label="subcribe-button"><img class="div-lazy-loader" data-src="<?= base_url() ?>assets/images/right.png" width="10" alt="right-image"></button>
                        </form>
                    </div> -->
                    <div class="footer-newsletter-box">
                        <form class="Newsletter-form-box" method="post" action="<?php echo base_url('home/add_newsletter'); ?>">
                            <div class="input-wrapper">
                                <input type="email" required id="subscriber_email" name="email" class="Newsletter-form-input" placeholder=" ">
                                <label for="subscriber_email" class="placeholder-label">Enter email address<span class="red-star">*</span></label>
                            </div>
                            <button id="subscribe" class="Newsletter-form-button" aria-label="subscribe-button">
                                <img class="div-lazy-loader" data-src="<?= base_url() ?>assets/images/right.png" width="10" alt="right-image">
                            </button>
                        </form>
                    </div>
                    <div class="footer-newsletter-soical">
                        <h4>Social Media</h4>
                        <div class="footer_social">
                            <ul>
                                <!-- <li class="facebook">
                                    <a href="<?= fetch_data('ci_general_settings', ['id', 1], 'facebook_link'); ?>"
                                       target="_blank"><i class="fa fa-facebook"> </i></a>
                                </li>
                                <li class="twitter">
                                    <a href="<?= fetch_data('ci_general_settings', ['id', 1], 'twitter_link'); ?>"
                                       target="_blank"><i class="fa fa-twitter"> </i></a>
                                </li>
                                <li class="youtube">
                                    <a href="<?= fetch_data('ci_general_settings', ['id', 1], 'youtube_link'); ?>"
                                       target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                                </li> -->
                                <li class="google-plus">
                                    <button style="border: 0; color: white; background: transparent;" aria-label="instagram" onclick="window.open('<?= fetch_data('ci_admin', ['id', 1], 'insta'); ?>')">
                                        <i class="fa fa-instagram"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                        <div class="border_right">
                            <div class="footer_links padd">
                                <h4>Menu Links</h4>
                                <ul>
                                    <li><a href="<?php echo base_url() ?>">Home</a></li>
                                    <li><a href="<?php echo base_url('pages/about-us') ?>">About Us</a></li>
                                    <li><a href="<?php echo base_url('products/products_list') ?>">Top 50 Deals</a></li>
                                    <!-- <li><a href="<?php // echo base_url('pages/salesalert')
                                                        ?>">My Sales Alerts</a></li> -->
                                    <li onclick="letsCompare(this)"><button class="home-menu-btn2" href="javascript:void(0);">Compare Products</button>
                                        <span class="compare_count">0</span>
                                    </li>
                                    <!-- <button type="button" onclick="letsCompare(this)" class="mainbtn w-100"><i class="fa fa-exchange" aria-hidden="true"></i> Compare <span class="compare_count">0</span></button> -->
                                    <li><a href="<?php echo base_url('contact_us') ?>">Contact Us</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5">
                        <div class="border_right">
                            <div class="footer_links padd">
                                <h4>Top Categories</h4>
                                <div class="row">
                                    <?php
                                    foreach ($topcategory as $key => $value) {
                                    ?>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                            <ul>
                                                <li>
                                                    <a href="<?= base_url($value->slug) ?>"><?php echo $value->name ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                        <div class="footer_links padd">
                            <h4>Contact Us</h4>
                            <p><a href="mailTo:enquiries@onsalenow.ie"><i class="fa fa-envelope" aria-hidden="true"></i>
                                    enquiries@onsalenow.ie</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="copyright">
                    <div class="copyright_text">
                        <p><?php echo $logo_data->copyright ?>                             <?php
                            $terms = $this->db->where('slug', 'terms-and-conditions')->get('cms_management')->row();
                            if (isset($terms) && $terms->status == 1) {
                            ?>
                                <a href="<?php echo base_url('pages/terms-and-conditions') ?>">Terms & Conditions</a>
                            <?php } else {
                                echo "Terms & Conditions";
                            } ?>


                            <?php
                            $policyData = $this->db->where('slug', 'privacy-policy')->get('cms_management')->row();
                            if (isset($policyData) && $policyData->status == 1) {
                            ?>
                                | <a href="<?php echo base_url('pages/privacy-policy') ?>">Privacy Policy</a>
                            <?php } else {
                                echo "| Privacy Policy";
                            } ?>


                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</footer>
<style>
        .input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .Newsletter-form-input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .placeholder-label {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: grey; 
            pointer-events: none;
            transition: opacity 0.2s, top 0.2s;
            font-size: 16px;
        }

        .red-star {
            color: red;
        }

        .Newsletter-form-input:focus + .placeholder-label,
        .Newsletter-form-input:not(:placeholder-shown) + .placeholder-label {
            opacity: 0;
        }
    </style>
     <script>
        function handleFocus() {
            var emailInput = document.getElementById("subscriber_email");
            var placeholderLabel = document.querySelector(".placeholder-label");

            emailInput.addEventListener("input", function() {
                if (emailInput.value) {
                    placeholderLabel.style.opacity = '0';
                } else {
                    placeholderLabel.style.opacity = '1';
                }
            });
        }

        function handleBlur() {
            var emailInput = document.getElementById("subscriber_email");
            var placeholderLabel = document.querySelector(".placeholder-label");

            if (!emailInput.value) {
                placeholderLabel.style.opacity = '1';
            }
        }
    </script>
<!-- JavaScript Libraries -->
<script src="<?= base_url() ?>assets/lib/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/lib/wow/wow.min.js"></script>
<script src="<?= base_url() ?>assets/lib/owl/js/owl.carousel.js"></script>
<script src="<?= base_url() ?>assets/js/zoom-image.js"></script>
<script src="<?= base_url() ?>assets/js/zoom-main.js"></script>
<!-- Main Javascript File -->
<script src="<?= base_url() ?>assets/lib/range/js/ion.rangeSlider.js"></script>
<script src="<?= base_url() ?>assets/js/custom.js"></script>


<script>
    function myFunction() {
        var x = document.getElementById('All-Categories');
        if (x.style.display === 'none') {
            x.style.display = 'block';
        } else {
            x.style.display = 'none';
        }
    }
</script>
<script>
    $(document).ready(function() {
        $(".`ShareBadge`").click(function() {
            $(".footer-newsletter-soical").toggleClass("d-block");
        });

        $('img').attr('onerror', 'this.onerror=null;this.src=`/assets/images/no-image.png`;');

        $('.search_icon').click(function() {
            search();
        });

        $("#seaarhbar").on("keydown", function(event) {
            if (event.key === "Enter") {
                search();
            }
        });

        function search() {
            const searchStr = $('#seaarhbar').val();
            if (searchStr !== '') {
                window.location = '/products?search=' + searchStr;
            }
        }
    });
</script>

<script>
    var cid = [];
    $(".categories").on("change", function() {
        var id = $(this).val();
        if ($(this).is(":checked")) {
            cid.push(id);
            $(".category_" + id).show();
        } else {
            cid.splice($.inArray(id, cid), 1);
            $(".subcategory_" + id).removeAttr('checked');
            $(".category_" + id).hide();
        }
        // alert(cid.length);
        if (cid.length > 0) {
            $("#filterBrand").css("display", "block");
        } else {
            $("#filterBrand").css("display", "none");
        }
    })
</script>
<script>
    $(".mobile-Filters").click(function() {
        $(".FiltersMain").toggleClass("show-filter");
    });
</script>

<script>
    let options = {
        root: null,
        threshold: 0.5,
    };

    function onIntersection(entries, observer) {
        entries.forEach((entry) => {
            if (entry.intersectionRatio > 0) {
                const imageElement = $(entry.target);
                let imageSrc = imageElement.attr('data-src');

                if (imageElement.is('img')) {
                    imageElement.attr('src', imageSrc);
                } else if (imageElement.is('div')) {
                    imageElement.css('background-image', `url(${imageSrc})`);
                }

                observer.unobserve(entry.target);
            }
        });
    }

    const observer = new IntersectionObserver(onIntersection, options);

    $('.div-lazy-loader').each(function() {
        observer.observe(this);
    });
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LC85J3LN16"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LC85J3LN16');
</script>

<!-- Google Tag Manager (noscript) -->
<!--
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NTDJ2NZ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
-->
<!-- End Google Tag Manager (noscript) -->

</body>

</html>