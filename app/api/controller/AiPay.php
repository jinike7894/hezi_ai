<?php
namespace app\api\controller;

use app\api\controller\AiBase;
use app\common\model\AiVipProduct;
use app\common\model\AiPointsProduct;
use app\common\model\AiPayment;
use app\common\model\AiUser;
use app\common\model\AiOrder;
use think\facade\Db;
use app\gladmin\model\SystemConfig;
class AiPay extends AiBase
{
    //获取支付通道用
    public $mchno = 2;
    public $currency = "cny";
    public $language = "zh-cn";
    public $payGateWay = "https://pay.dabaipay.com:82/api/trade/gateway";
    //获取vip产品
    public function getVipProduct()
    {
        $productData = AiVipProduct::where(["is_del" => 0])->field("id,name,day,free_day,price,show_tip,ai_video_face,ai_img_face,ai_auto_face,ai_manual_face")->order("sort desc")->select();
         return responseParams(["code" => 1, "msg" => "succ", "data" => $productData]);
    }
    //获取点数产品
    public function getPointsProduct()
    {
        $productData = AiPointsProduct::where(["is_del" => 0])->field("id,name,points,free_points,price,show_tips")->order("sort desc")->select();
        return responseParams(["code" => 1, "msg" => "succ", "data" => $productData]);
    }
    //选择支付通道
    public function getPayment()
    {
        $params = [
            "is_vip" => input("get.is_vip"),
            "pid" => input("get.pid"),
        ];
        $price = 3000;
        if ($params["is_vip"]) {
            $price = AiVipProduct::where(["id" => $params["pid"]])->value("price");
        } else {
            $price = AiPointsProduct::where(["id" => $params["pid"]])->value("price");
        }
        //查询价格
        $paymentData = AiPayment::where(["is_del" => 0])
            ->where("min", "<=", $price * 100)
            ->where("max", ">=", $price * 100)
            ->field("id,name,pay_icon,discount,show_tips,sort,pay_type")
            ->order("sort desc")
            ->select();
              return responseParams(["code" => 1, "msg" => "succ", "data" => $paymentData]);
    }
    //创建支付
    public function createOrder()
    {
        if (input("post.pid") == "" || input("post.pay_id") == "") {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "pid" => input("post.pid"),
            "is_vip" => input("post.is_vip"),
            "pay_id" => input("post.pay_id"),
        ];
        $uid = $this->uid;

        $paymentData = AiPayment::where(["is_del" => 0, "id" => $params["pay_id"]])->find();
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
            "is_first" => 0,
            "create_time" => time(),
            "update_time" => time(),
            "vip_level" => 0,
            "current_rate" => $paymentData["rate"],
            "is_activity" => 0,
        ];
        //生成订单号
        $orderParams["order_num"] = orderUniqueCode();
        //判断是否首单 12小时内并且首次支付
        $orderData = AiOrder::where(["uid" => $uid, "pay_status" => 1])->count();

        $userData = AiUser::where(["id" => $uid])->field("id,channelCode,create_time")->find();


        if (strtotime($userData["create_time"]) >= (time() - SystemConfig::getUserNewFlagTime()) && $orderData == 0) {

            $orderParams["is_activity"] = 1;
        }

