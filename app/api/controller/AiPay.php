<?php
namespace app\api\controller;

use app\api\controller\Aibase;
use app\common\model\AiVipProduct;
use app\common\model\AiPointsProduct;
use app\common\model\AiPayment;
use app\common\model\AiUser;
use app\common\model\AiOrder;
use think\facade\Db;
class AiPay extends Aibase
{

    //获取vip产品
    public function getVipProduct()
    {
        $productData = AiVipProduct::where(["is_del" => 0])->field("id,name,day,free_day,price,show_tip,ai_video_face,ai_img_face,ai_auto_face,ai_manual_face")->order("sort desc")->select();

        return json_encode(["code" => 1, "msg" => "succ", "data" => $productData]);
    }
    //获取点数产品
    public function getPointsProduct()
    {
        $productData = AiPointsProduct::where(["is_del" => 0])->field("id,name,points,free_points,price,show_tips")->order("sort desc")->select();
        return json_encode(["code" => 1, "msg" => "succ", "data" => $productData]);
    }
    //选择支付通道
    public function getPayment()
    {
        $paymentData = AiPayment::where(["is_del" => 0])->field("id,name,pay_icon,discount,show_tips,sort")->order("sort desc")->select();
        return json_encode(["code" => 1, "msg" => "succ", "data" => $paymentData]);
    }
    //创建支付
    public function createOrder()
    {
        if (input("post.pid")=="" || input("post.is_vip")=="" || input("post.pay_id")=="") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "pid" => input("post.pid"),
            "is_vip" => input("post.is_vip"),
            "pay_id" => input("post.pay_id"),
        ];
        $uid = $this->uid;
        //初始化订单数据
        $orderParams = [
            "name" => "",
            "order_num" => "",
            "pid" => (int) $params["pid"],
            "uid" => $uid,
            "original_price" => "",
            "price" => "",
            "is_vip" => $params["is_vip"],
            "data" => "",
            "pay_time" => 0,
            "pay_type_id" => $params["pay_id"],
            "pay_status" => 0,
            "vip_expired_time" => 0,
            "create_time" => time(),
            "update_time" => time(),

        ];
        //生成订单号
        $orderParams["order_num"] = orderUniqueCode();
        $discount = 0;//支付通道优惠金额
        //判断支付通道优惠金额
        $paymentData = AiPayment::getPayMentFind($params["pay_id"]);
        if (!$paymentData) {
            return json_encode(["code" => 0, "msg" => "选择支付错误", "data" => ""]);
        }
        $discount = $paymentData["discount"];
        //判断是否vip订单
        if ($params["is_vip"]==1) {
            $vipProduct = AiVipProduct::where(["id" => $params["pid"]])->find();
            //生成产品镜像 创建订单数据
            $orderParams["data"] = json_encode($vipProduct);
            //设置order name
            $orderParams["name"] = $vipProduct["name"];
            //设置原价
            $orderParams["price"] = $vipProduct["price"] - $discount;
            //设置订单价格
            $orderParams["original_price"] = $vipProduct["price"];
            //获取vip总天数  产品天数+赠送天数
            $total_day = $vipProduct["free_day"] + $vipProduct["day"];
            //计算 vip_expired_time
            $userVipExpiration = AiUser::where(["id" => $uid])->value("vip_expiration");
            $orderParams["vip_expired_time"] = $userVipExpiration + ($total_day * 86400);
        } else {
            $pointsProduct = AiPointsProduct::where(["id" => $params["pid"]])->find();
            //设置order name
            $orderParams["name"] = $pointsProduct["name"];
            //生成产品镜像 创建订单数据
            $orderParams["data"] = json_encode($pointsProduct);
            //设置原价
            $orderParams["price"] = $pointsProduct["price"] - $discount;
            //设置订单价格
            $orderParams["original_price"] = $pointsProduct["price"];

        }
        //生成订单数据
        $orderRes=AiOrder::create($orderParams);
        if(!$orderRes){
            return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => []]);
        }
        //请求三方支付 或者支付链接
        return json_encode(["code" => 1, "msg" => "succ", "data" => ["pay_url"=>"https://google.com/"]]);
    }
    //支付回调
    public function payNotify()
    {
        $params = [
            "ordernum" => input("ordernum"),
        ];
        //验签
        //订单操作
        $orderRes = AiOrder::notify($params["ordernum"]);
       
        if (!$orderRes) {
            echo "error";
            die;
        }
        //接下来得操作
        echo "success";
    }
}