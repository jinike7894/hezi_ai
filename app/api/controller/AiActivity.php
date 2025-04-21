<?php
namespace app\api\controller;

use app\api\controller\Aibase;
use app\common\model\Products;

use think\facade\Db;
use app\common\model\AiActivityRecord;
use app\common\model\AiUser;

class AiActivity extends Aibase
{
    //获取产品列表
    public function getActivityData(){
        if (input("get.type")==""||input("get.page")==""||input("get.limit")==""){
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "type" => input("get.type"),
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        //获取今日金币
        $points=AiActivityRecord::getActivityPoints($uid);
        //获取产品列表
        $activityProductData=Products::getAiActivityData($params["type"],$uid,$params["page"],$params["limit"]);
        return json_encode(["code" => 1, "msg" => "succ", "data" =>["points"=>$points,"list"=>$activityProductData]]);
    }
    //获取任务记录
    public function getActivityRecord(){
        if (input("get.page")==""||input("get.limit")==""){
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $activityRecordData=AiActivityRecord::where(["uid"=>$uid])->field("id,name,points,create_time")->paginate([
			'list_rows' => $params["limit"],
			'page' => $params["page"],
		]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $activityRecordData]);
    }
    //任务中心回调
    public function activityNotify(){
        if (input("post.pid")==""){
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        } 
        $params = [
            "pid" => input("post.pid"),
        ];
        $productData=Products::where(["id"=>$params["pid"]])->field("id,name,ai_activity_switch,ai_activity_free_points")->find();
        if($productData["ai_activity_switch"]!=1||!$productData){
            return json_encode(["code" => 0, "msg" => "产品错误", "data" => ""]);
        }
        $uid = $this->uid;
        //查询记录已经存在 则不提交
        $recordData = AiActivityRecord::where(["pid"=>$params["pid"],"uid"=>$uid])->find();
        if($recordData){
            return json_encode(["code" => 0, "msg" => "任务已完成", "data" => ""]);
        }
        try {
            Db::transaction(function () use ($uid, $productData, $params) {
                // 增加用户点数
                $userRes = AiUser::where(['id' => $uid])
                    ->inc("points", $productData["ai_activity_free_points"])
                    ->update();
                if (!$userRes) {
                    return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
                }
                // 生成使用表记录
                $activityRecord = [
                    "name" => $productData["name"],
                    "pid" => $params["pid"],
                    "uid" => $uid,
                    "points" => $productData["ai_activity_free_points"],
                    "create_time" => time(),
                    "update_time" => time(),
                ];
                $recordRes = AiActivityRecord::create($activityRecord);
                if (!$recordRes) {
                    return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
                }
            });
        } catch (\Exception $e) {
            return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
        }
        return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
       
    }
}