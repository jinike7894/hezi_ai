<?php /*a:2:{s:57:"/www/wwwroot/h5/view/gladmin/data/product/batch_edit.html";i:1694622270;s:48:"/www/wwwroot/h5/view/gladmin/layout/default.html";i:1679848388;}*/ ?>
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
            <label class="layui-form-label required">产品ID</label>
            <div class="layui-input-block">
                <input type="text" readonly="true" value="<?php echo (htmlentities(app('request')->param()['id'])) ? htmlentities(app('request')->param()['id']) : ''; ?>" class="layui-input" lay-verify="required" placeholder="" />
                <tip>需要批量替换连接的产品，注意仔细核对</tip>
            </div>
            
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">客户名称</label>
            <div class="layui-input-block">
                <input type="text" name="k_name" class="layui-input" lay-verify="" placeholder="请输入客户名称" value="">
                <tip>需要修改请填写</tip>
            </div>
            
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">产品链接(安卓)</label>
            <div class="layui-input-block">
                <input type="text" name="androidurl" class="layui-input" lay-verify="" placeholder="请输入产品链接,必须以http://或者https://开头" value="" />
                <tip>需要修改请填写</tip>
            </div>
            
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">产品链接(ios)</label>
            <div class="layui-input-block">
                <input type="text" name="iosurl" class="layui-input" placeholder="请输入产品链接,必须以http://或者https://开头" value="">
                <tip>需要修改请填写</tip>
            </div>
            
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="radio" name="status" class="layui-input" title="不更改" checked="true" value="3">
                <input type="radio" name="status" class="layui-input" title="禁用" value="0">
                <input type="radio" name="status" class="layui-input" title="启用" value="1">
                <input type="radio" name="status" class="layui-input" title="暂停" value="2">
            </div>
            
        </div>
        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>

    </form>
</div>
<script>
layui.use('form',function(){
    var form = layui.form;
    form.on('submit(*)',function(data){
        return false;
    })
    form.verify();
});
</script>
</body>
</html>