<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------


namespace app\gladmin\model;


use app\common\model\TimeModel;

class SystemConfig extends TimeModel
{
    //获取新人标识时间
    public static function getUserNewFlagTime(){
        return self::where(["name"=>"ai_user_newflag_time"])->value("value");
    }
}