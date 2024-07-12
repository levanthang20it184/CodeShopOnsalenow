<?php $this->load->view('backend/layout/header'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h3 class="card-title"><i class="fa fa-plus"></i>
                            <?= trans('add_new_cms') ?> </h3>
                    </div>
                    <div class="d-inline-block float-right">
                        <a href="<?= base_url('backend/cms'); ?>" class="btn btn-success"><i
                                    class="fa fa-list"></i> <?= trans('cms_list') ?></a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- For Messages -->
                    <?php $this->load->view('backend/common/messages.php') ?>

                    <?php echo form_open(base_url('backend/cms/add'), 'class="form-horizontal"'); ?>
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label"><?= trans('title') ?></label>

                        <div class="col-md-12">
                            <input type="text" value="<?php echo set_value('title'); ?>" name="title"
                                   class="form-control" id="title" placeholder="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="meta_title" class="col-md-2 control-label"><?= trans('meta_title') ?></label>

                        <div class="col-md-12">
                            <input type="text" value="<?php echo set_value('meta_title'); ?>" name="meta_title"
                                   class="form-control" id="meta_title" placeholder="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="date" class="mr-3 control-label">Type</label>
                        <select class="form-control" name="type">
                            <option value="Page">Page</option>
                            <option value="Blog">Blog</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date" class="mr-3 control-label">Date</label>

                        <input type="date" class="form-control" value="<?php echo set_value('date'); ?>" name="date" id="date">
                    </div>

                    <div class="form-group">
                        <label for="meta_title" class="mr-3 control-label">Archieved</label>

                        <input type="checkbox" value="<?php echo set_value('achieved'); ?>" name="achieved" id="achieved">
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-md-2 control-label"><?= trans('description') ?></label>

                        <div class="col-md-12">
                            <textarea rows="10" name="description" class="form-control" id="description"
                                      placeholder=""><?php echo set_value('description'); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="submit" name="submit" value="Add cms" class="btn btn-primary pull-right">
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>
<?php $this->load->view('backend/layout/footer'); ?>