<?php $this->load->view('backend/layout/header'); ?>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">
<!-- DataTables -->
<div class="content-wrapper">
    <section class="content">
        <!-- For Messages -->
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"><i class="fa fa-eye"></i>
                    &nbsp;View <?= $title; ?> </h3>
            </div>
        </div>

        <div class="row">
            <?php foreach ($contact as $key => $value): ?>

                <div class="col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <b><?= ucwords(str_replace('_', ' ', $key)); ?> : </b>
                            <?= ucfirst($value); ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>


        </div>

        <div class="form-group">
            <div class="col-md-12">
                <a href="<?= base_url('backend/contact'); ?>" class="btn btn-success"> Back</a>
            </div>
        </div>


    </section>
</div>

<?php $this->load->view('backend/layout/footer'); ?>
