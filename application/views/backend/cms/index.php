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
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= trans('cms_list') ?></h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?php echo base_url(); ?>backend/cms/add">Add CMS</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="na_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>#<?= trans('id') ?></th>
                        <th>Title</th>
                        <th><?= trans('slug') ?></th>
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

<?php $this->load->view('backend/layout/footer'); ?>
<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    //---------------------------------------------------
    var table = $('#na_datatable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": "<?=base_url('backend/cms/cms_datatable')?>",
        "order": [[4, 'desc']],
        "columnDefs": [
            {"targets": 0, "name": "id", 'searchable': true, 'orderable': true},
            {"targets": 1, "title": "title", 'searchable': true, 'orderable': true},
            {"targets": 2, "slug": "slug", 'slug': true, 'orderable': true},
            {"targets": 3, "name": "is_active", 'searchable': true, 'orderable': true},
            {"targets": 4, "name": "is_verify", 'searchable': true, 'orderable': true},
            {"targets": 5, "name": "action", 'searchable': false, 'orderable': false, "width": '100px'},
        ]
    });
</script>


<script type="text/javascript">
    $("body").on("change", ".tgl_checkbox", function () {
        $.post('<?=base_url("backend/cms/change_status")?>',
            {
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                id: $(this).data('id'),
                status: $(this).is(':checked') == true ? 1 : 0
            },
            function (data) {
                $.notify("Status Changed Successfully", "success");
            });
    });

    $("body").on("click", ".cms-delete", function () {

        Swal.fire({
            title: 'Do you want to delete this record?',
            showDenyButton: true,
            // showCancelButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: `Don't Delete`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.post($(this).attr('deleteUrl'),
                    {
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        id: $(this).attr('deleteId'),
                        status: $(this).is(':checked') == true ? 1 : 0
                    },
                    function (data) {
                        $.notify("CMS Deleted Successfully", "success");
                        location.reload();
                    });
            } else if (result.isDenied) {
                Swal.fire('Request cancel', '', 'info')
            }
        })

    });

</script>


