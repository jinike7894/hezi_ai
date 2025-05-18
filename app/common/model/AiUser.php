<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
use app\common\model\AiPointsBill;
class AiUser extends \think\Model
{
    // 设置字段信息
    // protected $schema = [
    //     'id'          => 'int',
    //     'username'          => 'string',
    //     'plain_passwd'          => 'string',
    //     'passwd'          => 'string',
    //     'unique_code'          => 'string',
    //     'vip_expiration'          => 'int',
    //     'points'          => 'int',
    //     'model'          => 'string',
    //     'is_update'          => 'int',
    //     "channelCode"=>"string",
    //     'create_time' => 'int',
    //     'update_time' => 'int',
    //     'delete_time' => 'int',
    // ];
    //扣减points 生成账变记录
    public static function  pointsDec($userData,$points){
        $userRes=self::where(["id"=>$userData["id"]])
        ->dec("points",$points)
        ->update();

        if(!$userRes){
            return false;
        }
        //生成账变记录
        $useRecord=[
            "uid"=>$userData["id"],
            "username"=>$userData["username"],
            "channelCode"=>$userData["channelCode"],
            "original_points"=>$userData["points"],
            "points"=>$points,
            "after_points"=>$userData["points"]-$points,
            "bill_type"=>1,
            "points_type"=>0,
            "create_time"=>time(),
            "update_time"=>time(),
            "operator"=>$userData["username"],
        ];
        $billRes=AiPointsBill::create($useRecord);
        if(!$billRes){
            return false;
        }
        return true;
    }
}

?>