<?php $this->load->view('backend/layout/header'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
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
                    <!-- For Messages -->
                    <?php $this->load->view('backend/common/messages.php') ?>

                    <!-- <form method="post" enctype="multipart/form-data"> -->
                    <?php echo form_open_multipart(base_url('backend/merchant/edit/' . $deal['id']), 'class="form-horizontal"') ?>
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label">Merchant Name</label>
                        <div class="col-md-12">
                            <input type="text" name="merchant_name" class="form-control" id="merchant_name"
                                   value="<?= $deal['merchant_name']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label">Shipping Cost</label>
                        <div class="col-md-12">
                            <input type="text" name="shipping_cost" class="form-control" id="product_name"
                                   value="<?= $deal['shipping_cost']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label">Delivery Time</label>
                        <div class="col-md-12">
                            <input type="text" name="shipping_days" class="form-control" id="delivery_time"
                                   value="<?= $deal['shipping_days']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label">Specific Promotion </label>
                        <div class="col-md-12">
                            <input type="text" name="specific_promotion" class="form-control" id="specific_promotion"
                                   value="<?= $deal['specific_promotion']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username" class="col-md-2 control-label">Logo Update</label>
                        <div class="col-md-12">
                            <input type="file" class="form-controls" name="file">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image" class="col-md-2 control-label">Logo</label>
                        <div class="col-md-12">
                            <img alt="merchant image" width="100px" src="<?= base_url('/uploads/merchant/') . $deal['image']; ?>">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-md-2 control-label">EU Icon Status<span class="required">*</span></label>
                        <div class="col-md-12">
                            <select class="form-control" name="eu_icon">
                                <option <?php if ($deal['eu_icon_status'] == '1') {
                                    echo 'selected';
                                } else {
                                    echo '';
                                } ?> value="1">Yes
                                </option>
                                <option <?php if ($deal['eu_icon_status'] == '0') {
                                    echo 'selected';
                                } else {
                                    echo '';
                                } ?> value="0">No
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="<?= base_url('backend/merchant'); ?>" class="btn btn-success"> Back</a>
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