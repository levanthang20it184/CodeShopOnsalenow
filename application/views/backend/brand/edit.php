<?php $this->load->view('backend/layout/header'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h3 class="card-title"><i class="fa fa-pencil"></i>
                            &nbsp; Edit <?= $title; ?> </h3>
                    </div>
                </div>
                <?php // echo "<pre>"; print_r($brand); ?>
                <div class="card-body">
                    <form method="post" id="brand_form" action="<?php echo base_url('backend/brand/update') ?>"
                          enctype="multipart/form-data">
                        <!-- For Messages -->
                        <div class="form-group">
                            <label for="dealname" class="col-md-2 control-label">Brand Name <span
                                        class="required">*</span></label>
                            <input type="hidden" name="brand_id" value="<?php echo $brand['id']; ?>">
                            <div class="col-md-12">
                                <input type="text" name="brand_name" value="<?php echo $brand['brand_name']; ?>"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dealname" class="col-md-2 control-label">Alias<span
                                        class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="alias" id="alias" value="<?php echo $brand['alias']; ?>"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dealname" class="col-md-2 control-label">Slug<span
                                        class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="slug" id="slug" value="<?php echo $brand['slug']; ?>"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dealname" class="col-md-2 control-label">Image<span
                                        class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="image" value="<?php echo $brand['image']; ?>"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dealname" class="col-md-2 control-label">Description <span
                                        class="required">*</span></label>

                            <div class="col-md-12">
                                <textarea rows="5" cols="150"
                                          name="description"><?php echo $brand['description']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Title</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="meta_title"
                                       value="<?= $brand['meta_title']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Tag</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="meta_tag"
                                       value="<?= $brand['meta_tag']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Description</label>
                            <div class="col-md-12">
                                <!-- <input type="text" class="form-controls" name="meta_description"> -->
                                <textarea name="meta_description"
                                          class="form-control"><?= $brand['meta_description']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="<?= base_url('backend/brand'); ?>" class="btn btn-success"> Back</a>
                                <button id="submit" class="btn btn-info">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>

<?php $this->load->view('backend/layout/footer'); ?>

<style>
    .ui-autocomplete {
        border: 1px solid gainsboro;
        background: aliceblue;
        width: 60%;
        padding: 10px 10px;
        list-style: none;
    }
    .ui-autocomplete > li {
        height: 35px;
        display: flex;
        align-items: center;
        padding-left: 10px
    }
    .ui-autocomplete > li:hover {
        background-color: cadetblue
    }
</style>

<script type="text/javascript">
        $(function() {

            var slugList = <?php echo json_encode($slugList); ?>;
            
            $('#slug').autocomplete({
                source: slugList
            });

            var aliasList = <?php echo json_encode($aliasList); ?>;

            $('#alias').autocomplete({
                source: aliasList
            });

            $('#submit').click(function(e) {
                if ($('#alias').val() === '') {
                    e.preventDefault();
                    $.notify('Please input alias', 'error');
                }

                const slug = $('#slug').val();

                if (!isValidSlug(slug)) {
                    e.preventDefault();
                    $.notify('Please input valid slug', 'error');
                }
            });
        });

        function isValidSlug(str) {
            const slugRegex = /^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/;
            return slugRegex.test(str);
        }
</script>