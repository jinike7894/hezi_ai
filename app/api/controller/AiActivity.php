<?php
namespace app\api\controller;

use app\api\controller\AiBase;
use app\common\model\Products;
use app\api\controller\AiApi;
use think\facade\Db;
use app\common\model\AiActivityRecord;
use app\common\model\AiUser;
use app\common\model\AiPointsBill;
use app\common\model\AiProductClickRecord;
use app\common\model\SystemConfig;
class AiActivity extends AiBase
{
    //获取产品列表
    public function getActivityData()
    {
        if (input("get.type") == "" || input("get.page") == "" || input("get.limit") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "type" => input("get.type"),
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        //获取今日金币
        $points = AiActivityRecord::getActivityPoints($uid);
        //可领取/待完成/已完成的任务数量
        $acticityNumParams = [
            "wait_collection_activity_num" => 0,//待领取
            "wait_complet_activity_num" => 0,//待完成
            "finish_activity_num" => 0,//已完成
        ];
        //获取产品列表
        $where=[];
        if($params["type"]==1){
            $where["product.ai_activity_pro_type"]=0;
        }
        $activityProductData = Products::getAiActivityData($params["type"], $uid, $params["page"], $params["limit"], $where);
        $acticityNumParams["wait_collection_activity_num"]  = $activityProductData["total"];//待领取
        $acticityNumParams["wait_complet_activity_num"]  = AiActivityRecord::where(["uid"=>$uid])->where('status', 'IN', [0, 1])->count();//待完成
        $acticityNumParams["finish_activity_num"]  = AiActivityRecord::where(["uid"=>$uid])->where('status', 2)->count();//已完成
        return responseParams(["code" => 1, "msg" => "succ", "data" => ["points" => $points, "list" => $activityProductData,'activity_params_count'=>$acticityNumParams]]);
    }
    //获取任务记录
    public function getActivityRecord()
    {
        if (input("get.page") == "" || input("get.limit") == "") {
              return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $activityRecordData = AiActivityRecord::where(["uid" => $uid])->order("create_time desc")->field("id,name,points,create_time")->paginate([
            'list_rows' => $params["limit"],
            'page' => $params["page"],
        ]);
          return responseParams(["code" => 1, "msg" => "succ", "data" => $activityRecordData]);
    }
    //任务中心回调
    public function activityNotify()
    {
        if (input("post.pid") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "pid" => input("post.pid"),
        ];
         $productData = Products::where(["id" => $params["pid"]])->field("id,name,ai_activity_switch,ai_activity_free_points,ai_activity_update_switch")->find();
        if ($productData["ai_activity_switch"] != 1 || !$productData) {
             return responseParams(["code" => 0, "msg" => "产品错误", "data" => ""]);
        }

        $uid = $this->uid;
        //判断金币领取已达上线
        $todayStart = strtotime(date('Y-m-d 00:00:00')); // 获取当天零点时间戳
        $todayEnd = strtotime(date('Y-m-d 23:59:59'));  // 获取当天最后一秒时间戳
        $activityPoints = AiActivityRecord::where([
                "uid" => $uid
            ])->where("create_time", ">", $todayStart)
                ->where("create_time", "<=", $todayEnd)
                ->sum("points");
        $pointsReceiveLimit=SystemConfig::getConfig("ai_points_receive_limit");       
        if( $activityPoints>=$pointsReceiveLimit){
            return responseParams(["code" => 301, "msg" => "今日获取金币已达上线", "data" => ""]);
        } 

        //判断ai_activity_update_switch是否有开关
        if ($productData["ai_activity_update_switch"] == 1) {
            //判断今天是否做过一次
            $recordData = AiActivityRecord::where([
                "pid" => $params["pid"],
                "uid" => $uid
            ])->where("create_time", ">", $todayStart)
                ->where("create_time", "<=", $todayEnd)
                ->find();
        } else {
            //查询记录已经存在 则不提交
            $recordData = AiActivityRecord::where(["pid" => $params["pid"], "uid" => $uid])->find();
        }
        if ($recordData) {
             return responseParams(["code" => 0, "msg" => "任务已存在", "data" => ""]);
        }
        $userData = AiUser::where(['id' => $uid])->field("id,username,points,channelCode")->find();
        try {
                // 生成做任务表记录
                $activityRecord = [
                    "name" => $productData["name"],
                    "pid" => $params["pid"],
                    "uid" => $uid,
                    "points" => $productData["ai_activity_free_points"],
                    "channelCode" => $userData["channelCode"],
                    "create_time" => time(),
                    "update_time" => time(),
                    "activity_order_num"=>orderUniqueCode(),
                    "status"=>0,
                    "activity_img"=>"",
                ];
                $recordRes = AiActivityRecord::create($activityRecord);
                if (!$recordRes) {
                      return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
                }
        } catch (\Exception $e) {
            return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
        }
         return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
    }
    public function clickRecord()
    {
        if (input("post.pid") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "pid" => input("post.pid"),
        ];
        $uid = $this->uid;
        $userData = AiUser::where(['id' => $uid])->field("id,username,points,channelCode")->find();
        $clickParams = [
            "pid" => $params["pid"],
            "uid" => $uid,
            "channelCode" => $userData["channelCode"],
            "create_time" => time(),
            "update_time" => time(),
        ];
        $clickRecordRes = AiProductClickRecord::create($clickParams);
        if (!$clickRecordRes) {
              return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
        }
         return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
    }
    //获取待完成/已完成的任务
    public function getActivityList()
    {
        if (input("get.type") == "" || input("get.page") == "" || input("get.limit") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "type" => input("get.type"),
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $where=[];
        switch($params["type"]){
            case 1:
                 $where[] = ['status', 'IN', [0, 1]];
                break;
            case 2:
                $where["status"]=2;
                break;
        }
        $uid = $this->uid;
        $activityRecordData = AiActivityRecord::where(["uid"=>$uid])->where($where)->field("id,name,points,create_time,activity_order_num,status")->select();
        return responseParams(["code" => 1, "msg" => "succ", "data" => $activityRecordData]);
    }
    //设置待完成审核图片
     public function setActivityImg()
    {
        if (input("post.img") == "" || input("post.activity_order_num") == "" ) {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "img" => input("post.img"),
            "activity_order_num" => input("post.activity_order_num"),
        ];
        $uid = $this->uid;
       
        $activityResult = AiActivityRecord::where(["uid"=>$uid,"activity_order_num"=>$params["activity_order_num"],"status"=>0])
                              ->update([
                                    "activity_img"=>$params["img"],
                                    "status"=>1,
                                    "update_time"=>time(),
                                    "apply_time"=>time(),
                          ]);
        if(!$activityResult){
            return responseParams(["code" => 0, "msg" => "设置失败", "data" => ""]);
        }
        return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
    }
}