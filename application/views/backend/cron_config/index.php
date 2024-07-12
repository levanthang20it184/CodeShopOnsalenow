<?php $this->load->view('backend/layout/header'); ?>
<!-- DataTables -->
<style>
    .popover-link:hover {
        color: green !important;
    }
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>">
<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js'); ?>"></script>

<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('backend/common/messages.php') ?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; Cron Job List</h3>
                </div>
                <div class="d-inline-block float-right">
                <button class="btn btn-danger mr-3" onclick="deleteOutStockProducts()" style="color: white"><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;Delete out stock products (<span id="outstock_products"><?php echo $product_cnt ?></span>)</button>

                <button class="btn btn-warning mr-3" onclick="launchAllCronJobs(true)"><i class="fa fa-gavel"></i>&nbsp;&nbsp;&nbsp;Launch all cron jobs forcefully</button>

                    <button class="btn btn-info mr-3" onclick="launchAllCronJobs()"><i class="fa fa-gavel"></i>&nbsp;&nbsp;&nbsp;Launch all cron jobs</button>
                    <a type="button" class="btn btn-success"
                       href="<?php echo base_url('backend/Cron_Config/create') ?>"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;Add
                        new cron job</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="na_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>#<?= trans('id') ?></th>
                        <th>Feed url</th>
                        <th>Merchant name</th>
                        <th>Start upload at</th>
                        <th>Last uploaded at</th>
                        <th>Last uploaded size</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th width="120" class="text-right"><?= trans('action') ?></th>
                    </tr>
                    </thead>
                </table>
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
<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
    //---------------------------------------------------
    var table = $('#na_datatable').DataTable({

        "processing": true,
        "serverSide": false,
        "autoWidth": false,
        "ajax": "<?= base_url('backend/Cron_Config/cronjob_datatable') ?>",
        "order": [
            [0, 'asc']
        ],
        "columnDefs": [{
            "targets": 0,
            "name": "id",
            'searchable': true,
            'orderable': true,
        },
            {
                "render": function (data) {
                    return `<span style="cursor: pointer" tabindex="0" class="feed_url" data-toggle="popover" data-trigger="focus">` + (data.length > 50 ? data.substring(0, 50) + '...' : data) + `</span>` +
                        `<div class="popover-content hide">
              <a class="popover-link" style="cursor: pointer" onclick='window.open("${data}");return false;'>${data}</a>
            </div>`;
                },
                "targets": 1
            },
            {
                "targets": 2,
                "name": "merchant_name",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 3,
                "name": "start_upload_at",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 4,
                "name": "last_uploaded_at",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 5,
                "name": "last_uploaded_size",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 6,
                "name": "currency",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 7,
                "name": "is_active",
                'searchable': false,
                'orderable': false
            },
            {
                "targets": 8,
                "name": "Action",
                'searchable': false,
                'orderable': false
            }
        ]
    });

    table.on('draw', function () {
        $(".feed_url").popover({
            html: true,
            content: function () {
                return $(this).next().html();
            }
        });
    });
</script>

<script type="text/javascript">
    $("body").on("change", ".tgl_checkbox", function () {
        $.post('<?= base_url("backend/Cron_Config/change_status") ?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: $(this).data('id'),
                status: $(this).is(':checked') == true ? 1 : 0
            },
            function (data) {
                $.notify("Status Changed Successfully", "success");
            });
    });

    function loadProduct(id) {
        $('#blockAppliactionDialog').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.post('<?= base_url("backend/Cron_Config/manualOnetimeLoad") ?>', {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id
            },
            function (data) {
                $('#blockAppliactionDialog').modal("hide");
                if (data.startsWith("<!DOCTYPE html>")) {
                    $.notify("You need to login first", "success");
                } else {
                    $.notify(data, "success");
                }
            }).fail(function (xhr, status, error) {
            $('#blockAppliactionDialog').modal("hide");
            console.log(xhr.responseText);
            alert("An error occurred!");
        });
    }

    function removeCron(id, index) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("backend/Cron_Config/removeCronJob") ?>', {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        id
                    },
                    function (data) {
                        var table = $('#na_datatable').DataTable();
                        var row = table.row(index - 1);

                        row.remove().draw();

                        $.notify(data, "success");
                    });
            } else {
                // User clicked cancel or closed the dialog box
                // Do nothing or show a message to the user...
            }
        })
    }

    function launchAllCronJobs(isForce = false) {
        $('#blockAppliactionDialog').modal({
            backdrop: 'static',
            keyboard: false
        });

        $.ajax({
            url: '<?= base_url('backend/Cron_Config/launchCronJob') ?>',
            method: 'GET',
            data: { // add any desired parameters here
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                isForce: isForce
            },
            success: function(data) {
                $('#blockAppliactionDialog').modal("hide");
                if (data.startsWith("<!DOCTYPE html>")) {
                    $.notify("You need to login first", "success");
                } else {
                    $.notify(data, "success");
                }
            },
            error: function(xhr, status, error) {
                $('#blockAppliactionDialog').modal("hide");
                console.log(xhr.responseText);
                alert("An error occurred!");
            },
            timeout: 7200000
        });
    }

    function deleteOutStockProducts() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("backend/Cron_Config/deleteOutStockProducts") ?>', {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    function (data) {
                        $.notify(data, "success");
                        $('#outstock_products').text('0');
                    });
            } else {
                // User clicked cancel or closed the dialog box
                // Do nothing or show a message to the user...
            }
        })
    }
</script>