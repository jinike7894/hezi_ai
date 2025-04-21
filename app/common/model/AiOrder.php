<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
use app\common\model\AiUser;
use app\common\model\AiPointsBill;
class AiOrder extends \think\Model
{
    // 设置字段信息
    // protected $schema = [
    //     'id' => 'int',
    //     'name' => 'string',
    //     'order_num' => 'string',
    //     'uid' => 'int',
    //     'pid' => 'int',
    //     'original_price' => 'int',
    //     'price' => 'string',
    //     'is_vip' => 'int',
    //     'data' => 'string',
    //     'pay_time' => 'int',
    //     'pay_type_id' => 'int',
    //     'pay_status' => 'int',
    //     'vip_expired_time' => 'int',
    //     'is_first' => 'int',
    //     'channelCode' => 'int',
    //     'create_time' => 'int',
    //     'update_time' => 'int',
    //     'delete_time' => 'int',
    // ];

    //获取充值记录
    public static function getOrderData($uid, $limit, $page)
    {

        $orderData = self::alias('order')
            ->leftJoin('ai_payment payment', 'order.pay_type_id = payment.id')
            ->where("order.uid", $uid)
            ->field('payment.name, order.price,order.pay_status,order.create_time')
            ->order("create_time desc")
            ->paginate([
                'list_rows' => $limit,  // 每页条数
                'page' => $page    // 当前页数
            ]);
        return $orderData;
    }
    public static function notify($ordernum)
    {
        $orderData = self::where(["order_num" => $ordernum])->field("id,name,order_num,pid,uid,is_vip,data,pay_status,is_first")->find();
        if ($orderData["pay_status"] == 1) {
            return true;
        }
        $userData = AiUser::where(["id" => $orderData["uid"]])->field("id,username,points,channelCode,create_time")->find();
        $productData = json_decode($orderData["data"], true);
        if ($orderData) {
            // 开启事务
            self::startTrans();
            try {
                if ($orderData["is_vip"]) {

                    //vip订单
                    //获取赠送天数+产品天数
                    //修改用户表过期时间
                    if ($orderData["is_first"] == 0) {
                        $totalDay = $productData["day"];
                    } else {
                        $totalDay = $productData["free_day"] + $productData["day"];
                    }
                    $userExpirationTime = $totalDay * 86400;
                    //更改用户vip时间
                    AiUser::where(["id" => $orderData["uid"]])->inc('vip_expiration', $userExpirationTime)->update();
                } else {
                    //点数订单
                    //获取赠送点数+产品点数
                    if ($orderData["is_first"] == 0) {
                        $totalPoints = $productData["points"];
                    } else {
                        $totalPoints = $productData["free_points"] + $productData["points"];
                    }
                    //修改用户表 points
                    AiUser::where(["id" => $orderData["uid"]])->inc('points', $totalPoints)->update();
                    //生成points账单变记录
                    $pointsBillParams = [
                        "uid" => $orderData["uid"],
                        "username" => $userData["username"],
                        "channelCode" => $userData["channelCode"],
                        "original_points" => $userData["points"],
                        "points" => $totalPoints,
                        "after_points" => $userData["points"] + $totalPoints,
                        "bill_type" => 2,
                        "points_type" => 1,
                        "create_time" => time(),
                        "update_time" => time(),
                    ];
                    AiPointsBill::create($pointsBillParams);
                }
                //修改pay_status状态 pay_time
                self::where(["order_num" => $ordernum])->update([
                    "pay_time" => time(),
                    "pay_status" => 1,
                ]);
                self::commit();
            } catch (\Exception $e) {
                // 发生异常，回滚事务
                self::rollback();
                return false;
            }
            return true;
        }

        return false;
    }
}

?>