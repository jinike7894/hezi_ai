<?php
namespace app\api\controller;

use app\api\controller\Aibase;

use app\common\model\AiUser;
use app\common\model\AiOrder;
use app\gladmin\model\SystemConfig;
use app\common\model\AiUseRecord;
use app\common\model\AiPromotion;
use think\facade\Db;
class Aiuserdata extends Aibase
{
    //设备码注册/登录
    public function registerUser()
    {
        if (input("post.unique_code") == "" || input("post.model") == "" || input("post.channelCode") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
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
            "channelCode" => $params["channelCode"],
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
                    return json_encode(["code" => 0, "msg" => $e->getMessage(), "data" => []]);
                }
            } else {
                AiUser::create($userAddData);
            }
        } else {
            AiUser::create($userAddData);
        }




        $data = AiUser::where(['unique_code' => $userAddData['unique_code']])->field("id,username,unique_code")->find()->toArray();
        if (empty($data)) {
            return json_encode(["code" => 0, "msg" => "请刷新页面重试", "data" => []]);
        }
        $token = generateToken($data);
        return json_encode(["code" => 1, "msg" => "succ", "data" => ["token" => $token]]);

        // return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);

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
    public function customerService()
    {
        $system = new SystemConfig();
        $email = $system
            ->where('name', "ai_onlinekf")
            ->value("value");
        return json_encode(["code" => 1, "msg" => "succ", "data" => ["customer_service" => $email]]);
    }
    //获取充值记录
    public function rechargeRecord()
    {
        if (!input("get.page") || !input("get.limit")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $orderData = AiOrder::getOrderData($uid, $params["limit"], $params["page"]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $orderData]);
    }
    //获取ai使用记录
    public function aiUseRecord()
    {
        if (!input("get.page") || !input("get.limit")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $uid = $this->uid;
        $recordData = AiUseRecord::where(["uid" => $uid, "is_del" => 0])->order("create_time desc ")->field("id,ai_type,img,ai_generate_source,status,create_time")->paginate([
            'list_rows' => $params["limit"],  // 每页条数
            'page' => $params["page"],    // 当前页数
        ]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $recordData]);
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
            return json_encode(["code" => 1, "msg" => "succ", "data" => []]);
        }
        return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => []]);
    }
    //设置钱包地址
    public function setWallet()
    {
        if (!input("post.wallet")) {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "wallet" => input("post.wallet"),
        ];
        $uid = $this->uid;
        $userRes = AiUser::where(["id" => $uid])->update([
            "coin_wallet_address" => $params["wallet"],
        ]);
        if ($userRes) {
            return json_encode(["code" => 1, "msg" => "succ", "data" => []]);
        }
        return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => []]);
    }
    //测试
    public function templant()
    {
        $jsonString = file_get_contents("./img.json"); // 读取 JSON 文件内容
        $arrayData = json_decode($jsonString, true); // 转换为数组


        $limit = 100; // 每页处理 10 条
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // 获取当前页（可从 URL 传递参数）
        $offset = ($page - 1) * $limit; // 计算偏移量

        // 进行分页
        $pagedData = array_slice($arrayData, $offset, $limit);

        foreach ($pagedData as $k => $v) {
            $nowImg = "./ai/img/" . uniqid() . ".png";
            $cleanPath = ltrim($nowImg, '.');
            $this->imgDecode($v["img"], $nowImg);
            $nowImg = "/ai/img/" . uniqid() . ".png";
            $tm_array=[
                "name"=>$v["title"],
                "img"=>$cleanPath,
                "create_time"=>time(),
                "update_time"=>time(),
            ];
            Db::name("ai_img_template")->create($tm_array);
        }
        // 输出分页信息
        $totalPages = ceil(count($arrayData) / $limit);
    }
    public function imgDecode($path, $sqlpath)
    {
        $filePath = $path; // 加密文件路径
        $outputPath = $sqlpath; // 输出图片路径

        // 读取加密文件内容
        $data = file_get_contents($filePath);

        if ($data === false) {
            return false;
        }

        // XOR 解密
        $decryptedData = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $decryptedData .= chr(ord($data[$i]) ^ 0x13);
        }

        // 保存解密后的数据为图片
        file_put_contents($outputPath, $decryptedData);
    }
}