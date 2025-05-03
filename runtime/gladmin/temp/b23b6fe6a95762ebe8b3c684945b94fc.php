<?php /*a:9:{s:51:"/www/wwwroot/h5/view/gladmin/data/config/index.html";i:1691149246;s:48:"/www/wwwroot/h5/view/gladmin/layout/default.html";i:1679848388;s:54:"/www/wwwroot/h5/view/gladmin/data/config/announce.html";i:1690820206;s:53:"/www/wwwroot/h5/view/gladmin/data/config/upgrade.html";i:1690820220;s:52:"/www/wwwroot/h5/view/gladmin/data/config/splash.html";i:1690820218;s:51:"/www/wwwroot/h5/view/gladmin/data/config/popup.html";i:1690820212;s:55:"/www/wwwroot/h5/view/gladmin/data/config/hotsearch.html";i:1690820208;s:51:"/www/wwwroot/h5/view/gladmin/data/config/share.html";i:1690820216;s:49:"/www/wwwroot/h5/view/gladmin/data/config/tab.html";i:1691149338;}*/ ?>
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
    <div class="layuimini-main" id="app">

        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this">公告管理</li>
                <li>升级设置</li>
                <li>开屏广告</li>
                <li>弹窗配置</li>
                <li>热门搜索</li>
                <li>软件分享</li>
                <li>tab配置</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">公告内容</label>
        <div class="layui-input-block">
            <textarea name="announce" class="layui-textarea" placeholder="请输入公告内容"><?php echo appconfig('announcement','announce'); ?></textarea>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="data.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>
                <div class="layui-tab-item">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">APP版本</label>
        <div class="layui-input-block">
            <input type="number" step="1" name="version" class="layui-input" lay-verify="required" placeholder="请输入APP版本" value="<?php echo appconfig('upgrade','version'); ?>">
            <tip>填写APP版本。</tip>
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label required">升级方式</label>
        <div class="layui-input-block">
            <?php foreach(['0'=>'非强制','1'=>'强制升级'] as $key=>$val): ?>
            <input type="radio" v-model="forceupdate" name="forceupdate" lay-filter="forceupdate" value="<?php echo htmlentities($key); ?>" title="<?php echo htmlentities($val); ?>" <?php if($key==appconfig('upgrade','forceupdate')): ?>checked<?php endif; ?>>
            <?php endforeach; ?>
            <tip>强制升级用户不能点击取消。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">升级包链接</label>
        <div class="layui-input-block">
            <input type="text" name="update_url" class="layui-input" lay-verify="required" placeholder="请输入升级包链接" value="<?php echo appconfig('upgrade','update_url'); ?>">
            <tip>填写升级包链接。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">升级内容</label>
        <div class="layui-input-block">
            <input type="text" name="update_tips" class="layui-input" lay-verify="required" placeholder="请输入升级内容" value="<?php echo appconfig('upgrade','update_tips'); ?>">
            <tip>填写升级内容,换行用\n。</tip>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="data.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>

<script>
    var forceupdate = "<?php echo appconfig('upgrade','forceupdate'); ?>";
