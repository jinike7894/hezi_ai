<?php
namespace app\api\controller;

use app\api\controller\Aibase;
use app\common\model\AiVipProduct;
use app\common\model\AiPointsProduct;
use app\common\model\AiPayment;
class AiPay extends Aibase
{
    //获取vip产品
    public function getVipProduct(){
        $productData=AiVipProduct::where(["is_del"=>0])->field("id,name,day,free_day,price,show_tip,ai_video_face,ai_img_face,ai_auto_face,ai_manual_face")->order("sort desc")->select();
        
        return json_encode(["code" => 1, "msg" => "succ", "data" => $productData]);
    }
    //获取点数产品
    public function getPointsProduct(){
        $productData=AiPointsProduct::where(["is_del"=>0])->field("id,name,points,free_points,price,show_tips")->order("sort desc")->select();
        return json_encode(["code" => 1, "msg" => "succ", "data" => $productData]);
    }
    //选择支付通道
    public function getPayment(){
        $paymentData=AiPayment::where(["is_del"=>0])->field("id,name,pay_icon,discount,show_tips,sort")->order("sort desc")->select();
        return json_encode(["code" => 1, "msg" => "succ", "data" => $paymentData]);
    }
    //创建支付
    public function createOrder(){
        if (!input("post.pid") || !input("post.is_vip")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "pid" => input("post.pid"),
            "is_vip" => input("post.is_vip"),
        ];
        $uid = $this->uid;
        //生成订单号
        //判断支付通道优惠金额
        //判断赠送天数
        //判断当前用户该笔订单的vip过期时间

        //生成产品镜像 创建订单数据


    }
}