<?php $this->load->view('backend/layout/header', $data); ?>
<div class="form-background">
    <div class="login-box">
        <div class="login-logo">
            <h2><a href="<?= base_url('admin'); ?>">ONSALENOW</a></h2>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Signin</p>

                <?php $this->load->view('backend/common/messages.php') ?>

                <?php echo form_open(base_url('backend/auth/login'), 'class="login-form" '); ?>
                <div class="form-group has-feedback">
                    <input type="text" name="username" id="name" class="form-control"
                           placeholder="<?= trans('username') ?>">
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="<?= trans('password') ?>">
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="checkbox icheck">
                            <label>
                                <a href="<?= base_url('admin/auth/forgot_password'); ?>"><?= trans('i_forgot_my_password') ?></a>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-block btn-flat"
                               value="<?= trans('signin') ?>">
                    </div>
                    <!-- /.col -->
                </div>
                <?php echo form_close(); ?>

                <p class="mb-1">
                    <!-- <a href="<?= base_url('admin/auth/forgot_password'); ?>"><?= trans('i_forgot_my_password') ?></a> -->
                </p>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
</div>
<?php $this->load->view('backend/layout/footer', $data); ?>