</script>
                </div>
                <div class="layui-tab-item">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">开屏状态</label>
        <div class="layui-input-block">
            <select name="splash_status">
                <option value="1" <?php if(appconfig('splash','splash_status') == 1): ?>selected<?php endif; ?>>开启</option>
                <option value="0" <?php if(appconfig('splash','splash_status') == 0): ?>selected<?php endif; ?>>关闭</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">开屏图片</label>
        <div class="layui-input-block layuimini-upload">
            <input name="splashads" class="layui-input layui-col-xs6" lay-verify="required" placeholder="请上传弹窗图片" value="<?php echo appconfig('splash','splashads'); ?>">
            <div class="layuimini-upload-btn">
                <span><a class="layui-btn" data-upload="splashads" data-upload-number="one" data-upload-exts="gif|webp|png|jpg|jpeg"><i class="fa fa-upload"></i> 上传</a></span>
                <span><a class="layui-btn layui-btn-normal" id="select_splashads" data-upload-select="splashads" data-upload-number="one"><i class="fa fa-list"></i> 选择</a></span>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">广告链接</label>
        <div class="layui-input-block">
            <input type="text" name="splash_url" class="layui-input" lay-verify="required" lay-reqtext="请输入广告链接" placeholder="请输入广告链接" value="<?php echo appconfig('splash','splash_url'); ?>">
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="data.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>
                <div class="layui-tab-item">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">弹窗状态</label>
        <div class="layui-input-block">
            <select name="popup_status">
                <option value="1" <?php if(appconfig('popup','popup_status') == 1): ?>selected<?php endif; ?>>开启</option>
                <option value="0" <?php if(appconfig('popup','popup_status') == 0): ?>selected<?php endif; ?>>关闭</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">广告方式</label>
        <div class="layui-input-block">
            <?php foreach(['external'=>'外部广告','product'=>'内部产品'] as $key=>$val): ?>
            <input type="radio" v-model="popup_type" name="popup_type" lay-filter="popup_type" value="<?php echo htmlentities($key); ?>" title="<?php echo htmlentities($val); ?>" <?php if($key==sysconfig('popup','popup_type')): ?>checked=""<?php endif; ?>>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="layui-form-item" v-if="popup_type == 'external'" v-cloak>
        <label class="layui-form-label required">弹窗图片</label>
        <div class="layui-input-block layuimini-upload">
            <input name="ads_image" class="layui-input layui-col-xs6" lay-verify="required" placeholder="请上传弹窗图片" value="<?php echo appconfig('popup','ads_image'); ?>">
            <div class="layuimini-upload-btn">
                <span><a class="layui-btn" data-upload="ads_image" data-upload-number="one" data-upload-exts="gif|webp|png|jpg|jpeg"><i class="fa fa-upload"></i> 上传</a></span>
                <span><a class="layui-btn layui-btn-normal" id="select_logo_image" data-upload-select="ads_image" data-upload-number="one"><i class="fa fa-list"></i> 选择</a></span>
            </div>
        </div>
    </div>

    <div class="layui-form-item" v-if="popup_type == 'external'" v-cloak>
        <label class="layui-form-label required">广告链接</label>
        <div class="layui-input-block">
            <input type="text" name="ads_url" class="layui-input" lay-verify="required" lay-reqtext="请输入广告链接" placeholder="请输入广告链接" value="<?php echo appconfig('popup','ads_url'); ?>">
        </div>
    </div>

    <div class="layui-form-item  layui-row layui-col-xs12" v-if="popup_type == 'external'" v-cloak>
        <label class="layui-form-label required">选择浏览器</label>
        <div class="layui-input-block">
            <select name="ads_browser">
                    <option value="0" <?php if(appconfig('popup','ads_browser') == 0): ?>selected<?php endif; ?>>内部浏览器</option>
                    <option value="1" <?php if(appconfig('popup','ads_browser') == 1): ?>selected<?php endif; ?>>外部浏览器</option>
            </select>
            <tip>调用APP内部浏览器或者手机系统浏览器</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="popup_type == 'product'" v-cloak>
        <label class="layui-form-label required">选择产品</label>
        <div class="layui-input-block layuimini-upload">
            <input name="pid" class="layui-input layui-col-xs6" lay-verify="required" placeholder="请选择产品" value="<?php echo appconfig('popup','pid'); ?>">
            <div class="layuimini-upload-btn">
                <span><a class="layui-btn layui-btn-normal" id="select_product_image" data-product-select="pid"><i class="fa fa-list"></i> 选择</a></span>
            </div>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="data.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
<script>
    layui.form.render();
    var popup_type = "<?php echo appconfig('popup','popup_type'); ?>";
    
</script>
                </div>
                <div class="layui-tab-item">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">热门搜索词</label>
        <div class="layui-input-block">
            <input type="text" name="hotword" class="layui-input" lay-verify="required" placeholder="请输入热门搜索词" value="<?php echo appconfig('search','hotword'); ?>">
            <tip>多个用英文,隔开。</tip>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="data.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>
                <div class="layui-tab-item">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">分享链接</label>
        <div class="layui-input-block">
            <input type="text" name="share_url" class="layui-input" lay-verify="required" lay-reqtext="请输入分享链接" placeholder="请输入分享链接" value="<?php echo appconfig('share','share_url'); ?>">
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="data.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>
                <div class="layui-tab-item">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">url链接</label>
        <div class="layui-input-block">
            <input type="text" name="tab_url" class="layui-input" lay-verify="required" lay-reqtext="请输入url链接" placeholder="请输入url链接" value="<?php echo appconfig('tabconfig','tab_url'); ?>">
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="data.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>