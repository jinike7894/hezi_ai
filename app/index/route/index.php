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
//同步免杀渠道号
Route::get('syncQd','index/syncQd');
// Route::get('index/test','index/test');
Route::get('index/epass','index/epass');
Route::get('/:channel','H5/index')->pattern(['channel' => '\d+(_\d+)?$']);
Route::get('/abc/:channel','H5/index1')->pattern(['channel' => '\d+(_\d+)?$']);

Route::get('/pv/:channel','H5/statistics')->pattern(['channel' => '\d+(_\d+)?$']);
Route::get('/down/pv/:channel','H5/down_statistics')->pattern(['channel' => '\d+(_\d+)?$']);
Route::get('/downauto/pv/:channel','H5/down_auto_statistics')->pattern(['channel' => '\d+(_\d+)?$']);



Route::get('/detail/:channel','H5/detail')->pattern(['channel' => '\d+(_\d+)?$']);
Route::get('/detail2/:channel','H5/detail2')->pattern(['channel' => '\d+(_\d+)?$']);


Route::post('api/tongji','Api/tongji');
Route::post('/getip','index/getip')->allowCrossDomain();
Route::get('/getip','index/getip1')->allowCrossDomain();

Route::get('/:category-:pag-:channel', 'H5/index')->pattern([
    'category' => '[a-zA-Z0-9-]+',
    'pag' => '\d+',
    'channel' => '\d+'
]);