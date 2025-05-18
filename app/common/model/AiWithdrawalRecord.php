<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
class AiWithdrawalRecord extends \think\Model
{
    //创建提现记录
    public static function  createRecord($userData,$amount){
            $recordParams=[
                "name"=>$userData["username"],
                "uid"=>$userData["id"],
                "channelCode"=>$userData["channelCode"],
                "amount"=>$amount,
                "status"=>0,
                "coin_wallet_address"=>$userData["coin_wallet_address"],
                "withdrawal_order_num"=>orderUniqueCode(),
                "create_time"=>time(),
                "update_time"=>time(),
            ];
            $recordRes=self::create($recordParams);
            if($recordRes){
                return true;
            }
            return false;
    }
}