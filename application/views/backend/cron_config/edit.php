<?php $this->load->view('backend/layout/header'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>">
<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js'); ?>"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-pencil"></i>
                        &nbsp; Create new cron job </h3>
                </div>
            </div>
            <div class="card-body table-responsive">

                <table cellpadding="2" cellspacing="1" width="100%">
                    <tbody>
                    <tr>
                        <td class=smaller colSpan=2 bgcolor="#E1D82F">
                            <b>Basic Crop job configuration</b>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table cellpadding="2" cellspacing="1" bgcolor="#ffffff" width="100%" style="margin-top: 20px">
                    <tbody>
                    <form id="form_update_cron" action="<?php echo base_url('backend/Cron_Config/update_cron_job') ?>"
                          method="POST">
                        <input class="d-none" name="column_map" id="column_map" readonly="readonly" aria-hidden="true"
                               type="text"/>
                        <input class="d-none" name="category_map" id="category_map" readonly="readonly"
                               aria-hidden="true" type="text"/>
                        <input type="hidden" name="cron_job_id" value="<?php echo $cronJob['id']; ?>">
                        <tr>
                            <td align=right width="30%" nowrap="true">Feed URL:</td>
                            <td height=30>
                            <textarea class="form-control" name="feed_url" id="feed_url" rows="5" cols="100"><?php echo htmlspecialchars($cronJob['feed_url']); ?></textarea>

                            </td>
                        </tr>
                        <tr>
                            <td align=right width="30%" nowrap="true">Merchant:</td>
                            <td height=30>
                                <div class="dropdown hierarchy-select" id="brand_select">
                                    <button type="button" class="btn btn-secondary dropdown-toggle"
                                            id="btn-brand-select" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="btn-brand-select">
                                        <div class="hs-searchbox">
                                            <input type="text" class="form-control" autocomplete="off">
                                        </div>
                                        <div class="hs-menu-inner">
                                            <?php foreach ($merchants as $merchant) : ?>
                                                <a class="dropdown-item" <?= $merchant['id'] == $cronJob['merchant_id'] ? 'data-default-selected' : '' ?>
                                                   data-value="<?= $merchant['id']; ?>"
                                                   href="#"><?= $merchant['merchant_name']; ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <input class="d-none" name="merchant_id" id="merchant_id" readonly="readonly"
                                           aria-hidden="true" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td align=right width="30%" nowrap="true">HTTP access Username:</td>
                            <td height=30>
                                <input class="form-control" style="width: 300px" type="text" name="username">
                            </td>
                        </tr>
                        <tr>
                            <td align=right width="30%" nowrap="true">Password:</td>
                            <td height=30>
                                <input class="form-control" style="width: 300px" type="text" name="password">
                            </td>
                        </tr>
                        <tr>
                            <td align=right width="30%" nowrap="true">Column separator:</td>
                            <td height=30>
                                <select class="form-control" name="column_separator" id="column_separator"
                                        style="width: 100px">
                                    <option <?php echo $cronJob['column_separator'] == "," ? "selected" : "" ?>>,
                                    </option>
                                    <option <?php echo $cronJob['column_separator'] == "|" ? "selected" : "" ?>>|
                                    </option>
                                    <option <?php echo $cronJob['column_separator'] == ";" ? "selected" : "" ?>>;
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align=right width="30%" nowrap="true">Start upload at:</td>
                            <td height=30>
                                <input style="width: 200px" class="form-control" type="date" name="start_upload_at"
                                       value="<?php echo $cronJob['start_upload_at'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td align=right width="30%" nowrap="true">CSV has title line:</td>
                            <td height=30>
                                <input type="checkbox" <?php echo $cronJob['has_title_line'] == "yes" ? "checked" : "" ?>
                                       name="has_title_line" id="has_title_line">
                            </td>
                        </tr>

                        <tr>
                            <td align=right width="30%" nowrap="true">Price currency:</td>
                            <td height=30>
                                <select class="form-control" name="currency" style="width: 100px">
                                    <option <?php echo $cronJob['currency'] == "€" ? "selected" : "" ?>>€</option>
                                    <option <?php echo $cronJob['currency'] == "£" ? "selected" : "" ?>>£</option>
                                    <option <?php echo $cronJob['currency'] == "$" ? "selected" : "" ?>>$</option>
                                </select>
                            </td>
                        </tr>
                    </form>

                    </tbody>
                </table>

                <table cellpadding="2" cellspacing="1" width="100%" style="margin-top: 30px">
                    <tbody>
                    <tr>
                        <td class=smaller colSpan=2 bgcolor="#E1D82F">
                            <b>Resolve columns</b>
                        </td>
                        <td class=smaller colSpan=2 bgcolor="#E1D82F">
                            <div style="float: right;">
                                <button class="btn btn-danger" id="btn-load-column">Load column information.</button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table id="na_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>CSV Column</th>
                        <th>First Row Data(Example)</th>
                        <th>OSN Column</th>
                    </tr>
                    </thead>
                    <tbody id="columnInfo">
                    </tbody>
                </table>

                <table cellpadding="2" cellspacing="1" width="100%" style="margin-top: 30px">
                    <tbody>
                    <tr>
                        <td class=smaller colSpan=2 bgcolor="#E1D82F">
                            <b>Map Product categories</b>
                        </td>
                        <td class=smaller colSpan=2 bgcolor="#E1D82F">
                            <div style="float: right;">
                                <button class="btn btn-danger" id="btn-load-category">Load catogory map.</button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table id="na_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Onsalenow Category</th>
                        <th>Onsalenow Sub Category</th>
                    </tr>
                    </thead>
                    <tbody id="categoryInfo">
                    </tbody>
                </table>

                <div style="margin-top: 30px; float: right">
                    <input type="button" class="btn btn-primary" value="Save" onclick="Save()">
                    <input type="button" class="btn btn-primary" value="Cancel" style="margin-left: 5px"
                           onClick="history.back(-1)">
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal" id="blockAppliactionDialog" tabindex="-1" role="status" style="padding-right: 15px;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content"
             style="background-color: transparent;border-width:0;box-shadow: initial;border: 1px solid burlywood;backdrop-filter: blur(2px);border-radius: 14px;">
            <div class="container" style="color: burlywood;font-size:large">
                <h3 style="text-align: center;margin-bottom: 0;margin: 28px;">Loading from server...</h3>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('backend/layout/footer'); ?>

<!-- Popper Js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<!-- Hierarchy Select Js -->
<script src="<?= base_url() ?>assets/js/hierarchy-select.js"></script>

<script>
    let columnMapIninted = false;

    let categories;
    $(document).ready(function () {
        $('#brand_select').hierarchySelect({
            hierarchy: false,
            width: '300px',
            onSelect: function (value, text) {
                $('#merchant_id').val(value);
            }
        });

        $('#btn-load-column').click(function () {
            let feedUrl = $('#feed_url').val();

            if (feedUrl == "") {
                $.notify("You need to input the feed url.", "error");
                return;
            }

            let brandId = $('#merchant_id').val();

            if (brandId == 0) {
                $.notify("You need to select brand.", "error");
                return;
            }

            $('#blockAppliactionDialog').modal({
                backdrop: 'static',
                keyboard: false
            });

            $.post('<?= base_url("backend/Cron_Config/load_column_info") ?>', {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                    feed_url: feedUrl,
                    column_separator: $('#column_separator').val(),
                    has_title_line: $('#has_title_line').val()
                },
                function (data) {
                    try {
                        $('#blockAppliactionDialog').modal("hide");

                        let status = JSON.parse(data)[1];
                        if (JSON.parse(data)[1] == "error") {
                            $.notify(JSON.parse(data)[0], "error");
                        } else {
                            columnInfo = JSON.parse(data)[0];
                            let titleLine = columnInfo[0];
                            let firstLine = columnInfo[1];

                            let titleLineCnt = titleLine.length;
                            let firstLineCnt = firstLine.length;

                            let maxCnt = titleLineCnt > firstLineCnt ? titleLineCnt : firstLineCnt;

                            $('#columnInfo').empty();
                            for (let i = 0; i < maxCnt; i++) {
                                let row = `<tr><td class="column_index">${i + 1}</td><td>${i < titleLineCnt ? getShortenText(titleLine[i]) : ""}</td><td>${i < firstLineCnt ? getShortenText(firstLine[i]) : ""}</td><td>${getOSNColumn(i)}</td></tr>`;
                                $('#columnInfo').append(row);
                            }
                        }

                        columnMapIninted = true;
                    } catch (e) {
                        console.log(e);
                    }
                }).fail(function (xhr, status, error) {
                $('#blockAppliactionDialog').modal("hide");
                console.log(xhr.responseText);
                alert("An error occurred!");
            });
        });

        const column_map_js = JSON.parse('<?php echo $cronJob['column_map']?>');

        function getOSNColumn(index) {
            return `<select class="osn-column form-control" style="width: 200px">
            <option ${column_map_js[index][1] === '...' ? 'selected' : ''}>...</option>
            <option ${column_map_js[index][1] === 'Brand' ? 'selected' : ''}>Brand</option>
            <option ${column_map_js[index][1] === 'Category' ? 'selected' : ''}>Category</option>
            <option ${column_map_js[index][1] === 'SubCategory' ? 'selected' : ''}>SubCategory</option>
            <option ${column_map_js[index][1] === 'Product Name' ? 'selected' : ''}>Product Name</option>
            <option ${column_map_js[index][1] === 'Product Description' ? 'selected' : ''}>Product Description</option>
            <option ${column_map_js[index][1] === 'Product Image URL' ? 'selected' : ''}>Product Image URL</option>
            <option ${column_map_js[index][1] === 'Product Details URL' ? 'selected' : ''}>Product Details URL</option>
            <option ${column_map_js[index][1] === 'Direct Buy Link' ? 'selected' : ''}>Direct Buy Link</option>
            <option ${column_map_js[index][1] === 'Merchant Price' ? 'selected' : ''}>Merchant Price</option>
            <option ${column_map_js[index][1] === 'Sale Price' ? 'selected' : ''}>Sale Price</option>
            <option ${column_map_js[index][1] === 'Color' ? 'selected' : ''}>Color</option>
            <option ${column_map_js[index][1] === 'Size' ? 'selected' : ''}>Size</option>
            <option ${column_map_js[index][1] === 'Option' ? 'selected' : ''}>Option</option>
           </select>`;
        }

        $('#btn-load-category').click(function () {
            let feedUrl = $('#feed_url').val();

            if (feedUrl == "") {
                $.notify("You need to input the feed url.", "error");
                return;
            }

            let brandId = $('#merchant_id').val();

            if (brandId == 0) {
                $.notify("You need to select brand.", "error");
                return;
            }

            if (!columnMapIninted) {
                $.notify("You need to init column map.", "error");
                return;
            }

            let categoryIndex = 0;
            let subCategoryIndex = 0;
            let productNameIndex = 0;
            let brandNameIndex = 0;

            $('.osn-column').each(function (index) {
                const label = $(this).val();
                if (label === "Category") {
                    categoryIndex = ++index;
                } else if (label === "SubCategory") {
                    subCategoryIndex = ++index;
                } else if (label === "Product Name") {
                    productNameIndex = ++index;
                } else if (label === "Brand") {
                    brandNameIndex = ++index;
                }
            });

            if (brandNameIndex === 0) {
                $.notify("You need to select brand.", "error");
                return;
            }

            if (productNameIndex === 0) {
                $.notify("You need to select product name.", "error");
                return;
            }

            if (categoryIndex === 0) {
                $.notify("You need to select category.", "error");
                return;
            }

            if (subCategoryIndex === 0) {
                $.notify("You need to select sub category.", "error");
                return;
            }

            categoryIndex--;
            subCategoryIndex--;

            $('#blockAppliactionDialog').modal({
                backdrop: 'static',
                keyboard: false
            });

            $.post('<?= base_url("backend/Cron_Config/load_category_info") ?>', {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                    feed_url: feedUrl,
                    column_separator: $('#column_separator').val(),
                    has_title_line: $('#has_title_line').val(),
                    categoryIndex,
                    subCategoryIndex
                },
                function (data) {
                    try {
                        $('#blockAppliactionDialog').modal("hide");

                        data = JSON.parse(data);
                        $('#categoryInfo').empty();

                        const depsAndCats = data[2];
                        const length = depsAndCats.length;

                        categories = data[1];

                        for (let i = 0; i < length; i++) {
                            const categoryName = depsAndCats[i][0];
                            const subCategoryName = depsAndCats[i][1];
                            const osnCategoryId = depsAndCats[i][2];
                            const osnSubCategoryId = depsAndCats[i][3];

                            let row = `<tr class="categoryMapItem"><td>${i + 1}</td><td class="category_name">${categoryName}</td><td class="subCategory_name">${subCategoryName}</td>
              <td><div style="display: flex">${getOSNCategory(i, data[0], osnCategoryId)}<button style="margin-left: 5px" title="View" class="view btn btn-sm btn-success" onclick="addCategory(${i})"> <i class="fa fa-plus"></i></button></div></td>
              <td><div style="display: flex">${getOSNSubCategory(i, osnCategoryId, data[1], osnSubCategoryId)}<button style="margin-left: 5px" title="View" class="view btn btn-sm btn-info" onclick="addSubCategory(` + i + `)"><i class="fa fa-plus"></i></button></div></td>
              </tr>`;
                            $('#categoryInfo').append(row);
                        }
                    } catch (e) {
                        console.log(e);
                    }
                }).fail(function (xhr, status, error) {
                $('#blockAppliactionDialog').modal("hide");
                console.log(xhr.responseText);
                alert("An error occurred!");
            });
        });
    });

    function addCategory(index) {
        Swal.fire({
            title: "Add Category",
            input: "text",
            inputValue: '',
            showCancelButton: true,
            confirmButtonText: "Add",
        }).then((result) => {
            if (result.isConfirmed) {
                const newName = result.value;

                $.post('<?= base_url("backend/Category/addCategory") ?>', {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        newName
                    },
                    function (data) {
                        data = JSON.parse(data);

                        $('.od').each(function (index, element) {
                            $(element).append(`<option value="${data[1]}">${newName}</option>`);
                        });

                        $('#od_' + index).find('option[value="' + data[1] + '"]').first().attr('selected', true);

                        $('#oc_' + index).empty();

                        $.notify(data[0], "success");
                    });
            }
        });
    }

    function addSubCategory(index) {
        const categoryId = $('#od_' + index).val();

        Swal.fire({
            title: "Add Sub Category",
            input: "text",
            inputValue: '',
            showCancelButton: true,
            confirmButtonText: "Add",
        }).then((result) => {
            if (result.isConfirmed) {
                const newName = result.value;

                $.post('<?= base_url("backend/Category/addSubCategory") ?>', {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        categoryId,
                        newName
                    },
                    function (data) {
                        data = JSON.parse(data);

                        categories.push({'id': data[1], 'category_id': categoryId, 'name': newName});

                        console.log(categories);

                        const selectedCategoryName = $('#od_' + index + ' option:selected').text();

                        $('.od option:selected').filter(function () {
                            return $(this).text().trim() === selectedCategoryName;
                        }).parent('select').parent().parent().parent().find('.oc').append(`<option value="${data[1]}">${newName}</option>`);

                        $('#oc_' + index).find('option[value="' + data[1] + '"]').first().prop('selected', true);

                        $.notify(data[0], "success");
                    });
            }
        });
    }

    function getOSNCategory(index, osnCategories, selectedId) {
        let categorySelect = `<select id="od_${index}" onchange="categoryChanged(${index})" class="od form-control" style="width: 250px">
          <option ${selectedId == -1 ? "selected" : ""} 
        value="-1">...</option>`;

        for (osnCategory of osnCategories) {
            categorySelect += `<option ${osnCategory["id"] == selectedId ? "selected" : ""}
        value="${osnCategory["id"]}">${osnCategory["name"]}</option>`;
        }

        categorySelect += `</select>`;
        return categorySelect;
    }

    function categoryChanged(index) {
        let oc = $('#oc_' + index);
        oc.empty();

        let categoryId = $('#od_' + index).val();

        if (categoryId == -1) {
            oc.append(`<option value="0">...</option>`);
            return;
        }

        for (osnCategory of categories) {

            if (categoryId == osnCategory["category_id"])
                oc.append(`<option value="${osnCategory["id"]}">${osnCategory["name"]}</option>`);
        }
    }

    function getOSNSubCategory(index, osnCategoryId, osnSubCategories, selectedId) {

        let subCategorySelect = `<select id="oc_${index}" class="oc form-control" style="width: 250px">`;

        if (osnCategoryId == -1) {
            subCategorySelect += `<option selected value="-1">...</option>`;
        } else {
            for (osnSubCategory of osnSubCategories) {
                if (osnCategoryId == osnSubCategory["category_id"])
                    subCategorySelect += `<option ${osnSubCategory["id"] == selectedId ? "selected" : ""} value="${osnSubCategory["id"]}">${osnSubCategory["name"]}</option>`;
            }
        }

        subCategorySelect += `</select>`;
        return subCategorySelect;
    }

    function getShortenText(text) {
        return text.substring(0, 100);
    }

    function Save() {
        let feedUrl = $('#feed_url').val();

        if (feedUrl == "") {
            $.notify("You need to input the feed url.", "error");
            return;
        }

        let brandId = $('#merchant_id').val();

        if (brandId == 0) {
            $.notify("You need to select brand.", "error");
            return;
        }

        if (!columnMapIninted) {
            $.notify("You need to init column map.", "error");
            return;
        }

        let column_map = [];

        $(".column_index").each(function () {
            let index = $(this).text();
            let osnColumn = $(this).next().next().next().find("select").val();

            column_map.push([index, osnColumn]);
        });

        if (column_map.length === 0) {
            $.notify("You need to configure column map.", "error");
            return;
        }

        $('#column_map').val(JSON.stringify(column_map));

        // collect category_map information
        let categoryMapItems = [];
        $('.categoryMapItem').each(function () {

            let categoryMapItem = [$(this).find(".category_name").text(),
                $(this).find(".subCategory_name").text(),
                $(this).find(".od").val(),
                $(this).find(".oc").val()
            ];

            categoryMapItems.push(categoryMapItem);
        });

        $('#category_map').val(JSON.stringify(categoryMapItems));

        $('#form_update_cron').submit();
    }
</script>