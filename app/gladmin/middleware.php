<?php
// 全局中间件定义文件
return [

    // Session初始化
    \think\middleware\SessionInit::class,

    // 系统操作日志
    \app\gladmin\middleware\SystemLog::class,

    // Csrf安全校验
    \app\gladmin\middleware\CsrfMiddleware::class,

    // 后台视图初始化
//    \app\gladmin\middleware\ViewInit::class,

    // 检测用户是否登录
//    \app\gladmin\middleware\CheckAdmin::class,


];
