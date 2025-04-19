<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
class AiOrder extends \think\Model
{
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'name'          => 'string',
        'order_num'          => 'string',
        'uid'          => 'int',
        'original_price'          => 'int',
        'price'          => 'string',
        'is_vip'          => 'int',
        'data'          => 'string',
        'pay_time'          => 'int',
        'pay_type_id'          => 'int',
        'pay_status'          => 'int',
        'vip_expired_time'          => 'int',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];

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