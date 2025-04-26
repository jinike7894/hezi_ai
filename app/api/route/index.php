<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::miss(function() {
    return '404 Not Found!';
});
Route::group(function ()  {
Route::get('getdata/gettoppic','getdata/gettoppic');
Route::get('getdata/getindexdata','getdata/getindexdata');
Route::get('getdata/detail','getdata/detail');
Route::post('getdata/search','getdata/search');
Route::get('getdata/gettypeproducts','getdata/gettypeproducts');
Route::post('data/tongji','data/tongji');
Route::post('data/install','data/install');
Route::get('data/getversion','data/getversion');
Route::get('data/getannounce','data/getannounce');
Route::get('data/getshare','data/getshare');
Route::rule('getdata/getsplash','getdata/getsplash');
Route::get('getdata/getpopup','getdata/getpopup');
Route::get('getdata/getwxqq','getdata/getwxqq');
Route::get('getdata/gethotkeywords','getdata/gethotkeywords');
Route::get('cron/dopic','cron/dopic');
Route::get('cron/autoc','cron/autoc');
Route::get('cron/deldata','cron/deldata');
Route::get('tongbu/index','tongbu/index');
Route::get('tongbu/dhclick','tongbu/dhclick');
Route::get('tongbu/config','tongbu/config');
//ai类接口
//设备码 注册登录
Route::post('ai/register','AiUserdata/registerUser');
//修改用户名 密码
Route::post('ai/updateUser','AiUserdata/updateUserData');
//账号密码登录
Route::post('ai/login','AiUserdata/loginByPasswd');
//获取用户信息
Route::get('ai/userInfo','AiUserdata/userInfo');
//获取客服
Route::get('ai/customerService','AiUserdata/customerService');
//获取充值记录
Route::get('ai/rechargeRecord','AiUserdata/rechargeRecord');
//获取ai使用记录
Route::get('ai/aiUseRecord','AiUserdata/aiUseRecord');
//删除ai使用记录
Route::post('ai/delUseRecord','AiUserdata/delUseRecord');
//获取是否是新用户
Route::get('ai/UserNewFlag','AiPay/getUserNewFlag');
//获取vip产品
Route::get('ai/vipProduct','AiPay/getVipProduct');
//获取点数产品
Route::get('ai/pointsProduct','AiPay/getPointsProduct');
//获取支付通道
Route::get('ai/payment','AiPay/getPayment');
//创建订单 获取支付链接
Route::post('ai/createOrder','AiPay/createOrder');
//支付回调
Route::post('ai/payNotify','AiPay/payNotify');
//获取任务中心产品
Route::get('ai/activityData','AiActivity/getActivityData');
//产品点击
Route::post('ai/clickRecord','AiActivity/clickRecord');
//任务回调
Route::post('ai/activityNotify','AiActivity/activityNotify');
//任务记录列表
Route::get('ai/activityRecord','AiActivity/getActivityRecord');
//视频脱衣
Route::post('ai/videoAi','Ai/videoAi');
//获取视频模板列表
Route::get('ai/videoTemplateData','Ai/videoTemplateData');
//获取单个视频模板列表
Route::get('ai/videoTemplateFindData','Ai/videoTemplateFindData');
//获取图片模板列表
Route::get('ai/imgTemplateData','Ai/imgTemplateData');
//获取单个图片模板列表
Route::get('ai/imgTemplateFindData','Ai/imgTemplateFindData');

//图片脱衣
Route::post('ai/imgAi','Ai/imgAi');

//自动脱衣
Route::post('ai/imgAutoAi','Ai/imgAutoAi');
//手动脱衣
Route::post('ai/imgManualAi','Ai/imgManualAi');
//获取用户推广信息
Route::get('ai/promotion','AiPromotion/getPromotionData');
//获取用户推广信息
Route::get('ai/promotionRecord','AiPromotion/getPromotionRecord');
//获取账户记录
Route::get('ai/userBalanceRecord','AiPromotion/getUserBalanceRecord');
//获取账户记录
Route::get('ai/withdrawalRecord','AiPromotion/getWithdrawalRecord');
//设置钱包
Route::post('ai/wallet','AiUserdata/setWallet');
//获取提现信息
Route::get('ai/withdrawalInfo','AiPromotion/getWithdrawalInfo');
//提现
Route::post('ai/withdrawal','AiPromotion/withdrawal');
//获取状态接口
Route::get('ai/taskStatus','Ai/getTaskStatus');
})->allowCrossDomain();