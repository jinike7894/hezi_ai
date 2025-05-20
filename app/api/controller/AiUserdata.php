<?php
namespace app\api\controller;

use app\api\controller\AiBase;

use app\api\controller\AiApi;
use app\common\model\AiUser;
use app\common\model\AiOrder;
use app\gladmin\model\SystemConfig;
use app\common\model\AiUseRecord;
use app\common\model\AiPromotion;
use app\common\model\AiBalanceBill;
use app\common\model\AiActivityRecord;
use think\facade\Db;
class AiUserdata extends AiBase
{
    //设备码注册/登录
    public function registerUser()
    {
        if (input("post.unique_code") == "" || input("post.model") == "" || input("post.channelCode") == "") {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "model" => input("post.model"),
            "unique_code" => input("post.unique_code"),
            "channelCode" => input("post.channelCode"),
            "pid" => input("post.pid"),
        ];
        $token = "";
        //查询unique 
        $userData = AiUser::where(['unique_code' => $params["unique_code"]])->field("id,username,unique_code")->find();
        $userData = $userData ? $userData->toArray() : [];
        //已注册
        if ($userData) {
            $token = generateToken($userData);
            return responseParams(["code" => 1, "msg" => "succ", "data" => ["token" => $token]]);
        }

        //新增用户
        $uniqueUserName = generateUniqueUserName();
        $ip_array = explode(",", GetIP());
        $client_ip = trim($ip_array[0]);
        $userAddData = [
            "username" => $uniqueUserName,
            "passwd" => md5($uniqueUserName),
            "plain_passwd" => $uniqueUserName,
            "unique_code" => $params["unique_code"],
            "vip_expiration" => time(),
            "points" => 0,
            "create_time" => time(),
            "update_time" => time(),
            "is_update" => 0,
            "model" => $params["model"],
            "channelCode" => $params["channelCode"],
            "ip"=>$client_ip,
        ];
        //判断携带推广参数
        //判断pid 是否存在
        if ($params["pid"] != "") {
            $userAddData["pid"] = $params["pid"];
            if (AiUser::where(['id' => $params["pid"]])->count() > 0) {
                try {
                    Db::transaction(function () use ($params, $userAddData) {
                        // 创建用户
                        $addRes = AiUser::create($userAddData);
                        if (!$addRes) {
                            throw new \Exception("创建用户失败");
                        }
                        // 生成推广表数据
                        $promotionRes = AiPromotion::createRecord($params["pid"], $addRes->id);
                        if (!$promotionRes) {
                            throw new \Exception("推广记录创建失败");
                        }
                    });
                } catch (\Exception $e) {
                    return responseParams(["code" => 0, "msg" => $e->getMessage(), "data" => []]);
                }
            } else {
                AiUser::create($userAddData);
            }
        } else {
            AiUser::create($userAddData);
        }




        $data = AiUser::where(['unique_code' => $userAddData['unique_code']])->field("id,username,unique_code")->find()->toArray();
        if (empty($data)) {
            return responseParams(["code" => 0, "msg" => "请刷新页面重试", "data" => []]);
        }
        $token = generateToken($data);
        return responseParams(["code" => 1, "msg" => "succ", "data" => ["token" => $token]]);

        // return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);

    }
    //修改用户名密码
    public function updateUserData()
    {
        if (!input("post.username") || !input("post.passwd")) {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "username" => input("post.username"),
            "passwd" => input("post.passwd"),
        ];
        $uid = $this->uid;

        //查询用户是否已经更改过用户名密码
        $updateLock = AiUser::where(['id' => $uid])->field("username,unique_code")->value("is_update");
        if ($updateLock) {
            //只修改密码
            $updateRes = AiUser::where(['id' => $uid])->update(["passwd" => md5($params["passwd"]), "plain_passwd" => $params["passwd"]]);
            if ($updateRes) {
                return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
            }
            return responseParams(["code" => 0, "msg" => "不能设置原密码", "data" => ""]);
        }
        //修改用户名和密码
        $updateRes = AiUser::where(['id' => $uid])->update(["username" => $params["username"], "passwd" => md5($params["passwd"]), "plain_passwd" => $params["passwd"], "is_update" => 1, "update_time" => time()]);
        if ($updateRes) {
            return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
        }
        return responseParams(["code" => 0, "msg" => "不能设置原密码", "data" => ""]);
    }
    //账号密码登录
    public function loginByPasswd()
    {
        if (!input("post.username") || !input("post.passwd")) {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "username" => input("post.username"),
            "passwd" => input("post.passwd"),
        ];

        //查询用户
        $userData = AiUser::where(["username" => $params["username"], "passwd" => md5($params["passwd"])])->field("id,username,unique_code")->find();
        if (!$userData) {
            return responseParams(["code" => 0, "msg" => "账号或者密码错误", "data" => ""]);
        }
        
        $token = generateToken($userData->toArray());
        return responseParams(["code" => 1, "msg" => "succ", "data" => ["token" => $token]]);
    }
    //获取用户信息
    public function userInfo()
    {
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,unique_code,vip_expiration,points,is_update,plain_passwd")->find();
        if(!$userData){
            return responseParams(["code" => 0, "msg" => "用户信息错误", "data" => []]);
        }
        $userData= $userData->toArray();
         //今日收益
        $userData["today_income"] = AiBalanceBill::where(["amount_type" => 1,"uid"=>$uid])->whereTime("create_time", "today")->sum("amount");
        //今日金币
        $userData["today_points"]  = AiActivityRecord::getActivityPoints($uid);
         $userData["is_vip"] = 0;
        $userData["vip_params"] = [
            "name" => "未开通vip",
            "ai_video_face" => 0,//视频换脸
            "ai_img_face" => 0,//图片换脸
            "ai_auto_face" => 0,//自动换脸
            "ai_manual_face" => 0,//手动换脸
            "vip_level"=>0,
        ];
        //获取当前网址
        $system = new SystemConfig();
        $land_host = $system
            ->where('name', "ai_land_host")
            ->value("value");
        //落地页域名
        $userData["land_host"] = $land_host;
        //
        //判断vip类型
        if ($userData["vip_expiration"] > time()) {
            $userData["is_vip"] = 1;
            //查询拥有的vip
            $orderData = AiOrder::where(["uid" => $uid, "is_vip" => 1, "pay_status" => 1])->where('vip_expired_time', '>', time())->field("id,name,data")->find();

            if ($orderData["data"]) {
                $vipData = json_decode($orderData["data"], true);
                $userData["vip_params"] = [
                    "name" => $orderData["name"],
                    "ai_video_face" => AiOrder::availableTimes($uid, 0),//视频换脸
                    "ai_img_face" =>AiOrder::availableTimes($uid, 1),//图片换脸
                    "ai_auto_face" =>AiOrder::availableTimes($uid, 2),//自动换脸
                    "ai_manual_face" => AiOrder::availableTimes($uid, 3),//手动换脸
                    "vip_level"=>isset($vipData["vip_level"])?$vipData["vip_level"]:0,
                ];
            }
        }
        return responseParams(["code" => 1, "msg" => "succ", "data" => $userData]);
    }
    //获取客服信息
    public function customerService()
    {
        $system = new SystemConfig();
        $systemData = $system

            // ->whereIn('name', ["ai_onlinekf_whatsapp", "ai_onlinekf_telegram"])
            ->whereIn('name', ["ai_onlinekf_email"])
            ->column("value", "name");
      
        // return json_encode(["code" => 1, "msg" => "succ", "data" => ["email" => $systemData["ai_onlinekf_email"], "telegram" => $systemData["ai_onlinekf_telegram"]]]);
        return responseParams(["code" => 1, "msg" => "succ", "data" => ["email" => $systemData["ai_onlinekf_email"]]]);

    }
    //获取充值记录
    public function rechargeRecord()
    {
        if (!input("get.page") || !input("get.limit")) {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $orderData = AiOrder::getOrderData($uid, $params["limit"], $params["page"]);
        return responseParams(["code" => 1, "msg" => "succ", "data" => $orderData]);
    }
    //获取ai使用记录
    public function aiUseRecord()
    {
        if (!input("get.page") || !input("get.limit")) {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $recordData = AiUseRecord::where(["uid" => $uid, "is_del" => 0])
            ->order("create_time desc ")
            ->field("id,ai_type,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(img, '.jpg', '.js'), '.jpeg', '.js'), '.png', '.js'), '.webp', '.js'), '.gif', '.js') as img,ai_generate_source,status,create_time")->paginate([
                    'list_rows' => $params["limit"],  // 每页条数
                    'page' => $params["page"],    // 当前页数
                ]);
        return responseParams(["code" => 1, "msg" => "succ", "data" => $recordData]);
    }
    //删除记录
    public function delUseRecord()
    {
        if (!input("post.id")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "id" => input("post.id"),
        ];
        $uid = $this->uid;
        $delRes = AiUseRecord::where(["uid" => $uid, "id" => $params["id"]])->update([
            "is_del" => 1
        ]);
        if ($delRes) {
            //发送删除请求到三方
            $aiApi = new AiApi();
            $useRecordData = AiUseRecord::where(["id" => $params["id"]])->field("id,task_id")->find();
            $aiApi->delTask($useRecordData["task_id"]);
            return json_encode(["code" => 1, "msg" => "succ", "data" => []]);
        }
        return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => []]);
    }
    //设置钱包地址
    public function setWallet()
    {
        if (!input("post.wallet")) {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "wallet" => input("post.wallet"),
        ];
        $uid = $this->uid;
        $userRes = AiUser::where(["id" => $uid])->update([
            "coin_wallet_address" => $params["wallet"],
        ]);
        if ($userRes) {
            return responseParams(["code" => 1, "msg" => "succ", "data" => []]);
        }
        return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => []]);
    }


}