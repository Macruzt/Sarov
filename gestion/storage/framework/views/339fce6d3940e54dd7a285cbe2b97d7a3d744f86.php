<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo e(cbLang("page_title_login")); ?> : <?php echo e(Session::get('appname')); ?></title>
    <meta name='generator' content='CRUDBooster'/>
    <meta name='robots' content='noindex,nofollow'/>
    <link rel="shortcut icon"
          href="<?php echo e(CRUDBooster::getSetting('favicon')?asset(CRUDBooster::getSetting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png')); ?>">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo e(asset('vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="<?php echo e(asset('vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css')); ?>" rel="stylesheet" type="text/css"/>

    <!-- support rtl-->
    <?php if(in_array(App::getLocale(), ['ar', 'fa'])): ?>
        <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
        <link href="<?php echo e(asset("vendor/crudbooster/assets/rtl.css")); ?>" rel="stylesheet" type="text/css"/>
<?php endif; ?>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <link rel='stylesheet' href='<?php echo e(asset("vendor/crudbooster/assets/css/main.css")); ?>'/>
    <style type="text/css">
        .login-page, .register-page {
            background: <?php echo e(CRUDBooster::getSetting("login_background_color")?:'#dddddd'); ?> url('<?php echo e(CRUDBooster::getSetting("login_background_image")?asset(CRUDBooster::getSetting("login_background_image")):asset('vendor/crudbooster/assets/bg_blur3.jpg')); ?>');
            color: <?php echo e(CRUDBooster::getSetting("login_font_color")?:'#ffffff'); ?>  !important;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }


        .login-box, .register-box {
            margin: 2% auto;
        }

        .login-box-body {
            border-radius: 25px;
           background: rgba(0,0,0,.4);
    	   box-sizing: border-box;
	       box-shadow: 0 15px 25px rgba(0,0,0,.5);
        }

        html, body {
            overflow: hidden;
        }
    </style>
</head>

<body class="login-page">

<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo e(url('/')); ?>">
            <img title='<?php echo (Session::get('appname') == 'CRUDBooster')?"<b>CRUD</b>Booster":CRUDBooster::getSetting('appname'); ?>'
                 src='<?php echo e(CRUDBooster::getSetting("logo")?asset(CRUDBooster::getSetting('logo')):asset('vendor/crudbooster/assets/logo_crudbooster.png')); ?>'
                 style='max-width: 100%;max-height:170px'/>
        </a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">

        <?php if( Session::get('message') != '' ): ?>
            <div class='alert alert-warning'>
                <?php echo e(Session::get('message')); ?>

            </div>
        <?php endif; ?>

        <p class='login-box-msg' style="color:white;"><?php echo e(cbLang("login_message")); ?></p>
        <form autocomplete='off' action="<?php echo e(route('postLogin')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"/>
            
            <?php if(!empty(config('services.google'))): ?>

                <div style="margin-bottom:10px" class='row'>
                    <div class='col-xs-12'>

                        <a href='<?php echo e(route("redirect", 'google')); ?>' class="btn btn-primary btn-block btn-flat"><i class='fa fa-google'></i>
                            Google Login</a>

                        <hr>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="form-group has-feedback">
                <input autocomplete='off' type="text" class="form-control" name='email' required placeholder="Email"/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input autocomplete='off' type="password" class="form-control" name='password' required placeholder="Password"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div style="margin-bottom:10px" class='row'>
                <div class='col-xs-12'>
                    <button type="submit" class="btn btn-primary btn-block btn-flat"><i class='fa fa-lock'></i> <?php echo e(cbLang("button_sign_in")); ?></button>
                </div>
            </div>

            <div class='row'>
                <div class='col-xs-12' align="center"><p style="padding:10px 0px 10px 0px; color:#ffffff"><?php echo e(cbLang("text_forgot_password")); ?> <a style="color:#FCC402;"
                                href='<?php echo e(route("getForgot")); ?>'><?php echo e(cbLang("click_here")); ?></a></p></div>
            </div>
        </form>


        <br/>
        <!--a href="#">I forgot my password</a-->

    </div><!-- /.login-box-body -->

</div><!-- /.login-box -->


<!-- jQuery 2.2.3 -->
<script src="<?php echo e(asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js')); ?>"></script>
<!-- Bootstrap 3.4.1 JS -->
<script src="<?php echo e(asset('vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js')); ?>" type="text/javascript"></script>
</body>
</html>
<?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/login.blade.php ENDPATH**/ ?>