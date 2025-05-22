<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use app\gladmin\model\SystemConfig;
use app\common\model\AiUser;
use think\facade\Db;
class AiActivityRecord extends \think\Model
{
    //获取用户今日金币数
    public static function getActivityPoints($uid){
        $pointsData=self::where(["uid"=>$uid])->whereTime('create_time', 'today')->sum("points");
        return $pointsData;
    }
    //任务完成回调
    public static function activityNotify($uid,$pid)
    {
        $params = [
            "pid" => $pid,
        ];
         $productData = Products::where(["id" => $params["pid"]])->field("id,name,ai_activity_switch,ai_activity_free_points,ai_activity_update_switch")->find();
        if ($productData["ai_activity_switch"] != 1 || !$productData) {
            return ["code" => 0, "msg" => "产品错误"];
        }
        //判断ai_activity_update_switch是否有开关
        if ($productData["ai_activity_update_switch"] == 1) {
            //判断今天是否做过一次
            $todayStart = strtotime(date('Y-m-d 00:00:00')); // 获取当天零点时间戳
            $todayEnd = strtotime(date('Y-m-d 23:59:59'));  // 获取当天最后一秒时间戳
            $recordData = AiActivityRecord::where([
                "pid" => $params["pid"],
                "uid" => $uid
            ])->where("create_time", ">", $todayStart)
                ->where("create_time", "<=", $todayEnd)
                ->find();
        } else {
            //查询记录已经存在 则不提交
            $recordData = AiActivityRecord::where(["pid" => $params["pid"], "uid" => $uid])->find();
        }

        if ($recordData) {
             return ["code" => 0, "msg" => "任务已完成"];
        }
        $userData = AiUser::where(['id' => $uid])->field("id,username,points,channelCode")->find();
        try {
            Db::transaction(function () use ($uid, $productData, $params, $userData) {
                // 增加用户点数
                $userRes = AiUser::where(['id' => $uid])
                    ->inc("free_points", $productData["ai_activity_free_points"])
                    ->update();
                if (!$userRes) {
                   throw new \Exception("用户点数更新失败");
                }
                //生成points账单变记录
                $pointsBillParams = [
                    "uid" => $uid,
                    "username" => $userData["username"],
                    "channelCode" => $userData["channelCode"],
                    "original_points" => $userData["points"],
                    "points" => $productData["ai_activity_free_points"],
                    "after_points" => $userData["points"] + $productData["ai_activity_free_points"],
                    "bill_type" => 0,
                    "points_type" => 1,
                    "create_time" => time(),
                    "update_time" => time(),

                ];
                AiPointsBill::create($pointsBillParams);
                // 生成做任务表记录
                $activityRecord = [
                    "name" => $productData["name"],
                    "pid" => $params["pid"],
                    "uid" => $uid,
                    "points" => $productData["ai_activity_free_points"],
                    "channelCode" => $userData["channelCode"],
                    "create_time" => time(),
                    "update_time" => time(),
                ];
                $recordRes = AiActivityRecord::create($activityRecord);
                if (!$recordRes) {
                    throw new \Exception("生成记录失败");
                }
            });
        } catch (\Exception $e) {
              return ["code" => 0, "msg" => $e->getMessage()];
        }
        return ["code" => 1, "msg" => "succ"];
    }
}