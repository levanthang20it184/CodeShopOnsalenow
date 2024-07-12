<?php
if(isset($_SESSION['is_change_password']) && $_SESSION['is_change_password'] == 1):
?>
    <?php $this->load->view('backend/layout/header'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default color-palette-bo">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h3 class="card-title"><i class="fa fa-pencil"></i>
                            &nbsp; <?= trans('change_password') ?> </h3>
                    </div>
                </div>
                <div class="card-body">
                    <!-- For Messages -->
                    <?php $this->load->view('backend/common/messages.php') ?>

                    <?php echo form_open(base_url('backend/profile/password'), 'class="form-horizontal"'); ?>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label"><?= trans('new_password') ?></label>

                        <div class="col-md-12">
                            <input type="password" name="password" class="form-control" id="password" required
                                   placeholder="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_pwd" class="col-sm-3 control-label"><?= trans('confirm_password') ?></label>

                        <div class="col-md-12">
                            <input type="password" onclick="checkPassword()" name="confirm_pwd" class="form-control"
                                   required id="confirm_pwd" placeholder="">
                        </div>
                        <div id="CheckPasswordMatch"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="submit" name="submit" value="<?= trans('change_password') ?>"
                                   class="btn btn-info pull-right">
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>
    <?php $this->load->view('backend/layout/footer'); ?>
<?php endif; ?>
