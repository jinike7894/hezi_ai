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
})->allowCrossDomain();