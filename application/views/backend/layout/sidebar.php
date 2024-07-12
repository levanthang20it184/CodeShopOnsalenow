<?php
$cur_tab = $this->uri->segment(2) == '' ? 'dashboard' : $this->uri->segment(2);
$logo_data = web_logo();
?>

<style type="text/css">
    img.brand-image.img-circle.elevation-3 {
        width: 80%;
        border-radius: 0;
    }

    a.brand-link {
        display: inline-block;
        width: 100%;
    }
</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('backend/dashboard'); ?>" class="brand-link">
        <img src="<?= base_url() . $logo_data->logo ?>" style="object-fit: cover" alt="Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url() ?>assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?= ucwords($this->session->userdata('username')); ?>
                </a>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <?php
                $menu = get_sidebar_menu();
                // echo "<pre>"; print_r($menu); die;
                foreach ($menu as $nav):

                    $sub_menu = get_sidebar_sub_menu($nav['module_id']);

                    $has_submenu = (count($sub_menu) > 0) ? true : false;
                    ?>

                    <?php //if($this->rbac->check_module_permission($nav['controller_name'])):
                    ?>

                <li id="<?= ($nav['controller_name']) ?>"
                    class="nav-item <?= ($has_submenu) ? 'has-treeview' : '' ?> has-treeview">

                    <a href="<?= base_url('backend/' . $nav['controller_name']) ?>" class="nav-link">
                        <i class="nav-icon fa <?= $nav['fa_icon'] ?>"></i>
                        <p>
                            <?= trans($nav['module_name']) ?>
                            <?= ($has_submenu) ? '<i class="right fa fa-angle-right"></i>' : '' ?>
                        </p>
                    </a>

                    <!-- sub-menu -->
                    <?php
                    if ($has_submenu):
                        ?>
                        <ul class="nav nav-treeview">

                            <?php foreach ($sub_menu as $sub_nav):
                                $controller = $sub_nav['controller'] ? $nav['controller_name'] . '/' . $sub_nav['controller'] . '/' . $sub_nav['controller'] : $nav['controller_name'];
                                ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('backend/' . $controller . '/' . $sub_nav['link']); ?>"
                                       class="nav-link">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p><?= trans($sub_nav['name']) ?></p>
                                    </a>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                        <?php //endif;
                        ?>
                        <!-- /sub-menu -->
                        </li>

                    <?php endif; ?>

                <?php endforeach; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
    $("#<?= $cur_tab ?>").addClass('menu-open');
    $("#<?= $cur_tab ?> > a").addClass('active');
</script>