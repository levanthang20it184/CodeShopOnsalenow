<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

    <!--print error messages-->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        <?= $errors; ?>
    </div>
<?php endif; ?>

    <!--print custom error message-->
<?php if ($this->session->flashdata('errors')): ?>
    <div class="m-b-15">
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <p>
                <i class="icon fa fa-times"></i>
                <?php echo $this->session->flashdata('errors'); ?>
            </p>
        </div>
    </div>
<?php endif; ?>

    <!--print custom success message-->
<?php if ($this->session->flashdata('success')): ?>
    <div class="m-b-15">
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <p>
                <i class="icon fa fa-check"></i>
                <?php echo $this->session->flashdata('success'); ?>
            </p>
        </div>
    </div>
<?php endif; ?>