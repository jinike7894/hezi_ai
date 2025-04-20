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
Route::post('ai/register','aiuserdata/registerUser');
//修改用户名 密码
Route::post('ai/updateUser','aiuserdata/updateUserData');
//账号密码登录
Route::post('ai/login','aiuserdata/loginByPasswd');
//获取用户信息
Route::get('ai/userInfo','aiuserdata/userInfo');
//获取客服
Route::get('ai/customerService','aiuserdata/customerService');
//获取充值记录
Route::get('ai/rechargeRecord','aiuserdata/rechargeRecord');
//获取ai使用记录
Route::get('ai/aiUseRecord','aiuserdata/aiUseRecord');
//删除ai使用记录
Route::post('ai/delUseRecord','aiuserdata/delUseRecord');
//获取vip产品
Route::get('ai/vipProduct','aipay/getVipProduct');
//获取点数产品
Route::get('ai/pointsProduct','aipay/getPointsProduct');
//获取支付通道
Route::get('ai/payment','aipay/getPayment');
//创建订单 获取支付链接
Route::post('ai/createOrder','aipay/createOrder');
//支付回调
Route::post('ai/payNotify','aipay/payNotify');
})->allowCrossDomain();