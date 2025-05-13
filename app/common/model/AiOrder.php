<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
use app\common\model\AiUser;
use app\common\model\AiPointsBill;
use function GuzzleHttp\default_ca_bundle;
use app\common\model\AiUseRecord;
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
            ->field('payment.name, order.price,order.pay_status,order.create_time,payment.pay_type')
            ->order("create_time desc")
            ->paginate([
                'list_rows' => $limit,  // 每页条数
                'page' => $page    // 当前页数
            ]);
        return $orderData;
    }
    //订单回调
    public static function notify($ordernum)
    {
        $orderData = self::where(["order_num" => $ordernum])->field("id,name,order_num,pid,uid,is_vip,data,pay_status,is_first,price")->find();
        if ($orderData["pay_status"] == 1) {
            return true;
        }
        $orderCount = AiOrder::where(["uid" => $orderData["uid"], "pay_status" => 1])->count();
        $userData = AiUser::where(["id" => $orderData["uid"]])->field("id,username,points,pid,channelCode,commission,balance,create_time")->find();
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
                        //判断是否满足新订单
                        if (strtotime($userData["create_time"]) >=  (time() - SystemConfig::getUserNewFlagTime())&&$orderCount==0) {
                            $totalDay = $productData["free_day"] + $productData["day"];
                        }else{
                            $totalDay = $productData["day"];
                        }

                    }

                    //修改用户vip等级
                    if (isset($productData["vip_level"])) {
                        AiUser::where(["id" => $orderData["uid"]])->update([
                            "vip_level"=>"v".$productData["vip_level"],
                        ]);
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
                         //判断是否满足新订单
                         if (strtotime($userData["create_time"]) >=  (time() - SystemConfig::getUserNewFlagTime())&&$orderCount==0) {
                            $totalPoints = $productData["free_points"] + $productData["points"];
                        }else{
                            $totalPoints = $productData["points"];
                        }
                      
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
                        "operator" => $userData["username"],
                    ];
                    AiPointsBill::create($pointsBillParams);
                }
                //修改pay_status状态 pay_time
                self::where(["order_num" => $ordernum])->update([
                    "pay_time" => time(),
                    "pay_status" => 1,
                ]);
                if ($userData["pid"] != 0) {
                    //佣金账单设置
                    $balanceBillAmount = $orderData["price"] * ($userData["commission"] / 100);
                    //查询代理信息
                    $userPidData=AiUser::where(["id" => $userData["pid"]])->field("id,username,points,pid,channelCode,commission,balance,create_time")->find();
                    AiBalanceBill::createBill($userPidData, $balanceBillAmount, 0, 1);
                    AiUser::where(["id" => $userData["pid"]])->inc('balance', $balanceBillAmount)->update();
                }
                self::commit();
            } catch (\Exception $e) {
                // echo $e->getMessage();
                // 发生异常，回滚事务
                self::rollback();
                return false;
            }
            return true;
        }

        return false;
    }
    //获取当前vip 某产品可用次数
    public static function availableTimes($uid, $aiType)
    {
        $orderData = AiOrder::where(["uid" => $uid, "is_vip" => 1, "pay_status" => 1])->where('vip_expired_time', '>', time())->order("create_time asc")->limit(1)->field("id,name,data")->find();
        if(!$orderData){
                return 0;
        }
        
        //获取当前vip 每天几次 
        $aiProductParams = json_decode($orderData["data"], true);
        $aiTimes = 0;
        switch ($aiType) {
            case 0:
                //视频换脸
                $aiTimes = $aiProductParams["ai_video_face"];
                break;
            case 1:
                //图片换脸
                $aiTimes = $aiProductParams["ai_img_face"];
                break;
            case 2:
                //自动换脸
                $aiTimes = $aiProductParams["ai_auto_face"];
                break;
            case 3:
                //手动换脸
                $aiTimes = $aiProductParams["ai_manual_face"];
                break;
            default:
                $aiTimes = $aiProductParams["ai_manual_face"];
                break;

        }
       
        //查询今日使用次数
        $usedAiRecord = AiUseRecord::where(["is_use_vip" => 1, "ai_type" => $aiType])
            ->whereIn("status", [0, 1])
            ->whereTime('create_time', 'today')
            ->count();
           
        if ($aiTimes < $usedAiRecord) {
          
            return 0;
        }
   
        return $aiTimes - $usedAiRecord;
    }
}

?>