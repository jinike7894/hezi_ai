<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class AiPromotion extends \think\Model
{
    //生成推广表记录
    public static function createRecord($pid,$uid){
        $promotionParams=[
            "uid"=>$uid,
            "pid"=>$pid,
            "create_time"=>time(),
            "update_time"=>time(),
        ];
        $createRes=self::create($promotionParams);
        if(!$createRes){
            return false;
        }
        return true;
    }
    //获取推广记录
    public static function promotionRecord($uid,$limit,$page){
          $promotionData=self::alias('promotion')
          ->leftJoin('ai_order order', 'promotion.uid = order.uid and order.pay_status=1') 
          ->leftJoin('ai_user user', 'promotion.uid = user.id') 
          ->where(["promotion.pid"=>$uid])
          ->field("promotion.uid,promotion.create_time,user.username, COALESCE(SUM(order.price), 0) AS total_price")
          ->group("promotion.uid, promotion.create_time, user.username") 
          ->order("promotion.create_time desc ")
          ->paginate([
            'list_rows' => $limit,
			'page' => $page,
          ] );
      
          return $promotionData;
    }
}