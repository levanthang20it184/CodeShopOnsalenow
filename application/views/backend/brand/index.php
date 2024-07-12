<?php $this->load->view('backend/layout/header'); ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">

<style type="text/css">
    .image_disabled::before {
        background-color: lightgray!important;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('backend/common/messages.php') ?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; Brand List </h3>
                </div>
                <div class="d-inline-block float-right">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="na_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th>#<?= trans('id') ?></th>
                            <th>Name</th>
                            <th>Alias</th>
                            <th>Slug</th>
                            <th>Image</th>
                            <th>Product Count</th>
                            <th>Status</th>
                            <th>Show Logo</th>
                            <th>Show Home</th>
                            <th width="100" class="text-right"><?= trans('action') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <div>
        <div style="display: flex; justify-content: center; margin: 20px 0px">
            <table>
                <tr>
                    <td>
                        <span id="noImageBrandCnt"><?php echo $noImageBrandCnt ?></span> brands have no image.
                    </td>
                    <td>
                        <button class="btn btn-success" style="margin-left: 20px" id="btn-load-column" onclick="fetchLogos()">Fetch logos
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="modal" id="blockAppliactionDialog" tabindex="-1" role="status" style="padding-right: 15px;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color: transparent;border-width:0;box-shadow: initial;border: 1px solid burlywood;backdrop-filter: blur(2px);border-radius: 14px;">
            <div class="container" style="color: burlywood;font-size:large">
                <h3 style="text-align: center;margin-bottom: 0;margin: 28px;">Loading from server...</h3>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('backend/layout/footer'); ?>
<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
    //---------------------------------------------------
    var table = $('#na_datatable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "<?php echo base_url('backend/brand/brand_datatable'); ?>",
        order: [
            [0, 'asc']
        ],
        columnDefs: [{
                targets: 0,
                name: "id",
                searchable: true,
                orderable: true
            },
            {
                targets: 1,
                name: "brand_name",
                searchable: true,
                orderable: true
            },
            {
                targets: 2,
                name: "alias",
                searchable: true,
                orderable: true
            },
            {
                targets: 3,
                name: "slug",
                searchable: true,
                orderable: true
            },
            {
                targets: 4,
                name: "image",
                searchable: false,
                orderable: false
            },
            {
                targets: 5,
                name: "product_cnt",
                searchable: false,
                orderable: true
            },
            {
                targets: 6,
                searchable: false,
                orderable: true,
                render: function(data, type, row) {
                    data = JSON.parse(data);
                    if (type === 'display')
                        return '<input class="tgl_active_checkbox tgl-ios" data-id="' + data[1] + '" id="cb_' + data[1] + '" type="checkbox" ' + data[0] + '><label for="cb_' + data[1] + '"></label>';
                    else
                        return data[0];
                }
            },
            {
                targets: 7,
                searchable: false,
                orderable: true,
                render: function(data, type, row) {
                    data = JSON.parse(data);
                    if (type === 'display')
                        return `<input ${JSON.parse(row[6])[0] === 'checked' ? '' : 'disabled'} class="tgl_image_checkbox tgl-ios" data-id="` + data[1] + '" id="cb1_' + data[1] + '" type="checkbox" ' + data[0] + '><label data-id="' + data[1] + `" class="image_label ${JSON.parse(row[6])[0] === 'checked' ? '' : 'image_disabled'}" for="cb1_` + data[1] + `"></label>`;
                    else
                        return data[0];
                }
            },
            {
                targets: 8,
                searchable: false,
                orderable: true,
                render: function(data, type, row) {
                    data = JSON.parse(data);
                    if (type === 'display')
                        return `<input ${JSON.parse(row[6])[0] === 'checked' ? '' : 'disabled'} class="tgl_home_checkbox tgl-ios" data-id="` + data[1] + '" id="cb2_' + data[1] + '" type="checkbox" ' + data[0] + '><label data-id="' + data[1] + `" class="image_label ${JSON.parse(row[6])[0] === 'checked' ? '' : 'image_disabled'}" for="cb2_` + data[1] + `"></label>`;
                    else
                        return data[0];
                }
            },
            {
                targets: 9,
                name: null,
                searchable: false,
                orderable: false,
                width: "100px"
            }
        ]
    });

    $("body").on("change", ".tgl_image_checkbox", function() {
        $.post('<?= base_url("backend/brand/change_is_image") ?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: $(this).data('id'),
                is_image: $(this).is(':checked') == true ? 1 : 0
            },
            function(data) {
                $.notify("Image status changed successfully", "success");
            });
    });

    $("body").on("change", ".tgl_home_checkbox", function() {
        $.post('<?= base_url("backend/brand/change_show_home") ?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: $(this).data('id'),
                show_home: $(this).is(':checked') == true ? 1 : 0
            },
            function(data) {
                $.notify("Home display status changed successfully", "success");
            });
    });

    $("body").on("change", ".tgl_active_checkbox", function() {
        $(`.image_label[data-id='${$(this).data('id')}']`).toggleClass("image_disabled");

        if ($(this).is(':checked')) {
            $(`.tgl_image_checkbox[data-id='${$(this).data('id')}']`).prop("disabled", false);
            $(`.tgl_home_checkbox[data-id='${$(this).data('id')}']`).prop("disabled", false);
        } else {
            $(`.tgl_image_checkbox[data-id='${$(this).data('id')}']`).prop("disabled", true);
            $(`.tgl_home_checkbox[data-id='${$(this).data('id')}']`).prop("disabled", true);
        }

        $.post('<?= base_url("backend/brand/change_status") ?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: $(this).data('id'),
                status: $(this).is(':checked') == true ? 1 : 0
            },
            function(data) {
                $.notify("Status changed successfully", "success");
            });
    });

    function getProductCount(brandId, categoryId, subCategoryId) {
        $.post('<?= base_url("backend/brand/getProductCount") ?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                brandId,
                categoryId,
                subCategoryId
            },
            function(data) {
                $.notify("This brand has " + data + " products.", "success");
            });
    }

    function fetchLogos() {
        $('#blockAppliactionDialog').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.post('<?= base_url("backend/brand/fetchLogos") ?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            function(data) {
                $('#blockAppliactionDialog').modal("hide");

                $.notify('Brand logos updated.', "success");

                $('#noImageBrandCnt').text("0");
            }).fail(function(xhr, status, error) {
            $('#blockAppliactionDialog').modal("hide");
            console.log(xhr.responseText);
            alert("An error occurred while fetching brand logos!");
        });
    }
</script>