<?php $this->load->view('backend/layout/header'); ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('backend/common/messages.php') ?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; Menu List </h3>
                </div>
                <div class="d-inline-block float-right">


                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="menus_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Link</th>
                        <!-- <th>Status</th> -->
                        <th width="100" class="text-right"><?= trans('action') ?></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('backend/layout/footer'); ?>


<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
    //---------------------------------------------------
    var table = $('#menus_datatable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": "<?=base_url('backend/menus/menus_datatable')?>",
        "order": [[0, 'asc']],
        "columnDefs": [
            {"targets": 0, "name": "id", 'searchable': true, 'orderable': true},
            {"targets": 1, "name": "name", 'searchable': true, 'orderable': true},
            {"targets": 2, "name": "link", 'searchable': true, 'orderable': true},
            {"targets": 3, "name": "Action", 'searchable': false, 'orderable': false}
        ]
    });
</script>
