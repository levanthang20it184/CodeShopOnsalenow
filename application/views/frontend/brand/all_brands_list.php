<?php $this->load->view('frontend/layout/header'); ?>

    <style>
        .ln-letters > a {
            cursor: pointer;
        }

        .ln-letters > a:hover {
            background-color: #9f2b1e;
            color: white !important;
        }
    </style>

    <div class="container">
        <div class="Breadcrumb row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12  ">
                <ul class="list-unstyled d-flex">
                    <li><a href="<?php echo base_url() ?>">Home</a></li>
                    <li><span> <?= $title; ?></span></li>
                </ul>
            </div>
        </div>
    </div>

    <section class="theme-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12  ">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="BrandTopbar">
                                <div class="ln-letters">
                                    <a class="brand-letters all ln-selected" onclick="getFilterList();">All</a>
                                    <a class="brand-letters _ ln-disabled" onclick="getFilterList('0-9');">0-9</a>
                                    <?php foreach ($alphabeticList as $alphabeticValue) { ?>
                                        <a class="brand-letters <?php echo $alphabeticValue; ?>"
                                           onclick="getFilterList('<?php echo $alphabeticValue; ?>');"><?php echo $alphabeticValue; ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="BrandMain">
                                <div class="search_Box">
                                    <form class="search-form-box">
                                        <input type="text" id="az-search" name="search" onkeyup="searchbybrand();"
                                               placeholder="Search By Brand Name..." class="search-form-input">
                                        <button class="search-form-button"><img class="div-lazy-loader" alt="search button image" data-src="<?php echo base_url('assets/images/search.png'); ?>"></button>
                                    </form>
                                </div>

                                <?php // echo "<pre>"; print_r($brandList); ?>

                                <ul id="listnav">
                                    <?php foreach ($alphabeticList as $key => $alphabeticValue) { 
                                        $before = ""; ?>

                                        <div class="title red-title"></div>

                                        <?php foreach ($brandList as $key => $brandValue) {
                                            $brandValueFirst = substr($brandValue->alias, 0, 1);

                                            if ($brandValueFirst == $alphabeticValue && $before != $brandValue->alias) { ?>

                                                <li>
                                                    <a href="<?php echo base_url() ?><?php echo $brandValue->slug; ?>"><?php echo $brandValue->alias; ?></a>
                                                </li>

                                            <?php }
                                            $before = $brandValue->alias;
                                        } ?>

                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </section>

    <script>
        function searchbybrand() {

            var searchData = document.getElementById('az-search').value;
            // alert(searchData);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . 'home/getAllBrandsBySearchText'?>",
                data: {searchData: searchData},
                dataType: "html",
                cache: false,
                success: function (data) {

                    console.log(data);
                    $('#listnav').html(data);
                }
            });


        }

        function getFilterList(value) {

            var alphaVal = value;

            // alert(alphaVal);

            $.ajax({

                type: "POST",
                url: "<?php echo base_url() . 'home/filters'?>",
                data: {value: value},
                dataType: "html",
                cache: false,
                success: function (data) {
                    console.log(data);
                    $('#listnav').html(data);

                }
            });


        }
    </script>

<?php $this->load->view('frontend/layout/footer'); ?>