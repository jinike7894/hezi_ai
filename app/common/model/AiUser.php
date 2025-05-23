<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;
use app\gladmin\model\SystemConfig;
use app\common\model\AiUseRecord;
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
    //判断用户可用金币(可用赠送+充值金币)
    public static function getUserPoints($uid)
    {
        $todayStart = strtotime("today");  // 获取今天 00:00:00 的时间戳
        $todayEnd = strtotime("tomorrow") - 1;  // 获取今天 23:59:59 的时间戳
        $UserData = AiUser::where(["id" => $uid])->field("id,username,points,free_points,vip_expiration,channelCode")->find();
        $freeLimit = 0;
        if ($UserData["free_points"] > 0) {
            //获取赠送金币使用上线
            $freePointsLimit = SystemConfig::where(["name" => "ai_points_consum_limit"])->value("value");  //每天金币消费上限
            //今日金币消费数
            $freePointsNum = AiUseRecord::where(["uid" => $uid])
                ->whereBetween("create_time", [$todayStart, $todayEnd])
                ->sum("free_points");
            //如果还达到消费上限
            if (($freePointsLimit - $freePointsNum) > 0) {
                //获取可用赠送金币额度
                $freeLimit =$freePointsLimit -$freePointsNum;
                //获取可用赠送金币数量
                if($freeLimit>$UserData["free_points"]){
                    $freeLimit = $UserData["free_points"];
                }
            }
        }
        $UserData["points"] += $freeLimit;
        return $UserData;
    }
    //查询赠送金币是否达到消费上限
    public static function getUserConsumFreePointsLimit($uid)
    {
         $todayStart = strtotime("today");  // 获取今天 00:00:00 的时间戳
        $todayEnd = strtotime("tomorrow") - 1;  // 获取今天 23:59:59 的时间戳
         //获取赠送金币使用上线
            $freePointsLimit = SystemConfig::where(["name" => "ai_points_consum_limit"])->value("value");  //每天金币消费上限
            $freePointsNum = AiUseRecord::where(["uid" => $uid])
                ->whereBetween("create_time", [$todayStart, $todayEnd])
                ->sum("free_points");
            if($freePointsLimit<=$freePointsNum){
                //达到消费上限
                return false;
            }
            return true;
    }
    //获取用户可用赠送金币额度
    public static function getUserFreePointsLimit($uid)
    {
        $todayStart = strtotime("today");  // 获取今天 00:00:00 的时间戳
        $todayEnd = strtotime("tomorrow") - 1;  // 获取今天 23:59:59 的时间戳
        $UserData = self::where(["id" => $uid])->field("id,username,points,free_points,vip_expiration,channelCode")->find();
        $freeLimit = 0;
        if ($UserData["free_points"] > 0) {
            //获取赠送金币使用上线
            $freePointsLimit = SystemConfig::where(["name" => "ai_points_consum_limit"])->value("value");//每天使用赠送金币额度
            $freePointsNum = AiUseRecord::where(["uid" => $uid])
                ->whereBetween("create_time", [$todayStart, $todayEnd])
                ->sum("free_points");//已经使用赠送金币
            if (($freePointsNum-$freePointsLimit) < 0) {
                $freeLimit = $freePointsLimit - ($freePointsNum);
            }
        }

        return $freeLimit;
    }
    //扣减points 生成账变记录 生成使用记录
    public static function pointsDec($userData, $points, $params, $aiType)
    {
        try {
      
            $useRecordRes=Db::transaction(function () use ($userData, $points, $params, $aiType) {
                $uid = $userData["id"];
                $freePoints = 0;  // 即将使用的赠送金币
                $rechargePoints = 0; // 即将使用的充值金币
                //$userPoints = self::getUserPoints($uid)["points"]; // 获取充值+赠送 可用总金币

                // 计算赠送金币和充值金币的使用情况
                //赠送金币不够了
                if ((self::getUserPoints($uid)["points"]-$points) > 0) {
                    $usePoints=0;
                    if($userData["free_points"] > self::getUserFreePointsLimit($uid)){
                        //获取即将使用得赠送金币
                        $usePoints=self::getUserFreePointsLimit($uid);
                    }else{
                         //获取即将使用得赠送金币
                        $usePoints=$userData["free_points"];
                    }
                    $rechargePoints = $points - $usePoints; // 需要使用的充值金币
                    $freePoints = $points - $rechargePoints; // 需要使用的赠送金币
                    // var_dump("使用充值金币".$rechargePoints);
                    // var_dump("使用赠送金币".$freePoints);
                }else{
                      throw new \Exception("金币不足");
                }

                // 扣除赠送金币
                if ($freePoints > 0) {
                    $userRes = self::where(["id" => $uid])->dec("free_points", $freePoints)->update();
                    if (!$userRes) {
                        throw new \Exception("赠送金币扣除失败");
                    }
                }

                // 扣除充值金币
                if ($rechargePoints > 0) {
                    $userRes = self::where(["id" => $uid])->dec("points", $rechargePoints)->update();
                    if (!$userRes) {
                        throw new \Exception("充值金币扣除失败");
                    }
                }

                // 生成账变记录
                $useRecord = [
                    "uid" => $uid,
                    "username" => $userData["username"],
                    "channelCode" => $userData["channelCode"],
                    "original_points" => $userData["points"] + $userData["free_points"],
                    "points" => $points,
                    "after_points" => ($userData["points"] + $userData["free_points"]) - $points,
                    "bill_type" => 1,
                    "points_type" => 0,
                    "create_time" => time(),
                    "update_time" => time(),
                    "operator" => $userData["username"],
                ];
                $billRes = AiPointsBill::create($useRecord);
                if (!$billRes) {
                    throw new \Exception("账单记录创建失败");
                }

                // 生成使用记录
                $useRecordParams = [
                    "uid" => $uid,
                    "ai_type" => $aiType,
                    "img" => $params["img"],
                    "img_layers" => "",
                    "ai_generate_source" => "",
                    "free_points" => $freePoints,
                    "is_use_vip" => 0,
                    "points" => $points,
                    "status" => 0,
                    "channelCode" => $userData["channelCode"],
                    "create_time" => time(),
                    "update_time" => time(),
                ];
                if(isset($params["template_id"])){
                     $useRecordParams["template_id"]=$params["template_id"];
                }
                if(isset($params["img_layers"])){
                     $useRecordParams["img_layers"]=$params["img_layers"];
                }
                $useRecordRes = AiUseRecord::create($useRecordParams);
                if (!$useRecordRes) {
                    throw new \Exception("使用记录创建失败");
                }
                return $useRecordRes;
            });

            return $useRecordRes;

        } catch (\Exception $e) {
         
            return false;
        }
    }
}

?>