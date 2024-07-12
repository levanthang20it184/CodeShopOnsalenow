<?php $this->load->view('backend/layout/header'); ?>
    <style>
        div#Localdeal, #Localdispensary, #Localdelivery, #services, #brands {
            display: none;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h3 class="card-title"><i class="fa fa-plus"></i>
                            Edit Menu </h3>
                    </div>
                    <div class="d-inline-block float-right">
                    </div>
                </div>
                <div class="card-body">

                    <!-- For Messages -->
                    <?php $this->load->view('backend/common/messages.php') ?>
                    <?php echo form_open_multipart(base_url('backend/menus/update'), 'class="form-horizontal"'); ?>
                    <input type="hidden" name="id" value="<?= $menu['id']; ?>">
                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label">Menu Name</label>
                        <div class="col-md-12">
                            <input type="text" name="name" value="<?= $menu['name']; ?>" class="form-control" id="name"
                                   placeholder="Enter Name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label">CMS Page Link</label>
                        <div class="col-md-12">
                            <!-- <input type="text" name="link" value="<?= $menu['llink']; ?>" class="form-control"  id="link" placeholder="Enter Link"> -->
                            <select name="link" class="form-control">
                                <option selected="" disabled="">Select CMS Page Link</option>
                                <?php foreach ($links as $link): ?>
                                    <option value="<?= $link['slug']; ?>"><?= $link['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <h4 style="text-align: center; font-weight: Bold">OR</h4>
                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label">Static Link</label>
                        <div class="col-md-12">
                            <input type="text" name="static_link" value="<?= $menu['static_link']; ?>"
                                   class="form-control" id="static_link" placeholder="Enter Static Link">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-2 control-label">Status</label>
                        <div class="col-md-12">
                            <select class="form-control" name="status">
                                <option value="">Select Status</option>
                                <option <?php if ($menu['status'] == 1) {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="1">Active
                                </option>
                                <option <?php if ($menu['status'] == 2) {
                                    echo "selected";
                                } else {
                                    echo "";
                                } ?> value="2">Inactive
                                </option>
                            </select>
                        </div>
                    </div>


                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <input type="submit" name="submit" value="Update Menu" class="btn btn-primary pull-right">
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <!-- /.box-body -->
    </div>
    </section>
    </div>
<?php $this->load->view('backend/layout/footer'); ?>