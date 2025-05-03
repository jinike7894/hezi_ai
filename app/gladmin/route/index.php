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
    return '40412 Not Found!!';
});
Route::group(function ()  {
Route::get('/','index');
Route::get('index/welcome','index/welcome');
Route::get('index/editAdmin','index/editAdmin');
Route::get('index/editPassword','index/editPassword');
Route::get('/index','index/index');
Route::get('/index/index','index/index');
Route::get('login/index','Login/index');
Route::post('login/index','Login/index');
Route::get('login/captcha','Login/captcha');
Route::get('login/out','Login/out');
Route::rule('ajax/initAdmin','Ajax/initAdmin');
Route::get('ajax/clearCache','Ajax/clearCache');
Route::post('ajax/upload','Ajax/upload');
Route::get('ajax/uploadEditor','Ajax/uploadEditor');
Route::get('ajax/getUploadFiles','Ajax/getUploadFiles');
Route::get('ajax/getproducts','Ajax/getproducts');
Route::get('admin/index','Admin/index');
Route::get('system.auth/index','system.auth/index');
Route::rule('system.auth/add','system.auth/add');
Route::rule('system.auth/authorize','system.auth/authorize');
Route::rule('system.node/index','system.node/index');
Route::rule('system.node/refreshNode','system.node/refreshNode');
Route::post('system.auth/saveAuthorize','system.auth/saveAuthorize');
Route::get('system.admin/index','system.admin/index');
Route::rule('system.admin/add','system.admin/add');
Route::rule('system.admin/edit','system.admin/edit');
Route::rule('system.admin/password','system.admin/password');
Route::get('system.config/index','system.config/index');
Route::post('system.config/save','system.config/save');
Route::get('system.menu/index','system.menu/index');
Route::rule('system.menu/add','system.menu/add');
Route::rule('system.menu/edit','system.menu/edit');
Route::post('system.menu/modify','system.menu/modify');
Route::get('system.menu/getMenuTips','system.menu/getMenuTips');
Route::get('data.product/index','data.product/index');
Route::rule('data.product/add','data.product/add');
Route::post('data.product/modify','data.product/modify');
Route::rule('data.product/edit','data.product/edit');
Route::rule('data.product/batchEdit','data.product/batchEdit');
Route::rule('data.product/copy','data.product/copy');
Route::post('data.product/delete','data.product/delete');
Route::get('data.trend/index','data.trend/index');
Route::get('data.area/index','data.area/index');
Route::get('data.channel/index','data.channel/index');
Route::get('data.channel/report','data.channel/report');
Route::get('data.sub/index','data.sub/index');
Route::get('data.data/index','data.data/index');
Route::get('data.users/index','data.users/index');
Route::rule('data.users/add','data.users/add');
Route::rule('data.users/batchAdd','data.users/batchAdd');
Route::rule('data.users/edit','data.users/edit');
Route::post('data.users/delete','data.users/delete');
Route::get('data.users/getuser','data.users/getuser');
Route::get('data.Channelcodes/index','data.Channelcodes/index');
Route::rule('data.Channelcodes/add','data.Channelcodes/add');
Route::rule('data.Channelcodes/edit','data.Channelcodes/edit');
Route::post('data.Channelcodes/delete','data.Channelcodes/delete');
Route::post('data.Channelcodes/modify','data.Channelcodes/modify');
Route::get('data.data/getchannelCode','data.data/getchannelCode');
Route::get('data.data/getchannelCode1','data.data/getchannelCode1');
Route::get('data.pgather/index','data.pgather/index');
Route::get('data.pgather/kehu','data.pgather/kehu');
Route::get('data.pgathershow/index','data.pgathershow/index');
Route::get('data.clicks/index','data.clicks/index');
Route::get('data.clicks/show','data.clicks/show');
Route::get('data.clicks/user_report','data.clicks/show');
Route::get('data.type/index','data.type/index');
Route::rule('data.type/add','data.type/add');
Route::rule('data.type/edit','data.type/edit');
Route::rule('data.type/show','data.type/show');
Route::post('data.type/delete','data.type/delete');
Route::get('data.type/getptype','data.type/getptype');
Route::get('data.category/index','data.category/index');
Route::rule('data.category/add','data.category/add');
Route::rule('data.category/edit','data.category/edit');
Route::rule('data.category/show','data.category/show');
Route::post('data.category/delete','data.category/delete');
Route::get('data.category/getpcate','data.category/getpcate');
Route::get('data.config/index','data.config/index');
Route::post('data.config/save','data.config/save');
    Route::get('data.aivipproduct/index','data.aivipproduct/index');
    Route::rule('data.aivipproduct/edit','data.aivipproduct/edit');
    Route::post('data.aivipproduct/delete','data.aivipproduct/delete');

    Route::get('data.aipointproduct/index','data.aipointproduct/index');
    Route::rule('data.aipointproduct/edit','data.aipointproduct/edit');
    Route::post('data.aipointproduct/delete','data.aipointproduct/delete');


    Route::get('data.aiuser/index','data.aiuser/index');
    Route::rule('data.aiuser/changepw','data.aiuser/changepw');
    Route::rule('data.aiuser/edit','data.aiuser/edit');


    Route::get('data.aiorder/index','data.aiorder/index');
    Route::rule('data.aiorder/correct','data.aiorder/correct');


    Route::get('data.aipayment/index','data.aipayment/index');
    Route::rule('data.aipayment/edit','data.aipayment/edit');


    Route::get('data.aiuserecord/index','data.aiuserecord/index');

    Route::get('data.aibalancebill/index','data.aibalancebill/index');

    Route::get('data.aipointsbill/index','data.aipointsbill/index');


    Route::get('data.aireport/index','data.aireport/index');
    Route::get('data.aichannelreport/index','data.aichannelreport/index');
    Route::get('data.aiactclickrecord/index','data.aiactclickrecord/index');

    Route::get('data.aiagentdata/index','data.aiagentdata/index');
    Route::get('data.aiagentwithdrawal/index','data.aiagentwithdrawal/index');
    Route::rule('data.aiagentwithdrawal/edit','data.aiagentwithdrawal/edit');
    Route::rule('data.aiagentwithdrawal/qrcode','data.aiagentwithdrawal/qrcode');

});