<?php
namespace app\api\controller;

use app\api\controller\Aibase;
use app\api\controller\AiApi;
use app\common\model\Products;

use think\facade\Db;
use app\common\model\AiActivityRecord;
use app\common\model\AiUser;
use app\common\model\AiOrder;
use app\common\model\AiPointsBill;
use app\common\model\AiUseRecord;
use app\common\model\AiVideoTemplate;
use app\common\model\AiImgTemplate;
class Ai extends Aibase
{

    protected $aiVideoPoints = 28;//视频脱衣
    protected $aiImgPoints = 8; //图片脱衣
    protected $aiAutoPoints = 8; //自动脱衣
    protected $aiManualPoints = 8; //自动脱衣

    //视频换脸
    public function videoAi()
    {
        if (input("post.template_id") == "" || input("post.img") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "template_id" => input("post.template_id"),
            "img" => input("post.img"),
        ];
        //查询用户当前vip
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,points,vip_expiration,channelCode")->find();

        $useRecordParams = AiUseRecord::$useRecordParams;

        if ($userData["vip_expiration"] < time()) {
            if ($userData["points"] < $this->aiVideoPoints) {
                return json_encode(["code" => 0, "msg" => "金币不足请充值", "data" => ""]);
            }
            try {
                $instance = $this;
                Db::transaction(function () use ($uid, $userData, $params, $instance, $useRecordParams) {
                    // 扣减用户 points
                    $userRes = AiUser::pointsDec($userData, $instance->aiVideoPoints);
                    if (!$userRes) {
                        throw new \Exception("扣减用户点数失败");
                    }
                    // 生成使用记录
                    $useRecordParams["uid"] = $uid;
                    $useRecordParams["ai_type"] = 0;
                    $useRecordParams["template_id"] = $params["template_id"];
                    $useRecordParams["img"] = $params["img"];
                    $useRecordParams["img_layers"] = "";
                    $useRecordParams["ai_generate_source"] = "";
                    $useRecordParams["is_use_vip"] = 0;
                    $useRecordParams["points"] = $instance->aiVideoPoints;
                    $useRecordParams["status"] = 0;
                    $useRecordParams["channelCode"] = $userData["channelCode"];
                    $useRecordParams["create_time"] = time();
                    $useRecordParams["update_time"] = time();
                    $useRecordRes = AiUseRecord::create($useRecordParams);
                    if (!$useRecordRes) {
                        throw new \Exception("生成使用记录失败");
                    }
                    //发送请求到三方ai
                    $aiApi = new AiApi();
                    $aiRes = $aiApi->dataToAi(0, $params["img"], $params["template_id"], $useRecordRes->id);
                    if (!$aiRes) {
                        throw new \Exception("ai请求失败");
                    }
                });

                return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
            } catch (\Exception $e) {
                return json_encode(["code" => 0, "msg" => $e->getMessage(), "data" => ""]);
            }
        }
        //如果是vip判断是否剩余次数  如果次数不够扣减次数
        $orderData = AiOrder::where(["uid" => $uid, "is_vip" => 1, "pay_status" => 1])->where('vip_expired_time', '>', time())->order("create_time asc")->limit(1)->field("id,name,data")->find();
        //获取当前vip剩余次数
        if (AiOrder::availableTimes($uid, 0) > 0) {
            //生成vip使用记录 
            $useRecordParams["uid"] = $uid;
            $useRecordParams["ai_type"] = 0;
            $useRecordParams["template_id"] = $params["template_id"];
            $useRecordParams["img"] = $params["img"];
            $useRecordParams["img_layers"] = "";
            $useRecordParams["ai_generate_source"] = "";

            $useRecordParams["is_use_vip"] = 1;
            $useRecordParams["points"] = 0;
            $useRecordParams["status"] = 0;
            $useRecordParams["channelCode"] = $userData["channelCode"];
            $useRecordParams["create_time"] = time();
            $useRecordParams["update_time"] = time();
            $useRecordRes = AiUseRecord::create($useRecordParams);
            //发送请求到三方ai
            //发送请求到三方ai
            //发送请求到三方ai
            $aiApi = new AiApi();
            $aiApi->dataToAi(0, $params["img"], $params["template_id"], $useRecordRes->id);
            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        }
        //vip已使用完扣减余额
        if ($userData["points"] < $this->aiVideoPoints) {
            return json_encode(["code" => 0, "msg" => "金币不足请充值", "data" => ""]);
        }
        try {
            $instance = $this;
            Db::transaction(function () use ($uid, $userData, $params, $instance, $useRecordParams) {
                // 扣减用户 points
                $userRes = AiUser::pointsDec($userData, $instance->aiVideoPoints);
                if (!$userRes) {
                    throw new \Exception("扣减用户点数失败");
                }
                // 生成使用记录
                $useRecordParams["uid"] = $uid;
                $useRecordParams["ai_type"] = 0;
                $useRecordParams["template_id"] = $params["template_id"];
                $useRecordParams["img"] = $params["img"];
                $useRecordParams["img_layers"] = "";
                $useRecordParams["ai_generate_source"] = "";
                $useRecordParams["is_use_vip"] = 0;
                $useRecordParams["points"] = $instance->aiVideoPoints;
                $useRecordParams["status"] = 0;
                $useRecordParams["channelCode"] = $userData["channelCode"];
                $useRecordParams["create_time"] = time();
                $useRecordParams["update_time"] = time();
                //任务id
                $useRecordParams["task_id"] = time() . rand(00000, 99999);
                $useRecordRes = AiUseRecord::create($useRecordParams);
                if (!$useRecordRes) {
                    throw new \Exception("生成使用记录失败");
                }
                //发送请求到三方ai
                $aiApi = new AiApi();
                $aiRes = $aiApi->dataToAi(0, $params["img"], $params["template_id"], $useRecordRes->id);
                if (!$aiRes) {
                    throw new \Exception("ai请求失败");
                }
            });
            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        } catch (\Exception $e) {
            return json_encode(["code" => 0, "msg" => $e->getMessage(), "data" => ""]);
        }
    }
    //获取视频换脸模板列表
    public function videoTemplateData()
    {
        if (input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $templateData = AiVideoTemplate::paginate([
            'list_rows' => $params["limit"],
            'page' => $params["page"],
        ]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $templateData]);
    }
    //获取单个视频模板
    public function videoTemplateFindData()
    {
        if (input("get.id") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "id" => input("get.id"),
        ];
        $templateFindData = AiVideoTemplate::where(["id" => $params["id"]])->find();
        return json_encode(["code" => 1, "msg" => "succ", "data" => $templateFindData]);
    }
    //获取图片模板列表
    public function imgTemplateData()
    {
        if (input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $templateData = AiImgTemplate::paginate([
            'list_rows' => $params["limit"],
            'page' => $params["page"],
        ]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $templateData]);
    }
    //获取单个图片模板列表
    public function imgTemplateFindData()
    {
        if (input("get.id") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "id" => input("get.id"),
        ];
        $templateFindData = AiImgTemplate::where(["id" => $params["id"]])->find();
        return json_encode(["code" => 1, "msg" => "succ", "data" => $templateFindData]);
    }
    //图片换脸
    public function imgAi()
    {
        if (input("post.template_id") == "" || input("post.img") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "template_id" => input("post.template_id"),
            "img" => input("post.img"),
        ];
        //查询用户当前vip
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,points,vip_expiration,channelCode")->find();
        $useRecordParams = AiUseRecord::$useRecordParams;
        if ($userData["vip_expiration"] < time()) {
            if ($userData["points"] < $this->aiImgPoints) {
                return json_encode(["code" => 0, "msg" => "金币不足请充值", "data" => ""]);
            }
            try {
                $instance = $this;
                Db::transaction(function () use ($uid, $userData, $params, $instance, $useRecordParams) {
                    // 扣减用户 points
                    $userRes = AiUser::pointsDec($userData, $instance->aiImgPoints);
                    if (!$userRes) {
                        throw new \Exception("扣减用户点数失败");
                    }
                    // 生成使用记录
                    $useRecordParams["uid"] = $uid;
                    $useRecordParams["ai_type"] = 1;
                    $useRecordParams["template_id"] = $params["template_id"];
                    $useRecordParams["img"] = $params["img"];
                    $useRecordParams["img_layers"] = "";
                    $useRecordParams["ai_generate_source"] = "";
                    $useRecordParams["is_use_vip"] = 0;
                    $useRecordParams["points"] = $instance->aiImgPoints;
                    $useRecordParams["status"] = 0;
                    $useRecordParams["channelCode"] = $userData["channelCode"];
                    $useRecordParams["create_time"] = time();
                    $useRecordParams["update_time"] = time();
                    $useRecordRes = AiUseRecord::create($useRecordParams);
                    if (!$useRecordRes) {
                        throw new \Exception("生成使用记录失败");
                    }
                    //发送请求到三方ai
                    $aiApi = new AiApi();
                    $aiRes = $aiApi->dataToAi(1, $params["img"], $params["template_id"], $useRecordRes->id);
                    if (!$aiRes) {
                        throw new \Exception("ai请求失败");
                    }
                });

                return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
            } catch (\Exception $e) {
                return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
            }
        }
        //如果是vip判断是否剩余次数  如果次数不够扣减次数
        $orderData = AiOrder::where(["uid" => $uid, "is_vip" => 1, "pay_status" => 1])->where('vip_expired_time', '>', time())->order("create_time asc")->limit(1)->field("id,name,data")->find();
        //获取当前vip剩余次数
        if (AiOrder::availableTimes($uid, 1) > 0) {
            //生成vip使用记录 
            $useRecordParams["uid"] = $uid;
            $useRecordParams["ai_type"] = 1;
            $useRecordParams["template_id"] = $params["template_id"];
            $useRecordParams["img"] = $params["img"];
            $useRecordParams["img_layers"] = "";
            $useRecordParams["ai_generate_source"] = "";
            $useRecordParams["is_use_vip"] = 1;
            $useRecordParams["points"] = 0;
            $useRecordParams["status"] = 0;
            $useRecordParams["channelCode"] = $userData["channelCode"];
            $useRecordParams["create_time"] = time();
            $useRecordParams["update_time"] = time();
            $useRecordRes = AiUseRecord::create($useRecordParams);
            //发送请求到三方ai
            $aiApi = new AiApi();
            $aiApi->dataToAi(1, $params["img"], $params["template_id"], $useRecordRes->id);

            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        }
        //vip已使用完扣减余额
        if ($userData["points"] < $this->aiImgPoints) {
            return json_encode(["code" => 0, "msg" => "金币不足请充值", "data" => ""]);
        }
        try {
            $instance = $this;
            Db::transaction(function () use ($uid, $userData, $params, $instance, $useRecordParams) {
                // 扣减用户 points
                $userRes = AiUser::pointsDec($userData, $instance->aiImgPoints);
                if (!$userRes) {
                    throw new \Exception("扣减用户点数失败");
                }
                // 生成使用记录
                $useRecordParams["uid"] = $uid;
                $useRecordParams["ai_type"] = 1;
                $useRecordParams["template_id"] = $params["template_id"];
                $useRecordParams["img"] = $params["img"];
                $useRecordParams["img_layers"] = "";
                $useRecordParams["ai_generate_source"] = "";
                $useRecordParams["is_use_vip"] = 0;
                $useRecordParams["points"] = $instance->aiImgPoints;
                $useRecordParams["status"] = 0;
                $useRecordParams["channelCode"] = $userData["channelCode"];
                $useRecordParams["create_time"] = time();
                $useRecordParams["update_time"] = time();
                $useRecordRes = AiUseRecord::create($useRecordParams);
                if (!$useRecordRes) {
                    throw new \Exception("生成使用记录失败");
                }
                //发送请求到三方ai
                $aiApi = new AiApi();
                $aiRes = $aiApi->dataToAi(1, $params["img"], $params["template_id"], $useRecordRes->id);
                if (!$aiRes) {
                    throw new \Exception("ai请求失败");
                }
            });
            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        } catch (\Exception $e) {
            return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
        }
    }
    //自动脱衣
    public function imgAutoAi()
    {
        if (input("post.img") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "img" => input("post.img"),
        ];
        //查询用户当前vip
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,points,vip_expiration,channelCode")->find();
        $useRecordParams = AiUseRecord::$useRecordParams;
        if ($userData["vip_expiration"] < time()) {
            if ($userData["points"] < $this->aiAutoPoints) {
                return json_encode(["code" => 0, "msg" => "金币不足请充值", "data" => ""]);
            }
            try {
                $instance = $this;
                Db::transaction(function () use ($uid, $userData, $params, $instance, $useRecordParams) {
                    // 扣减用户 points
                    $userRes = AiUser::pointsDec($userData, $instance->aiAutoPoints);
                    if (!$userRes) {
                        throw new \Exception("扣减用户点数失败");
                    }
                    // 生成使用记录
                    $useRecordParams["uid"] = $uid;
                    $useRecordParams["ai_type"] = 2;
                    $useRecordParams["template_id"] = 0;
                    $useRecordParams["img"] = $params["img"];
                    $useRecordParams["img_layers"] = "";
                    $useRecordParams["ai_generate_source"] = "";
                    $useRecordParams["is_use_vip"] = 0;
                    $useRecordParams["points"] = $instance->aiAutoPoints;
                    $useRecordParams["status"] = 0;
                    $useRecordParams["channelCode"] = $userData["channelCode"];
                    $useRecordParams["create_time"] = time();
                    $useRecordParams["update_time"] = time();
                    $useRecordRes = AiUseRecord::create($useRecordParams);
                    if (!$useRecordRes) {
                        throw new \Exception("生成使用记录失败");
                    }
                    //发送请求到三方ai
                    $aiApi = new AiApi();
                    $aiRes = $aiApi->dataToAi(2, $params["img"], 0, $useRecordRes->id);
                    if (!$aiRes) {
                        throw new \Exception("ai请求失败");
                    }
                });
                return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
            } catch (\Exception $e) {
                return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
            }
        }
        //如果是vip判断是否剩余次数  如果次数不够扣减次数
        $orderData = AiOrder::where(["uid" => $uid, "is_vip" => 1, "pay_status" => 1])->where('vip_expired_time', '>', time())->order("create_time asc")->limit(1)->field("id,name,data")->find();
        //获取当前vip剩余次数
        if (AiOrder::availableTimes($uid, 2) > 0) {
            //生成vip使用记录 
            $useRecordParams["uid"] = $uid;
            $useRecordParams["ai_type"] = 2;
            $useRecordParams["template_id"] = 0;
            $useRecordParams["img"] = $params["img"];
            $useRecordParams["img_layers"] = "";
            $useRecordParams["ai_generate_source"] = "";
            $useRecordParams["is_use_vip"] = 1;
            $useRecordParams["points"] = 0;
            $useRecordParams["status"] = 0;
            $useRecordParams["channelCode"] = $userData["channelCode"];
            $useRecordParams["create_time"] = time();
            $useRecordParams["update_time"] = time();
            $useRecordRes = AiUseRecord::create($useRecordParams);
            //发送请求到三方ai
            $aiApi = new AiApi();
            $aiApi->dataToAi(2, $params["img"], 0, $useRecordRes->id);
            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        }
        //vip已使用完扣减余额
        if ($userData["points"] < $this->aiAutoPoints) {
            return json_encode(["code" => 0, "msg" => "金币不足请充值", "data" => ""]);
        }
        try {
            $instance = $this;
            Db::transaction(function () use ($uid, $userData, $params, $instance, $useRecordParams) {
                // 扣减用户 points
                $userRes = AiUser::pointsDec($userData, $instance->aiAutoPoints);
                if (!$userRes) {
                    throw new \Exception("扣减用户点数失败");
                }
                // 生成使用记录
                $useRecordParams["uid"] = $uid;
                $useRecordParams["ai_type"] = 2;
                $useRecordParams["template_id"] = 0;
                $useRecordParams["img"] = $params["img"];
                $useRecordParams["img_layers"] = "";
                $useRecordParams["ai_generate_source"] = "";
                $useRecordParams["is_use_vip"] = 0;
                $useRecordParams["points"] = $instance->aiAutoPoints;
                $useRecordParams["status"] = 0;
                $useRecordParams["channelCode"] = $userData["channelCode"];
                $useRecordParams["create_time"] = time();
                $useRecordParams["update_time"] = time();
                $useRecordRes = AiUseRecord::create($useRecordParams);
                if (!$useRecordRes) {
                    throw new \Exception("生成使用记录失败");
                }
                //发送请求到三方ai
                $aiApi = new AiApi();
                $aiRes = $aiApi->dataToAi(2, $params["img"], 0, $useRecordRes->id);
                if (!$aiRes) {
                    throw new \Exception("ai请求失败");
                }
            });
            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        } catch (\Exception $e) {
            return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
        }
    }
    //手动脱衣
    public function imgManualAi()
    {
        if (input("post.img") == "" || input("post.img_layers") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "img" => input("post.img"),
            "img_layers" => input("post.img_layers"),
        ];
        //查询用户当前vip
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,points,vip_expiration,channelCode")->find();
        $useRecordParams = AiUseRecord::$useRecordParams;
        if ($userData["vip_expiration"] < time()) {
            if ($userData["points"] < $this->aiManualPoints) {
                return json_encode(["code" => 0, "msg" => "金币不足请充值", "data" => ""]);
            }
            try {
                $instance = $this;
                Db::transaction(function () use ($uid, $userData, $params, $instance, $useRecordParams) {
                    // 扣减用户 points
                    $userRes = AiUser::pointsDec($userData, $instance->aiImgPoints);
                    if (!$userRes) {
                        throw new \Exception("扣减用户点数失败");
                    }
                    // 生成使用记录
                    $useRecordParams["uid"] = $uid;
                    $useRecordParams["ai_type"] = 3;
                    $useRecordParams["template_id"] = 0;
                    $useRecordParams["img"] = $params["img"];
                    $useRecordParams["img_layers"] = $params["img_layers"];
                    $useRecordParams["ai_generate_source"] = "";
                    $useRecordParams["is_use_vip"] = 0;
                    $useRecordParams["points"] = $instance->aiManualPoints;
                    $useRecordParams["status"] = 0;
                    $useRecordParams["channelCode"] = $userData["channelCode"];
                    $useRecordParams["create_time"] = time();
                    $useRecordParams["update_time"] = time();
                    $useRecordRes = AiUseRecord::create($useRecordParams);
                    if (!$useRecordRes) {
                        throw new \Exception("生成使用记录失败");
                    }
                    //发送请求到三方ai
                    $aiApi = new AiApi();
                    $aiRes = $aiApi->dataToAi(3, $params["img"], 0, $useRecordRes->id, $params["img_layers"]);
                    if (!$aiRes) {
                        throw new \Exception("ai请求失败");
                    }
                });
                return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
            } catch (\Exception $e) {
                return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
            }
        }
        //如果是vip判断是否剩余次数  如果次数不够扣减次数
        $orderData = AiOrder::where(["uid" => $uid, "is_vip" => 1, "pay_status" => 1])->where('vip_expired_time', '>', time())->order("create_time asc")->limit(1)->field("id,name,data")->find();
        //获取当前vip剩余次数
        if (AiOrder::availableTimes($uid, 3) > 0) {
            //生成vip使用记录 
            $useRecordParams["uid"] = $uid;
            $useRecordParams["ai_type"] = 3;
            $useRecordParams["template_id"] = 0;
            $useRecordParams["img"] = $params["img"];
            $useRecordParams["img_layers"] = $params["img_layers"];
            $useRecordParams["ai_generate_source"] = "";
            $useRecordParams["is_use_vip"] = 1;
            $useRecordParams["points"] = 0;
            $useRecordParams["status"] = 0;
            $useRecordParams["channelCode"] = $userData["channelCode"];
            $useRecordParams["create_time"] = time();
            $useRecordParams["update_time"] = time();
            $useRecordRes = AiUseRecord::create($useRecordParams);
            //发送请求到三方ai
            $aiApi = new AiApi();
            $aiApi->dataToAi(3, $params["img"], 0, $useRecordRes->id, $params["img_layers"]);

            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        }
        //vip已使用完扣减余额
        if ($userData["points"] < $this->aiManualPoints) {
            return json_encode(["code" => 0, "msg" => "金币不足请充值", "data" => ""]);
        }
        try {
            $instance = $this;
            Db::transaction(function () use ($uid, $userData, $params, $instance, $useRecordParams) {
                // 扣减用户 points
                $userRes = AiUser::pointsDec($userData, $instance->aiManualPoints);
                if (!$userRes) {
                    throw new \Exception("扣减用户点数失败");
                }
                // 生成使用记录
                $useRecordParams["uid"] = $uid;
                $useRecordParams["ai_type"] = 3;
                $useRecordParams["template_id"] = 0;
                $useRecordParams["img"] = $params["img"];
                $useRecordParams["img_layers"] = $params["img_layers"];
                $useRecordParams["ai_generate_source"] = "";
                $useRecordParams["is_use_vip"] = 0;
                $useRecordParams["points"] = $instance->aiManualPoints;
                $useRecordParams["status"] = 0;
                $useRecordParams["channelCode"] = $userData["channelCode"];
                $useRecordParams["create_time"] = time();
                $useRecordParams["update_time"] = time();
                $useRecordRes = AiUseRecord::create($useRecordParams);
                if (!$useRecordRes) {
                    throw new \Exception("生成使用记录失败");
                }
                //发送请求到三方ai
                $aiApi = new AiApi();
                $aiRes = $aiApi->dataToAi(3, $params["img"], 0, $useRecordRes->id, $params["img_layers"]);
                if (!$aiRes) {
                    throw new \Exception("ai请求失败");
                }
            });
            return json_encode(["code" => 1, "msg" => "succ", "data" => ""]);
        } catch (\Exception $e) {
            return json_encode(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
        }
    }


}