<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
class AiOrder extends \think\Model
{
    //获取充值记录
    public static function getOrderData($uid,$limit,$page)
    {
       
        $orderData = self::alias('order')
            ->leftJoin('ai_payment payment', 'order.pay_type_id = payment.id')
            ->where("order.uid",$uid)
            ->field('payment.name, order.price,order.pay_status,order.create_time')
            ->order("create_time desc")
            ->paginate([
                'list_rows' => $limit,  // 每页条数
                'page'      => $page    // 当前页数
            ]);
            return $orderData;
    }
}

?>