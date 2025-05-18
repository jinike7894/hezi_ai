<?php /*a:2:{s:70:"C:\phpstudy_pro\WWW\hezi_ai\view\gladmin\data\aipointproduct\edit.html";i:1745069710;s:60:"C:\phpstudy_pro\WWW\hezi_ai\view\gladmin\layout\default.html";i:1744896165;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/static/plugs/lay-module/xm-select/static/formSeletv4.css"/>
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">

        <div class="layui-form-item">
            <label class="layui-form-label required">产品名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" class="layui-input" lay-verify="required" placeholder="请输入产品名称" value="<?php echo htmlentities((isset($row['name']) && ($row['name'] !== '')?$row['name']:'')); ?>"  />
            </div>
        </div>
        


        <div class="layui-form-item">
            <label class="layui-form-label required">vip天数</label>
            <div class="layui-input-block">
                <input type="text" name="day" class="layui-input" lay-verify="required" placeholder="请输入vip天数" value="<?php echo htmlentities((isset($row['day']) && ($row['day'] !== '')?$row['day']:'')); ?>"  />
            <tip>vip天数</tip>
            </div>
        </div>


        
        

        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>

    </form>
</div>
<script type="text/javascript">
layui.use(['form', 'layer','laydate'], function () {
        // 操作对象
        var form = layui.form
                , layer = layui.layer
                ,laydate = layui.laydate
                , $ = layui.jquery;

    //   console.log(formSelects);
       laydate.render({
            elem: '#time_range',
            range: '~',
            type: 'time',
            //format: 'HH:mm:00' //可任意组合
            done: function(value, date, endDate){
                if(date.hours > endDate.hours){
                    $('#time_range').val("");
                    parent.layer.msg("起始时间不能大于截止时间");
                    throw '起始时间不能大于截止时间';
                } else if (date.hours == endDate.hours && date.minutes > endDate.minutes) {
                    $('#time_range').val("");
                    parent.layer.msg("起始时间不能大于截止时间");
                    throw '起始时间不能大于截止时间';
                }
            }
        });

    });

</script>
</body>
</html>