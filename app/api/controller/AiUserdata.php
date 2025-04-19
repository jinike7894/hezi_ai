<?php
namespace app\api\controller;

use app\api\controller\Aibase;

use app\common\model\AiUser;
use app\common\model\AiOrder;
use app\gladmin\model\SystemConfig;
class Aiuserdata extends Aibase
{
    //设备码注册/登录
    public function registerUser()
    {
        if (!input("post.unique_code") || !input("post.model")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "model" => input("post.model"),
            "unique_code" => input("post.unique_code"),
        ];
        $token = "";
        //查询unique 
        $userData = AiUser::where(['unique_code' => $params["unique_code"]])->field("id,username,unique_code")->find()->toArray();

        //已注册
        if ($userData) {
            $token = generateToken($userData);
            return json_encode(["code" => 1, "msg" => "succ", "data" => ["token" => $token]]);
        }

        //新增用户
        $uniqueUserName = generateUniqueUserName();
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

        ];
        $addRes = AiUser::create($userAddData);
        if ($addRes) {
            $data = AiUser::where(['unique_code' => $userAddData['unique_code']])->field("id,username,unique_code")->find()->toArray();
            $token = generateToken($data);
            return json_encode(["code" => 1, "msg" => "succ", "data" => ["token" => $token]]);
        }
        return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);

    }
    //修改用户名密码
    public function updateUserData()
    {
        if (!input("post.username") || !input("post.passwd")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
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
                return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
            }
            return json_encode(["code" => 0, "msg" => "不能设置原密码", "data" => ""]);
        }
        //修改用户名和密码
        $updateRes = AiUser::where(['id' => $uid])->update(["username" => $params["username"], "passwd" => md5($params["passwd"]), "plain_passwd" => $params["passwd"], "is_update" => 1, "update_time" => time()]);
        if ($updateRes) {
            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        }
        return json_encode(["code" => 0, "msg" => "不能设置原密码", "data" => ""]);
    }
    //账号密码登录
    public function loginByPasswd()
    {
        if (!input("post.username") || !input("post.passwd")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "username" => input("post.username"),
            "passwd" => input("post.passwd"),
        ];

        //查询用户
        $userData = AiUser::where(["username" => $params["username"], "passwd" => md5($params["passwd"])])->field("id,username,unique_code")->find()->toArray();
        if (!$userData) {
            return json_encode(["code" => 0, "msg" => "账号或者密码错误", "data" => ""]);
        }
        $token = generateToken($userData);
        return json_encode(["code" => 1, "msg" => "succ", "data" => ["token" => $token]]);
    }
    //获取用户信息
    public function userInfo()
    {
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,unique_code,vip_expiration,points")->find()->toArray();
        $userData["is_vip"] = 0;
        $userData["vip_params"] = [];
        //判断vip类型
        if ($userData["vip_expiration"] > time()) {
            $userData["is_vip"] = 1;
            //查询拥有的vip
            $orderData = AiOrder::where(["uid" => $uid, "is_vip" => 1, "pay_status" => 1])->where('vip_expired_time', '>', time())->field("id,name,data")->find();

            if ($orderData["data"]) {
                $vipData = json_decode($orderData["data"], true);

                $userData["vip_params"] = [
                    "name" => $orderData["name"],
                    "ai_video_face" => $vipData["ai_video_face"],//视频换脸
                    "ai_img_face" => $vipData["ai_img_face"],//图片换脸
                    "ai_auto_face" => $vipData["ai_auto_face"],//自动换脸
                    "ai_manual_face" => $vipData["ai_manual_face"],//手动换脸
                ];
            }
        }
        return json_encode(["code" => 1, "msg" => "succ", "data" => $userData]);
    }
    //获取客服信息
    public function customerService(){
        $system = new SystemConfig();
        $email=$system
        ->where('name', "ai_onlinekf")
        ->value("value");
        return json_encode(["code" => 1, "msg" => "succ", "data" => ["customer_service"=>$email]]);
    }
    //获取充值记录
    public function rechargeRecord(){
        if (!input("get.page") || !input("get.limit")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $orderData = AiOrder::getOrderData($uid,$params["limit"],$params["page"]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $orderData]);
    }
    //获取ai使用记录
}