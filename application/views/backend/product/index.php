<?php $this->load->view('backend/layout/header'); ?>
<!-- DataTables -->
<!-- <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">  -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('backend/common/messages.php') ?>
        <div class="card">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= trans('product_list') ?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <!-- <b> Total Products : </b><?php echo $totalRec; ?> -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <div class="d-flex justify-content-end mb-3 align-items-center">
                    <label>Category:&nbsp;</label>
                    <select class="form-control form-control-sm" style="max-width: 25%;" id="category-select">
                        <option value="">All</option>
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <table id="dataListTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Updated At</th>
                            <th>Top Deal</th>
                            <th>Manual Top50</th>
                            <th>Auto Top50</th>
                            <th>In Stock</th>
                            <th>Discount</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
        <div class="pagination backend-pgntion">
            <?php echo $this->pagination->create_links(); ?>
        </div>
    </section>
</div>

<?php $this->load->view('backend/layout/footer'); ?>
<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        var dataTable = $("#dataListTable").dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "autoWidth": false,
            "bPaginate": true,
            "sAjaxSource": "<?= base_url($api??'backend/product/viewProducts') ?>",
            buttons: [],
            lengthMenu: [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"]
            ],
            pageLength: 10,
            "columnDefs": [{
                "orderable": false,
                "targets": [3]
            }],
            dom: "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>"
        });

        $("body").on("change", ".tgl_manual_top", function() {
            $.post('<?= base_url("backend/product/change_status") ?>', {
                    id: $(this).data('id'),
                    m_top: $(this).is(':checked') ? 1 : 0
                },
                function(data) {
                    if (data == 1)
                        $.notify("You changed this product's top 50 status successfully.", "success");
                    else
                        $.notify("Something went wrong.", "error");
                });
        });

        $("body").on("change", ".tgl_top_deal", function() {
            $.post('<?= base_url("backend/product/change_top_deal") ?>', {
                    id: $(this).data('id'),
                    top_deal: $(this).is(':checked') ? 1 : 0
                },
                function(data) {
                    if (data == 1)
                        $.notify("You changed this product's top deal status successfully.", "success");
                    else
                        $.notify("Something went wrong.", "error");
                });
        });

        $("body").on("change", ".tgl_stock", function() {
            $.post('<?= base_url("backend/product/change_stock") ?>', {
                    id: $(this).data('id'),
                    stock: $(this).is(':checked') ? 1 : 0
                },
                function(data) {
                    if (data == 1)
                        $.notify("You changed this product's stock successfully.", "success");
                    else
                        $.notify("Something went wrong.", "error");
                });
        });

        $("#category-select").change(function() {
            const category = $(this).val();
            console.log(dataTable);
            dataTable.api().column(1).search(category).draw();
        });
    });
</script>