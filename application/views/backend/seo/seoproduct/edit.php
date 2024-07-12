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
                    </div>
                </div>
                <div class="card-body">
                    <!-- For Messages -->
                    <?php $this->load->view('backend/common/messages.php') ?>

                    <form method="post" action="<?php echo base_url('backend/seo/update') ?>"
                          enctype="multipart/form-data">


                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Title</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="meta_title"
                                       value="<?= $category['meta_title']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Keyword</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="meta_tag"
                                       value="<?= $category['meta_tag']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Description</label>
                            <div class="col-md-12">
                                <textarea class="form-control"
                                          name="meta_description"><?= $category['meta_description']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Meta H1</label>
                            <div class="col-md-12">
                                <textarea class="form-control"
                                          name="meta_h1"><?= $category['meta_h1']; ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="meta_type" value="<?php echo $type; ?>">
                        <div class="form-group">
                            <div class="col-md-12">
                                <!-- <a href="<?= base_url('backend/seo/'); ?>" class="btn btn-success"> Back</a> -->
                                <input type="submit" class="btn btn-info" name="submit" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>
<?php $this->load->view('backend/layout/footer'); ?>