<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class AiBalanceBill extends \think\Model
{
  //增加余额账单
  public static function createBill($userData, $amount, $bill_type, $amount_type)
  {
    $billParams = [
      "name" => $userData["username"],
      "uid" => $userData["id"],
      "original_amount" => $userData["balance"],
      "amount" => $amount,
      "bill_type" => $bill_type,
      "amount_type" => $amount_type,
      "operator" => $userData["username"],
      "create_time" => time(),
      "update_time" => time(),
    ];
    if (!$amount_type) {
      $billParams["after_amount"] = $userData["balance"] - $amount;
      if ($userData["balance"] < $amount) {
        $billParams["after_amount"] = 0;
      }
    } else {
      $billParams["after_amount"] = $userData["balance"] + $amount;
    }
    $billRes = self::create($billParams);
    if ($billRes) {
      return true;
    }
    return false;
  }
}