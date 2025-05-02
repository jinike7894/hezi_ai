<?php
namespace app\api\controller;

use app\api\controller\AiBase;
use app\common\model\Products;

use think\facade\Db;
use app\common\model\AiUser;
use app\common\model\AiBalanceBill;
use app\common\model\AiWithdrawalRecord;
use app\common\model\AiPromotion as AiPromotionModel;
use app\gladmin\model\SystemConfig;
class AiPromotion extends AiBase
{
    //获取推广数据
    public function getPromotionData()
    {
        $uid = $this->uid;
        $responseParams = [];
        $userData = AiUser::where(["id" => $uid])->field("id,username,commission,balance,channelCode")->find();
        $responseParams["commission"] = $userData["commission"];
        $responseParams["balance"] = $userData["balance"];
        //今日收益
        $responseParams["today_income"] = AiBalanceBill::where(["amount_type" => 1,"uid"=>$uid])->whereTime("create_time", "today")->sum("amount");
        //累计收益
        $responseParams["total_income"] = AiBalanceBill::where(["amount_type" => 1,"uid"=>$uid])->sum("amount");
        //成功邀请
        $responseParams["total_invite"] = AiUser::where(["pid" => $uid])->count();
        //推广链接
        $system = new SystemConfig();
        $land_host = $system
        ->where('name', "ai_land_host")
        ->value("value");
        //推广链接
        $responseParams["promotion_host"]=$land_host."?pid=".$uid."&channelCode=".$userData["channelCode"];
        return json_encode(["code" => 1, "msg" => "succ", "data" => $responseParams]);
    }
    //获取推广记录
    public function getPromotionRecord()
    {
        if (input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $promotionData = AiPromotionModel::promotionRecord($uid, $params["limit"], $params["page"]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $promotionData]);

    }
    //获取账户记录
    public function getUserBalanceRecord()
    {
        if (input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $balanceBillData = AiBalanceBill::where(["uid" => $uid])
            ->order("create_time desc")
            ->field("id,amount,after_amount,bill_type,amount_type,create_time")
            ->paginate([
                'list_rows' => $params["limit"],
                'page' => $params["page"],
            ]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $balanceBillData]);
    }
    //用户提现信息
    public function getWithdrawalInfo()
    {

        $uid = $this->uid;

        //获取usdt汇率
        $rate = SystemConfig::where('name', 'usdt_exchange_rate')->value('value'); // 获取某个配置值
        $userData = AiUser::where(['id' => $uid])->field("id,username,balance,coin_wallet_address")->find();
        $userData = $userData ? $userData->toArray() : [];
        $response = [
            "balance" => $userData["balance"],
            "wallet_address" => $userData["coin_wallet_address"],
            "rate" => $rate,
            "usdt" => number_format($userData["balance"] / $rate, 2)
        ];
        return json_encode(["code" => 1, "msg" => "succ", "data" => $response]);

    }
    //获取提现记录
    public function getWithdrawalRecord()
    {
        if (input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $withdrawalRecordData = AiWithdrawalRecord::where(["uid" => $uid])
            ->order("create_time desc")
            ->field("id,create_time,amount,status,coin_wallet_address")
            ->paginate([
                'list_rows' => $params["limit"],
                'page' => $params["page"],
            ]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $withdrawalRecordData]);
    }
    //提现
    public function withdrawal()
    {
        $uid = $this->uid;
        if (input("post.amount") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "amount" => input("post.amount"),
        ];
        try {
            Db::transaction(function () use ($uid, $params) {
                // 获取用户信息
                $userData = AiUser::where(["id" => $uid])
                    ->field("id,username,unique_code,channelCode,balance,coin_wallet_address")
                    ->lock(true) // 在事务内锁定该行，防止并发更新
                    ->find();
                if (!$userData) {
                    throw new \Exception("用户不存在");
                }
                if($userData["coin_wallet_address"]==""){
                    throw new \Exception("请先绑定收款卡");
                }
                // 检查余额是否足够
                if ($userData["balance"] < $params["amount"]) {
                    throw new \Exception("余额不足");
                }
                // 生成提现记录
                $withdrawalRecordRes = AiWithdrawalRecord::createRecord($userData, $params["amount"]);
                if (!$withdrawalRecordRes) {
                    throw new \Exception("生成转账记录失败");
                }
                // 用户余额扣减
                $userBalance = $userData["balance"] - $params["amount"];
                $updateBalance = AiUser::where(["id" => $uid])->update(["balance" => $userBalance]);
                if (!$updateBalance) {
                    throw new \Exception("扣减余额失败");
                }
                // 生成余额账单
                $billRes = AiBalanceBill::createBill($userData, $params["amount"], 1, 0);
                if (!$billRes) {
                    throw new \Exception("生成余额账单失败");
                }
            });
        } catch (\Exception $e) {
            return json_encode(["code" => 0, "msg" => $e->getMessage(), "data" => ""]);
        }
        return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
    }
}