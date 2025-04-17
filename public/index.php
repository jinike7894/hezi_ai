<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

$path=isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'';
if(stripos($path,'gladmin')!==false || stripos($path,'api')!==false){
   $response = $http->run();
}else{
   $response = $http->name('index')->run();
}

//$response = $http->run();

$response->send();

$http->end($response);
