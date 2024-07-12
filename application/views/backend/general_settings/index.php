<?php $this->load->view('backend/layout/header'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="card card-default color-palette-bo">
            <div class="card-header">
                <div class="d-inline-block">
                    <h3 class="card-title"><i class="fa fa-plus"></i>
                        <?= trans('general_settings') ?> </h3>
                </div>
            </div>
            <div class="card-body">
                <!-- For Messages -->
                <?php $this->load->view('backend/common/messages.php') ?>

                <?php echo form_open_multipart(base_url('backend/general_settings/add')); ?>
                <!-- Nav tabs -->
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#main" role="tab"
                           aria-controls="main" aria-selected="true"><?= trans('general_setting') ?></a>
                    </li>

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">

                    <!-- General Setting -->
                    <div role="tabpanel" class="tab-pane active" id="main">
                        <div class="form-group">
                            <label class="control-label">Logo </label><br/>
                            <?php if (!empty($general_settings['favicon'])): ?>
                                <p><img alt="setting_image" src="<?= base_url($general_settings['favicon']); ?>" class="favicon"></p>
                            <?php endif; ?>
                            <input type="file" name="favicon" accept=".png, .jpg, .jpeg, .gif, .svg, .webp">
                            <p><small class="text-success"><?= trans('allowed_types') ?>: gif, jpg, png, jpeg, webp</small>
                            </p>
                            <input type="hidden" name="old_favicon"
                                   value="<?php echo html_escape($general_settings['favicon']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Favicon Icon </label><br/>
                            <?php if (!empty($general_settings['favicon'])): ?>
                                <p><img alt="setting_image" src="<?= base_url($general_settings['favicon_icon']); ?>" class="favicon"></p>
                            <?php endif; ?>
                            <input type="file" name="favicon_icon" accept=".ico">
                            <p><small class="text-success"><?= trans('allowed_types') ?>: ico</small></p>
                            <input type="hidden" name="old_favicon"
                                   value="<?php echo html_escape($general_settings['favicon_icon']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Website <?= trans('logo') ?></label><br/>
                            <?php if (!empty($general_settings['logo'])): ?>
                                <p><img alt="setting_image" src="<?= base_url($general_settings['logo']); ?>" class="logo" width="150"></p>
                            <?php endif; ?>
                            <input type="file" name="logo" accept=".png, .jpg, .jpeg, .gif, .svg, webp">
                            <p><small class="text-success"><?= trans('allowed_types') ?>: gif, jpg, png, jpeg, webp</small>
                            </p>
                            <input type="hidden" name="old_logo"
                                   value="<?php echo html_escape($general_settings['logo']); ?>">
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?= trans('application_name') ?></label>
                            <input type="text" class="form-control" name="application_name"
                                   placeholder="application name"
                                   value="<?php echo html_escape($general_settings['application_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?= trans('copyright') ?></label>
                            <input type="text" class="form-control" name="copyright"
                                   placeholder="Copyright"
                                   value="<?php echo html_escape($general_settings['copyright']); ?>">
                        </div>
                    </div>


                    <!-- Google reCAPTCHA Setting-->
                    <h3>Google Captcha Keys</h3>
                    <div role="tabpanel" class="tab-pane active" id="reCAPTCHA">
                        <div class="form-group">
                            <label class="control-label"><?= trans('site_key') ?></label>
                            <input type="text" class="form-control" name="recaptcha_site_key" placeholder="Site Key"
                                   value="<?php echo($general_settings['recaptcha_site_key']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?= trans('secret_key') ?></label>
                            <input type="text" class="form-control" name="recaptcha_secret_key" placeholder="Secret Key"
                                   value="<?php echo($general_settings['recaptcha_secret_key']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?= trans('language') ?></label>
                            <input type="text" class="form-control" name="recaptcha_lang" placeholder="Language code"
                                   value="<?php echo($general_settings['recaptcha_lang']); ?>">
                            <a href="https://developers.google.com/recaptcha/docs/language" target="_blank">https://developers.google.com/recaptcha/docs/language</a>
                        </div>
                    </div>

                </div>

                <div class="box-footer">
                    <input type="submit" name="submit" value="<?= trans('save_changes') ?>"
                           class="btn btn-primary pull-right">
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('backend/layout/footer'); ?>
<script>
    $("#setting").addClass('active');
    $('#myTabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
</script>
