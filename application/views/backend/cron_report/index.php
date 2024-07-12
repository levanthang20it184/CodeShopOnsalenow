<?php $this->load->view('backend/layout/header'); ?>

<style>
    .form-control {
        width: auto !important
    }
</style>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">

<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('backend/common/messages.php') ?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block leftpart">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; Cron Report</h3>
                </div>
                <div class="rightpart">
                    <div class="imp-csv">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table id="na_datatable" class="table table-bordered table-striped" width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Merchant Name</th>
                        <th>Detail</th>
                        <th>Date</th>
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
    var table = $('#na_datatable').DataTable({

        "processing": true,
        "serverSide": false,
        "autoWidth": false,
        "ajax": "<?= base_url('backend/Cron_Report/cron_report_datatable') ?>",
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
                "targets": 1,
                "name": "merchant_name",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 2,
                "name": "detail",
                'searchable': true,
                'orderable': true
            },
            {
                "targets": 3,
                "name": "date",
                'searchable': true,
                'orderable': true
            }
        ]
    });
</script>