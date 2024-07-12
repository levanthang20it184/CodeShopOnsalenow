<?php $this->load->view('backend/layout/header'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h3 class="card-title"><i class="fa fa-pencil"></i>
                            &nbsp;Edit <?= $title; ?></h3>
                    </div>
                    <div class="d-inline-block float-right">
                        <a href="<?= base_url('backend/banners'); ?>" class="btn btn-success"><i class="fa fa-list"></i>
                            Home List</a>
                        <!-- <a href="<?= base_url('backend/banners/add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Add New Banner</a> -->
                    </div>
                </div>
                <div class="card-body">

                    <!-- For Messages -->
                    <?php $this->load->view('backend/common/messages.php') ?>

                    <?php echo form_open_multipart(base_url('backend/banners/edit/' . $banner['id']), 'class="form-horizontal"') ?>

                    <div class="form-group">
                        <label for="username" class="col-md-2 control-label">Banner</label>
                        <div class="col-md-12">
                            <input type="file" name="image" value="<?= set_value('image'); ?>" class="form-control">
                            <img alt="banner image" src="<?= base_url('uploads/banners/') . $banner['banner_image'] ?>" width="150"
                                 style="margin-top:10px">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label"> Title</label>
                        <div class="col-md-12">
                            <input type="text" name="title" value="<?= $banner['title']; ?>" class="form-control"
                                   id="title" placeholder="Enter Title">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label"> Heading</label>
                        <div class="col-md-12">
                            <input type="text" name="heading" value="<?= $banner['banner_heading']; ?>"
                                   class="form-control" id="heading" placeholder="Enter Banner Heading">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label"> Description</label>
                        <div class="col-md-12">
                            <textarea class="form-control" name="description"><?= $banner['description']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label"> Button label</label>
                        <div class="col-md-12">
                            <input type="text" name="button_label" value="<?= $banner['button_label']; ?>"
                                   class="form-control" id="image_link" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label"> buton Link</label>
                        <div class="col-md-12">
                            <input type="text" name="button_link" value="<?= $banner['button_link']; ?>"
                                   class="form-control" id="image_link" placeholder="">
                        </div>
                    </div>


                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" name="submit" value="Update " class="btn btn-primary pull-right">
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <!-- /.box-body -->
    </div>
    </section>
    </div>
<?php $this->load->view('backend/layout/footer'); ?>