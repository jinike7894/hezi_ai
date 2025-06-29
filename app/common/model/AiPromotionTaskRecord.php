<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class AiPromotionTaskRecord extends \think\Model
{
    //生成佣金记录
    public static function createRecord($pid, $uid)
    {
        $promotionParams = [
            "uid" => $uid,
            "pid" => $pid,
            "create_time" => time(),
            "update_time" => time(),
        ];
        $createRes = self::create($promotionParams);
        if (!$createRes) {
            return false;
        }
        return true;
    }
    //获取推广记录
    public static function setActivityImg($uid, $img, $pid, $productdata)
    {
        $startTime = strtotime('today');
        $recordRes = self::where(["uid" => $uid, "pid" => $pid])->where("create_time", ">=", $startTime)->find();
        if ($recordRes) {
            return false;
        }
        //新建
        $promotionParams = [
            "uid" => $uid,
            "pid" => $pid,
            "task_price" => $productdata["ai_promotion_free_price"],
            "task_name" => $productdata["name"],
            "activity_img" => $img,
            "status" => 1,
            "apply_time" => 0,
            "create_time" => time(),
            "update_time" => time(),
        ];
        $createRes = self::create($promotionParams);
        return true;
    }
    public static function taskFinishNotify($id)
    {
        Db::transaction(function () use ($id) {
            // 查询记录
            $record = self::find($id);
            if (!$record) {
                throw new \Exception("任务记录不存在");
            }

            // 获取用户信息
            $userData = AiUser::where(["id" => $record->uid])->find();
            // 更新任务状态
            $record->status = 2;
            $record->apply_time = time();
            $record->save();

            // 用户添加余额
            AiUser::where(["id" => $record->uid])->inc("balance", $record->task_price)->update();

            // 添加佣金账单
            AiBalanceBill::createBill($userData, $record->task_price, "5", 1);

            // 查询佣金比例并计算佣金
            $commissionRate = SystemConfig::where(["name" => "ai_promotion_rate"])->value("value");
            $commissionPrice = round($record->task_price * $commissionRate / 100, 2);
            // 查询上级用户
            $userPidData = AiUser::where(["id" => $userData->pid])->find();
            if ($userPidData) {
                // 添加佣金分享账单
                AiBalanceBill::createBill($userPidData, $commissionPrice, "6", 1);
                // 上级用户增加佣金
                AiUser::where(["id" => $userPidData->id])->inc("balance", $commissionPrice)->update();
            }
        });
        return true;
    }

}