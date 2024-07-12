<?php $this->load->view('backend/layout/header'); ?>
<!-- DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('backend/common/messages.php') ?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block leftpart">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= $title; ?> List</h3>
                </div>
                <div class="rightpart">
                    <div class="imp-csv">
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="erroelistmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modeldivsize">
                    <div class="modal-header">
                        <h5 class="modal-title panel-heading" id="exampleModalLabel">Import sheet error</h5>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group pre-scrollable element" id="errorlist">
                        </ul>
                    </div>

                </div>
            </div>
        </div>
        <!--end -->
        <div class="card">
            <div class="card-body table-responsive">
                <table id="na_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>#<?= trans('id') ?></th>
                        <th><?= trans('name') ?></th>
                        <th><?= trans('created_date') ?></th>
                        <th><?= trans('status') ?></th>
                        <th width="100" class="text-right"><?= trans('action') ?></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>


<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
    //---------------------------------------------------
    var table = $('#na_datatable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": "<?=base_url('backend/category/category_datatable')?>",
        "order": [[0, 'asc']],
        "columnDefs": [
            {"targets": 0, "name": "id", 'searchable': true, 'orderable': true},
            {"targets": 1, "name": "name", 'searchable': true, 'orderable': true},
            {"targets": 2, "name": "is_verify", 'searchable': true, 'orderable': true},
            {"targets": 3, "name": "status", 'searchable': true, 'orderable': true},
            // { "targets": 6, "name": "Action", 'searchable':false, 'orderable':false,'width':'100px'}
        ]
    });
</script>
<?php $this->load->view('backend/layout/footer'); ?>

<script type="text/javascript">
    $("body").on("change", ".tgl_checkbox", function () {
        $.post('<?=base_url("backend/category/change_status")?>',
            {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: $(this).data('id'),
                status: $(this).is(':checked') == true ? 1 : 0
            },
            function (data) {
                $.notify("Status Changed Successfully", "success");
            });
    });
</script>