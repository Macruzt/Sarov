<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="<?php echo e(url(config('crudbooster.ADMIN_PATH'))); ?>" title='<?php echo e(Session::get('appname')); ?>' class="logo"><?php echo e(CRUDBooster::getSetting('appname')); ?></a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title='Notifications' aria-expanded="false">
                        <i id='icon_notification' class="fa fa-bell-o"></i>
                        <span id='notification_count' class="label label-danger" style="display:none">0</span>
                    </a>
                    <ul id='list_notifications' class="dropdown-menu">
                        <li class="header"><?php echo e(cbLang("text_no_notification")); ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
                                <ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                                    <li>
                                        <a href="#">
                                            <em><?php echo e(cbLang("text_no_notification")); ?></em>
                                        </a>
                                    </li>

                                </ul>
                                <div class="slimScrollBar"
                                     style="width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 195.122px; background: rgb(0, 0, 0);"></div>
                                <div class="slimScrollRail"
                                     style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div>
                            </div>
                        </li>
                        <li class="footer"><a href="<?php echo e(route('NotificationsControllerGetIndex')); ?>"><?php echo e(cbLang("text_view_all_notification")); ?></a></li>
                    </ul>
                </li>

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="<?php echo e(CRUDBooster::myPhoto()); ?>" class="user-image" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?php echo e(CRUDBooster::myName()); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="<?php echo e(CRUDBooster::myPhoto()); ?>" class="img-circle" alt="User Image"/>
                            <p>
                                <?php echo e(CRUDBooster::myName()); ?>

                                <small><?php echo e(CRUDBooster::myPrivilegeName()); ?></small>
                                <small><em><?php echo date('d F Y')?></em></small>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-<?php echo e(cbLang('left')); ?>">
                                <a href="<?php echo e(route('AdminCmsUsersControllerGetProfile')); ?>" class="btn btn-default btn-flat"><i
                                            class='fa fa-user'></i> <?php echo e(cbLang("label_button_profile")); ?></a>
                            </div>
                            <div class="pull-<?php echo e(cbLang('right')); ?>">
                                <a title='Lock Screen' href="<?php echo e(route('getLockScreen')); ?>" class='btn btn-default btn-flat'><i class='fa fa-key'></i></a>
                                <a href="javascript:void(0)" onclick="swal({
                                        title: '<?php echo e(cbLang('alert_want_to_logout')); ?>',
                                        type:'info',
                                        showCancelButton:true,
                                        allowOutsideClick:true,
                                        confirmButtonColor: '#DD6B55',
                                        confirmButtonText: '<?php echo e(cbLang('button_logout')); ?>',
                                        cancelButtonText: '<?php echo e(cbLang('button_cancel')); ?>',
                                        closeOnConfirm: false
                                        }, function(){
                                        location.href = '<?php echo e(route("getLogout")); ?>';

                                        });" title="<?php echo e(cbLang('button_logout')); ?>" class="btn btn-danger btn-flat"><i class='fa fa-power-off'></i></a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<?php /**PATH /home/u923093801/domains/tuzonacomercial.com/public_html/sarov/vendor/crocodicstudio/crudbooster/src/views/header.blade.php ENDPATH**/ ?>