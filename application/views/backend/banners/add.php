<?php $this->load->view('backend/layout/header'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h3 class="card-title"><i class="fa fa-plus"></i>
                            Add New <?= $title; ?> </h3>
                    </div>
                    <div class="d-inline-block float-right">
                        <a href="<?= base_url('backend/banners'); ?>" class="btn btn-success"><i
                                    class="fa fa-list"></i> <?= $title; ?> List</a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- For Messages -->
                    <?php $this->load->view('backend/common/messages.php') ?>
                    <?php echo form_open_multipart(base_url('backend/banners/add'), 'class="form-horizontal"'); ?>

                    <div class="form-group">
                        <label for="username" class="col-md-2 control-label">Banner</label>
                        <div class="col-md-12">
                            <input type="file" name="image" value="<?= set_value('image'); ?>" class="form-control">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label">Slogan title</label>
                        <div class="col-md-12">
                            <input type="text" name="title" value="" class="form-control" id="title"
                                   placeholder="Enter Title">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label">Banner Heading</label>
                        <div class="col-md-12">
                            <input type="text" name="heading" value="" class="form-control" id="heading"
                                   placeholder="Enter Heading">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label">Slogan Description</label>
                        <div class="col-md-12">
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" name="submit" value="Add Banner" class="btn btn-primary pull-right">
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <!-- /.box-body -->
    </div>
    </section>
    </div>
<?php $this->load->view('backend/layout/footer'); ?>