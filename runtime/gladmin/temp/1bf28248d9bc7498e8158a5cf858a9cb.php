<?php /*a:2:{s:61:"/www/wwwroot/daohang/view/gladmin/data/channelcodes/edit.html";i:1688382714;s:53:"/www/wwwroot/daohang/view/gladmin/layout/default.html";i:1679848387;}*/ ?>
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
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">
        <div class="layui-form-item">
            <label class="layui-form-label required">所属用户</label>
            <div class="layui-input-block">
                 <select name="uid" lay-verify="required" data-select="<?php echo url('data.users/index'); ?>" data-fields="id,username" data-value="<?php echo htmlentities((isset($row['uid']) && ($row['uid'] !== '')?$row['uid']:'')); ?>">
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">渠道号</label>
            <div class="layui-input-block">
                <input type="text" name="channelCode" class="layui-input" lay-verify="required" placeholder="请输入渠道号,不能重复" value="<?php echo htmlentities((isset($row['channelCode']) && ($row['channelCode'] !== '')?$row['channelCode']:'')); ?>" readonly="readonly" />
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">每日保底</label>
            <div class="layui-input-block">
                <input type="number" name="mininum" class="layui-input" lay-verify="required" placeholder="请输入每日保底,不扣量则输入0" value="<?php echo htmlentities((isset($row['mininum']) && ($row['mininum'] !== '')?$row['mininum']:'0')); ?>" step="1" />
            <tip>每日达到保底数值才开始扣量</tip>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">扣量比例</label>
            <div class="layui-input-block">
                <input type="number" name="ratio" class="layui-input" lay-verify="required" placeholder="请输入扣量比例,不扣则输入0" value="<?php echo htmlentities((isset($row['ratio']) && ($row['ratio'] !== '')?$row['ratio']:'0')); ?>" step="1" />
            <tip>每日达到保底数值后按此比例随机扣除</tip>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注信息</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea" placeholder="请输入备注信息"><?php echo htmlentities((isset($row['remark']) && ($row['remark'] !== '')?$row['remark']:'')); ?></textarea>
            </div>
        </div>

        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>

    </form>
</div>
</body>
</html>