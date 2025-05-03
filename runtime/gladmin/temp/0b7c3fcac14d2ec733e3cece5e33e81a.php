<?php /*a:2:{s:51:"/www/wwwroot/h5/view/gladmin/system/menu/index.html";i:1643556692;s:48:"/www/wwwroot/h5/view/gladmin/layout/default.html";i:1679848388;}*/ ?>
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
<link rel="stylesheet" href="/static/plugs/lay-module/treetable-lay/treetable.css?v=<?php echo time(); ?>" media="all">
<style>
    .layui-btn:not(.layui-btn-lg ):not(.layui-btn-sm):not(.layui-btn-xs) {
        height: 34px;
        line-height: 34px;
        padding: 0 8px;
    }
</style>
<div class="layuimini-container">
    <div class="layuimini-main">
        <table id="currentTable" class="layui-table layui-hide"
               data-auth-add="<?php echo auth('system.menu/add'); ?>"
               data-auth-edit="<?php echo auth('system.menu/edit'); ?>"
               data-auth-delete="<?php echo auth('system.menu/delete'); ?>"
               lay-filter="currentTable">
        </table>
    </div>
</div>
<script type="text/html" id="toolbar">
    <button class="layui-btn layui-btn-sm layuimini-btn-primary" data-treetable-refresh><i class="fa fa-refresh"></i> </button>
    <button class="layui-btn layui-btn-normal layui-btn-sm <?php if(!auth('system.menu/add')): ?>layui-hide<?php endif; ?>" data-open="system.menu/add" data-title="添加" data-full="true"><i class="fa fa-plus"></i> 添加</button>
    <button class="layui-btn layui-btn-sm layui-btn-danger <?php if(!auth('system.menu/del')): ?>layui-hide<?php endif; ?>" data-url="system.menu/del" data-treetable-delete="currentTableRenderId"><i class="fa fa-trash-o"></i> 删除</button>
</script>

</body>
</html>