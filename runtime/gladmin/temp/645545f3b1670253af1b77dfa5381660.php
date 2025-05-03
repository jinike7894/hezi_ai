<?php /*a:2:{s:62:"/www/wwwroot/redlight/view/gladmin/data/channelcodes/edit.html";i:1691581819;s:54:"/www/wwwroot/redlight/view/gladmin/layout/default.html";i:1679848388;}*/ ?>
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
            <label class="layui-form-label">所属用户</label>
            <div class="layui-input-block">
                 <select name="uid" lay-verify="" data-select="<?php echo url('data.users/index'); ?>" data-fields="id,username" data-value="<?php echo htmlentities((isset($row['uid']) && ($row['uid'] !== '')?$row['uid']:'')); ?>">
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
            <label class="layui-form-label">x内容自动展示</label>
            <div class="layui-input-block">
                <select name="time_range" xm-select-search="" xm-select-skin="normal" xm-select="time_range">
                <?php foreach($hours as $hour): ?>
                    <option value="<?php echo htmlentities($hour); ?>" <?php if(in_array($hour, explode(',', ($row['time_range']??'')))): ?> selected <?php endif; ?>><?php echo htmlentities($hour); ?></option>
                <?php endforeach; ?>
                </select>
                <!--<input type="text" name="time_range" id="time_range" readonly="true" class="layui-input" lay-verify="" placeholder="请选则自动关闭时间,不选则表示不会自动开启" value="<?php echo isset($row['time_range']) ? htmlentities($row['time_range']) : ''; ?>" lay-reqtext="">-->
                <tip>x内容自动开启时间,配置后，在时间范围内会自动展示x内容；不设置，表示默认不展示</tip>
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
        
        <div class="layui-form-item">
            <label class="layui-form-label required">调整系数</label>
            <div class="layui-input-block">
                <input type="number" name="coefficient" class="layui-input" lay-verify="required" placeholder="请输入调整系数" value="<?php echo htmlentities((isset($row['coefficient']) && ($row['coefficient'] !== '')?$row['coefficient']:'0.62')); ?>" step="0.01" />
            <tip>开启自动调整生效</tip>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label required">CPA单价</label>
            <div class="layui-input-block">
                <input type="number" name="price" class="layui-input" lay-verify="required" placeholder="请输入CPA单价" value="<?php echo htmlentities((isset($row['price']) && ($row['price'] !== '')?$row['price']:'3')); ?>" step="0.1" />
            <tip>渠道CPA单价</tip>
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