        //设置产品渠道
        $orderParams["channelCode"] = $userData["channelCode"];
        $discount = 0;//支付通道优惠金额
        //判断支付通道优惠金额
        $paymentData = AiPayment::getPayMentFind($params["pay_id"]);
        if (!$paymentData) {
             return responseParams(["code" => 0, "msg" => "选择支付错误", "data" => ""]);
        }
        $discount = $paymentData["discount"];
        //判断是否vip订单
        if ($params["is_vip"] == 1) {
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
            if ($orderParams["is_activity"] == 0) {
                $total_day = $vipProduct["day"];
            } else {
                $total_day = $vipProduct["free_day"] + $vipProduct["day"];
            }
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
        $orderRes = AiOrder::create($orderParams);
        if (!$orderRes) {
             return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => []]);
        }
        //请求三方支付 或者支付链接
        $payReturnData = $this->doPay($orderParams, $params["pay_id"]);
        if ($payReturnData["code"]!=200) {
             return responseParams(["code" => 0, "msg" => $payReturnData["message"], "data" => []]);
        }
        return responseParams(["code" => 1, "msg" => "succ", "data" => ["pay_url" => $payReturnData["url"]]]);
    }
    //创建支付请求
    public function doPay($orderParams, $payId)
    {
        //查询当前域名配置
        $system = new SystemConfig();
        $imgHost = $system
            ->where('name', "pic_url")
            ->value("value");
        //查询支付网关和支付参数
        $paymentData = AiPayment::getPayMentFind($payId);
        $payParams = [
            "mchno" => $paymentData["appid"],
            "pid" => $paymentData["pid"],
            "money" => (int) $orderParams["price"] * 100,
            "orderno" => $orderParams["order_num"],
            "paytype" => $paymentData["pay_type"],
        ];
        $payParams["sign"] = generatePaySign($payParams, $paymentData["secret"]);
        $payParams["currency"] = "cny";
        // $payParams["attach"]=$orderParams["order_num"];
        $payParams["notifyurl"] = $imgHost . "/api/ai/payNotify";
        $payParams["returnurl"] = "";
        $payReturnData = postPayParams($paymentData["pay_gateway"], $payParams);
        $payReturnData = json_decode($payReturnData, true);
        // if ($payReturnData["code"] == 200) {
        //     return $payReturnData;
        // }
        return $payReturnData;
    }
    //支付回调
    public function payNotify()
    {
        $notifyParams = input("");
        $paymentId = AiOrder::where(["order_num" => $notifyParams["orderno"]])->value("pay_type_id");
        $token = AiPayment::where(["id" => $paymentId])->value("secret");
        $signParams = [
            "mchno" => $notifyParams["mchno"],
            "orderno" => $notifyParams["orderno"],
            "outtradeno" => $notifyParams["outtradeno"],
            "money" => $notifyParams["money"],
            "create_time" => $notifyParams["create_time"],
            "pay_time" => $notifyParams["pay_time"],
            "attach" => $notifyParams["attach"],
            "paytype" => $notifyParams["paytype"],
        ];
        $sign = generatePaySign($signParams, $token);
        if ($sign !== $notifyParams["sign"]) {
            echo "fail";
            return;
        }

        $params = [
            "ordernum" => $notifyParams["orderno"],
        ];
        //验签
        //订单操作
        $orderRes = AiOrder::notify($params["ordernum"]);
        if (!$orderRes) {
            echo "fail";
            die;
        }
        //接下来得操作
        echo "SUCCESS";
    }
    //获取是否是新用户
    public function getUserNewFlag()
    {
        $uid = $this->uid;
        $is_first = 0;
        $time = 0;
        $orderData = AiOrder::where(["uid" => $uid, "pay_status" => 1])->count();
        $userData = AiUser::where(["id" => $uid])->field("id,channelCode,create_time")->find();
        if (strtotime($userData["create_time"]) >=  (time() - SystemConfig::getUserNewFlagTime()) && $orderData == 0) {
            $is_first = 1;
            $time =strtotime($userData["create_time"])-(time() - SystemConfig::getUserNewFlagTime());
        }
        // if (strtotime($userData["create_time"]) >= (time() - 12 * 3600) && $orderData == 0) {
        //     $is_first = 1;
           
        //     $time =strtotime($userData["create_time"])-(time() - 12 * 3600);
        // }
        return responseParams(["code" => 1, "msg" => "succ", "data" => ["is_first" => $is_first,"time"=>$time]]);
    }
     //获取三方支付列表

    public function getPaymentlist()
    {
        $payGateway = "https://pay.dabaipay.com:82/api/trade/getpaytype";
        $payTypeParams = postPayParams($payGateway, [
            "mchno" => $this->mchno,
            "currency" => $this->currency,
            "language" => $this->language
        ]);
        $payTypeParams = json_decode($payTypeParams, true);
        if ($payTypeParams["code"] == 200) {
            $params = $payTypeParams["list"];

            $pidArray = array_column($params, 'pid');
            AiPayment::whereNotIn('pid', $pidArray)->update(['is_del' => 1]);
            $createPayArray = [];
            foreach ($params as $pk => $pv) {

                $currPay = AiPayment::where(["pid" => $pv["pid"]])->find();
                if ($currPay) {
                    AiPayment::where(["pid" => $pv["pid"]])->update([
                        "is_del" => 0,
                        "min" => $pv["data"]["min"],
                        "max" => $pv["data"]["max"],
                    ]);
                } else {
                    $arr = [
                        "name" => $pv["nickname"],
                        // "pay_icon"=>"",
                        "show_tips" => "123",
                        "discount" => "0",
                        "appid" => "2",
                        "secret" => "ZnTPBpsMB8ztPpTA",
                        "sort" => "10",
                        "rate" => "2",
                        "create_time" => time(),
                        "update_time" => time(),
                        "is_del" => 0,
                        "pay_gateway" => $this->payGateWay,
                        "pay_type" => $pv["paytype"],
                        "pid" => $pv["pid"],
                        "min" => $pv["data"]["min"],
                        "max" => $pv["data"]["max"],
                    ];
                    switch ($pv["paytype"]) {
                        case "alipay":
                            $arr["pay_icon"] = "/upload/otherimg/pay/zfb.png";
                            break;
                        case "wxpay":
                            $arr["pay_icon"] = "/upload/otherimg/pay/wx.png";
                            break;
                        case "unionpay":
                            $arr["pay_icon"] = "/upload/otherimg/pay/unionpay.png";
                            break;
                        case "usdt":
                            $arr["pay_icon"] = "/upload/otherimg/pay/usdt.png";
                            break;
                    }

                    $createPayArray[]=$arr;
                 
                }
            }
            if(count($createPayArray)>0){
                AiPayment::insertAll($createPayArray);
            }
        }
        echo "ok";

    }
    

}