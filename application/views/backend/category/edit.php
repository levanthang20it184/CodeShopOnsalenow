<?php $this->load->view('backend/layout/header'); ?>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>">
    <script src="<?php echo base_url('assets/js/sweetalert2.all.min.js'); ?>"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h3 class="card-title"><i class="fa fa-pencil"></i>
                            &nbsp;View <?= $title; ?> </h3>
                    </div>
                    <div class="d-inline-block float-right">
                    </div>
                </div>
                <div class="card-body">
                    <!-- For Messages -->
                    <?php $this->load->view('backend/common/messages.php') ?>

                    <form method="post" action="<?php echo base_url('backend/category/update') ?>"
                          enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-md-2 control-label">Name</label>
                            <div class="col-md-12">
                                <input type="text" name="name" class="form-control" id="name"
                                       value="<?= $category['name']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-md-2 control-label">Sub categories</label>
                            <div class="col-md-12" style="display: table">

                                <?php

                                foreach ($subCategories as $subCategory) {
                                    echo '<div id="subCategory_' . $subCategory['id'] . '" class="dropdown" style="display: flex; float: left; margin-right: 10px; margin-bottom: 10px">
                      <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">' .
                                        $subCategory["name"] .
                                        '</button>
                      <div class="dropdown-menu">
                          <a class="dropdown-item" style="cursor: pointer" onclick="renameSubCategory(' . $subCategory['id'] . ')">Rename</a>
                          <a class="dropdown-item" style="cursor: pointer" onclick="deleteSubCategory(' . $subCategory['id'] . ')">Delete</a>
                      </div>
                  </div>';
                                }

                                ?>

                                <div class="dropdown"
                                     style="display: flex; float: left; margin-right: 10px; margin-bottom: 10px">
                                    <button type="button" class="btn btn-danger btn-add"
                                            onclick="addSubCategory(<?php echo $category['id'] ?>)"
                                            data-toggle="dropdown">+ Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                        <div class="form-group">
                            <label for="image" class="col-md-2 control-label"><?= trans('image') ?></label>
                            <div class="col-md-12">
                                <img alt="category image" width="100px" src="<?= $this->config->item('category') . $category['image']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Image Update</label>
                            <div class="col-md-12">
                                <input type="file" class="form-control" name="file">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="price" class="col-md-2 control-label">Created Date<span
                                        class="required">*</span></label>
                            <div class="col-md-12">
                                <input type="text" name="max_price" value="<?php echo $category['created_at']; ?>"
                                       class="form-control" disabled="disabled">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Title</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="meta_title"
                                       value="<?= $category['meta_title']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Tag</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="meta_tag"
                                       value="<?= $category['meta_tag']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-md-2 control-label">Meta Description</label>
                            <div class="col-md-12">
                                <!-- <input type="text" class="form-controls" name="meta_description"> -->
                                <textarea class="form-control"
                                          name="meta_description"><?= $category['meta_description']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="<?= base_url('backend/category'); ?>" class="btn btn-success"> Back</a>
                                <input type="submit" class="btn btn-info" name="submit" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>

    <script>
        function deleteSubCategory(subCategoryId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('<?= base_url("backend/Category/removeSubCategory") ?>', {
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                            subCategoryId
                        },
                        function (data) {
                            $.notify(data, "success");
                            $('#subCategory_' + subCategoryId).remove();
                        });
                } else {
                    // User clicked cancel or closed the dialog box
                    // Do nothing or show a message to the user...
                }
            })
        }

        function renameSubCategory(subCategoryId) {
            const oldName = $('#subCategory_' + subCategoryId).find('.btn').text();

            Swal.fire({
                title: "Rename Sub Category",
                input: "text",
                inputValue: oldName,
                showCancelButton: true,
                confirmButtonText: "Rename",
            }).then((result) => {
                if (result.isConfirmed) {
                    const newName = result.value;

                    $.post('<?= base_url("backend/Category/renameSubCategory") ?>', {
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                            subCategoryId,
                            newName
                        },
                        function (data) {
                            $.notify(data, "success");
                            $('#subCategory_' + subCategoryId).find('.btn').html(newName);
                        });
                }
            });
        }

        function addSubCategory(categoryId) {
            Swal.fire({
                title: "Add Sub Category",
                input: "text",
                inputValue: '',
                showCancelButton: true,
                confirmButtonText: "Add",
            }).then((result) => {
                if (result.isConfirmed) {
                    const newName = result.value;

                    $.post('<?= base_url("backend/Category/addSubCategory") ?>', {
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                            categoryId,
                            newName
                        },
                        function (data) {
                            data = JSON.parse(data);

                            $('.btn-add').parent().before('<div id="subCategory_' + data[1] + '" class="dropdown" style="display: flex; float: left; margin-right: 10px; margin-bottom: 10px">' +
                                '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">' + newName + '</button>' +
                                '<div class="dropdown-menu">' +
                                '<a class="dropdown-item" style="cursor: pointer" onclick="renameSubCategory(' + data[1] + ')">Rename</a>' +
                                '<a class="dropdown-item" style="cursor: pointer" onclick="deleteSubCategory(' + data[1] + ')">Delete</a>' +
                                '</div>' +
                                '</div>');

                            $.notify(data[0], "success");

                        });
                }
            });
        }
    </script>

<?php $this->load->view('backend/layout/footer'); ?>