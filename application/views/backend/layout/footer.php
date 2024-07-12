<?php if (!isset($footer)): ?>
    <footer class="main-footer">
        <strong><?= general_settings('copyright'); ?></strong>
        <div class="float-right d-none d-sm-inline-block">
        </div>
    </footer>
<?php endif; ?>


<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->


</div>
<!-- ./wrapper -->


<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Slimscroll -->
<script src="<?= base_url() ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= base_url() ?>assets/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>assets/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>assets/dist/js/demo.js"></script>
<!-- Notify JS -->
<script src="<?= base_url() ?>assets/plugins/notify/notify.min.js"></script>
<!-- DROPZONE -->
<script src="<?= base_url() ?>assets/plugins/dropzone/dropzone.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/plugins/tiny_mce/tinymce.js"></script>
<script src="<?= base_url() ?>assets/plugins/tiny_mce/addediter.js"></script>


<script src="<?= base_url() ?>assets/plugins/dropzone/dropzone.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datetimepicker/jquery.datetimepicker.full.min.js"></script>

<script>
    $.datetimepicker.setDateFormatter({
        parseDate: function (date, format) {
            var d = moment(date, format);
            return d.isValid() ? d.toDate() : false;
        },
        formatDate: function (date, format) {
            return moment(date).format(format);
        },
    });
    $('#license_date').datetimepicker({
        //  format:'Y-m-d H:i',
        // format: 'YYYY-MM-DD HH:mm',
        format: 'YYYY-MM-DD',
        formatTime: 'HH:mm:ss',
        formatDate: 'YYYY-MM-DD',

    });

    $(document).ready(function () {
        $('img').attr('onerror', 'this.onerror=null;this.src=`/assets/images/no-image.png`;');
    });
</script>

<script>
    var csfr_token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csfr_token_value = '<?php echo $this->security->get_csrf_hash(); ?>';

</script>

</body>
</html>
