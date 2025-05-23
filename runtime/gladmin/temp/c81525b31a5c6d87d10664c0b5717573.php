<?php /*a:2:{s:45:"/www/wwwroot/h5/view/gladmin/login/index.html";i:1679848388;s:48:"/www/wwwroot/h5/view/gladmin/layout/default.html";i:1679848388;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo sysconfig('site','site_name'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/static/gladmin/css/public.css?v=<?php echo htmlentities($version); ?>" media="all">
    <script>
        window.CONFIG = {
            ADMIN: "<?php echo htmlentities((isset($adminModuleName) && ($adminModuleName !== '')?$adminModuleName:'admin')); ?>",
            CONTROLLER_JS_PATH: "<?php echo htmlentities((isset($thisControllerJsPath) && ($thisControllerJsPath !== '')?$thisControllerJsPath:'')); ?>",
            ACTION: "<?php echo htmlentities((isset($thisAction) && ($thisAction !== '')?$thisAction:'')); ?>",
            AUTOLOAD_JS: "<?php echo htmlentities((isset($autoloadJs) && ($autoloadJs !== '')?$autoloadJs:'false')); ?>",
            IS_SUPER_ADMIN: "<?php echo htmlentities((isset($isSuperAdmin) && ($isSuperAdmin !== '')?$isSuperAdmin:'false')); ?>",
            VERSION: "<?php echo htmlentities((isset($version) && ($version !== '')?$version:'1.0.0')); ?>",
            CSRF_TOKEN: "<?php echo token(); ?>",
        };
    </script>
    <script src="/static/plugs/layui-v2.5.6/layui.all.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/plugs/require-2.3.6/require.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/config-admin.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
</head>
<body>
<link rel="stylesheet" href="/static/gladmin/css/login.css?v=<?php echo htmlentities($version); ?>" media="all">
<div class="main-body">
    <div class="login-main">
        <div class="login-top">
            <span><?php echo sysconfig('site','site_name'); ?></span>
            <span class="bg1"></span>
            <span class="bg2"></span>
        </div>
        <form class="layui-form login-bottom">
            <div class="center">

                <div class="item">
                    <span class="icon icon-2"></span>
                    <input type="text" name="username" lay-verify="required"  placeholder="请输入登录账号" maxlength="24"/>
                </div>

                <div class="item">
                    <span class="icon icon-3"></span>
                    <input type="password" name="password" lay-verify="required"  placeholder="请输入密码" maxlength="20">
                    <span class="bind-password icon icon-4"></span>
                </div>

                <div class="item">
                    <span class="icon icon-3"></span>
                    <input type="number" name="googleauth" lay-verify="required"  placeholder="请输入谷歌验证码" maxlength="6">
                </div>

                <?php if($captcha == 1): ?>
                <div id="validatePanel" class="item" style="width: 137px;">
                    <input type="text" name="captcha" placeholder="请输入验证码" maxlength="5">
                    <img id="refreshCaptcha" class="validateImg"  src="<?php echo url('login/captcha'); ?>" onclick="this.src='<?php echo url('login/captcha'); ?>?seed='+Math.random()">
                </div>
                <?php endif; ?>

            </div>
            <div class="tip">
                <span class="icon-nocheck"></span>
                <span class="login-tip">保持登录</span>
                <a href="javascript:" class="forget-password">忘记密码？</a>
            </div>
            <div class="layui-form-item" style="text-align:center; width:100%;height:100%;margin:0px;">
                <button class="login-btn" lay-submit>立即登录</button>
            </div>
        </form>
    </div>
</div>
<div class="footer">
    <?php echo sysconfig('site','site_copyright'); ?><span class="padding-5">|</span><a target="_blank" href="http://www.miitbeian.gov.cn"><?php echo sysconfig('site','site_beian'); ?></a>
</div>
</body>
</html>