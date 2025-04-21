<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
use app\common\model\AiUser;
class AiActivityRecord extends \think\Model
{
    //获取用户今日金币数
    public static function getActivityPoints($uid){
        $pointsData=self::where(["uid"=>$uid])->whereTime('create_time', 'today')->sum("points");
        return $pointsData;
    }
}