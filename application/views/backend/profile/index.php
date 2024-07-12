<?php $this->load->view('backend/layout/header'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default color-palette-bo">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-pencil"></i>
                        &nbsp; <?= trans('profile') ?> </h3>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?= base_url('backend/profile/change_pwd'); ?>" class="btn btn-success"><i
                                class="fa fa-list"></i> <?= trans('change_password') ?></a>
                </div>
            </div>
            <div class="card-body">
                <!-- For Messages -->
                <?php $this->load->view('backend/common/messages.php') ?>

                <?php echo form_open_multipart(base_url('backend/profile'), 'class="form-horizontal"') ?>
                <div class="form-group">
                    <label for="brand" class="col-md-2 control-label">Profile</label>
                    <div class="col-md-12">
                        <input type="file" name="image" class="form-control">
                        <?php if (!empty($admin['image'])) { ?>
                            <img alt="profile image" src="<?= base_url('uploads/users/') . $admin['image'] ?>" width="110"
                                 style="margin-top:10px">
                        <?php } else { ?>
                            <img alt="profile image" src="<?= base_url('uploads/users/profile.jpg') ?>" width="110" style="margin-top:10px">
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label"><?= trans('username') ?></label>

                    <div class="col-md-12">
                        <input type="text" name="username" value="<?= $admin['username']; ?>" class="form-control"
                               id="username" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstname" class="col-sm-2 control-label"><?= trans('firstname') ?></label>

                    <div class="col-md-12">
                        <input type="text" name="firstname" value="<?= $admin['firstname']; ?>" class="form-control"
                               id="firstname" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label"><?= trans('lastname') ?></label>

                    <div class="col-md-12">
                        <input type="text" name="lastname" value="<?= $admin['lastname']; ?>" class="form-control"
                               id="lastname" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label"><?= trans('email') ?></label>

                    <div class="col-md-12">
                        <input type="email" name="email" value="<?= $admin['email']; ?>" class="form-control" id="email"
                               placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="mobile_no" class="col-sm-2 control-label">Country</label>

                    <div class="col-md-12">
                        <select name="phonecode" value="<?= set_value('phonecode'); ?>" class="form-control">
                            <option value="">Select a country</option>
                            <?php foreach ($countries as $country) { ?>
                                <option <?= ($admin['phonecode'] == $country['phonecode']) ? 'selected="selected"' : ''; ?>
                                        value="<?= $country['phonecode']; ?>"><?= $country['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="mobile_no" class="col-sm-2 control-label"><?= trans('mobile_no') ?></label>

                    <div class="col-md-12">
                        <input type="number" name="mobile_no" value="<?= $admin['mobile_no']; ?>" class="form-control"
                               id="mobile_no" placeholder="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="mobile_no" class="col-sm-2 control-label">Facebook</label>
                    <div class="col-md-12">
                        <input type="text" name="facebook" value="<?= $admin['fb']; ?>" class="form-control"
                               id="facebook">
                    </div>
                </div>

                <div class="form-group">
                    <label for="mobile_no" class="col-sm-2 control-label">Twitter</label>
                    <div class="col-md-12">
                        <input type="text" name="twitter" value="<?= $admin['tw']; ?>" class="form-control"
                               id="twitter">
                    </div>
                </div>

                <div class="form-group">
                    <label for="mobile_no" class="col-sm-2 control-label">Instagram</label>
                    <div class="col-md-12">
                        <input type="text" name="instagram" value="<?= $admin['insta']; ?>" class="form-control"
                               id="instagram">
                    </div>
                </div>
                <div class="form-group">
                    <label for="mobile_no" class="col-sm-2 control-label">Youtube</label>
                    <div class="col-md-12">
                        <input type="text" name="youtube" value="<?= $admin['ytube']; ?>" class="form-control"
                               id="youtube">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" name="submit" value="<?= trans('update_profile') ?>"
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