<?php $this->load->view('backend/layout/header'); ?>
<!-- DataTables -->
<!-- <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">  -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <?php $this->load->view('backend/common/messages.php') ?>
        <div class="card card-default">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-pencil"></i>
                        &nbsp;Edit <?= $title; ?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <!-- <a href="<?= base_url('admin/delivery'); ?>" class="btn btn-success"><i class="fa fa-list"></i> Delivery Deals List</a> -->
                </div>
            </div>
            <div class="card-body">
                <!-- <form method="post" enctype="multipart/form-data"> -->
                <?php echo form_open_multipart(base_url('backend/product/update/'), 'class="form-horizontal"') ?>
                <input type="hidden" value="<?= $deal['id']; ?>" />
                <div class="form-group">
                    <label for="merchat" class="col-md-2 control-label">Merchant</label>
                    <div class="col-md-12">
                        <select class="form-control" name="merchant_id">
                          <option value="" disabled selected>Select Merchant</option>
                          <?php foreach ($merchants as $merchant) { ?>
                            <option value="<?= $merchant['id']; ?>" <?= $merchant['id']==$product->merchant_id?'selected':''; ?>><?= $merchant['merchant_name']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="merchat" class="col-md-2 control-label">Sub Category</label>
                    <div class="col-md-12">
                        <select class="form-control" name="subcategory_id">
                          <option value="" disabled selected>Select SubCategory</option>
                          <?php foreach ($subCategories as $subCategory) { ?>
                            <option value=<?= $subCategory['id']; ?> <?= $subCategory['id']==$product->subCategory_id?'selected':''; ?>><?= $subCategory['name']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="merchat" class="col-md-2 control-label">Brand</label>
                    <div class="col-md-12">
                        <select class="form-control" name="brand_id">
                          <option value="" disabled selected>Select Brand</option>
                          <?php foreach ($brands as $brand) { ?>
                            <option value=<?= $brand['id']; ?> <?= $brand['id']==$product->brand_id?'selected':''; ?>><?= $brand['alias']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-md-2 control-label">Product Name</label>
                    <div class="col-md-12">
                        <input type="text" name="product_name" class="form-control" id="product_name" value="<?= $product->name; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="slug" class="col-md-2 control-label">Slug</label>
                    <div class="col-md-12">
                        <input type="text" name="slug" class="form-control" id="slug" value="<?= $product->slug; ?>" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-md-2 control-label">Description</label>
                    <div class="col-md-12">
                        <textarea type="text" name="description" class="form-control" id="description"><?= $product->description ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="image" class="col-md-2 control-label">Image </label>
                    <div class="col-md-12">
                        <input type="file" name="image" class="form-control" id="image" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="wp_name" class="col-md-2 control-label">WP Name</label>
                    <div class="col-md-12">
                        <input type="text" name="wp_name" class="form-control" id="wp_name" required value="<?= $product->name_wp ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="store_url" class="col-md-2 control-label">Merchant Store URL</label>
                    <div class="col-md-12">
                        <input type="text" name="store_url" class="form-control" id="store_url" required value="<?= $product->merchant_store_url ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="selling_price" class="col-md-2 control-label">Selling Price</label>
                    <div class="col-md-12">
                        <input type="number" name="selling_price" class="form-control" id="selling_price" value="<?= $product->selling_price ?>" required />
                    </div>
                </div>

                <div class="form-group">
                    <label for="cost_price" class="col-md-2 control-label">Cost Price</label>
                    <div class="col-md-12">
                        <input type="number" name="cost_price" class="form-control" id="cost_price" value="<?= $product->cost_price ?>" required />
                    </div>
                </div>

                <div class="form-group">
                    <label for="cost_price" class="col-md-2 control-label">Sale Start Date</label>
                    <div class="col-md-12">
                        <input type="date" name="sale_start_date" class="form-control" id="cost_price" value="<?= $product->sale_start_date ?>" required />
                    </div>
                </div>

                <div class="form-group">
                    <label for="cost_price" class="col-md-2 control-label">Sale End Date</label>
                    <div class="col-md-12">
                        <input type="date" name="sale_end_date" class="form-control" id="cost_price" value="<?= $product->sale_end_date ?>" required />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-info" name="submit" value="Update">
                    </div>
                </div>

                <?php echo form_close(); ?>
                <!-- </form> -->
            </div>
            <!-- /.box-body -->
        </div>
    </section>
</div>

<?php $this->load->view('backend/layout/footer'); ?>
<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script type="text/javascript">
</script>