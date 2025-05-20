<?php
namespace app\api\controller;

use app\api\controller\AiBase;
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
class Ai extends AiBase
{
    
    protected $aiVideoPoints = 30;//视频脱衣
    protected $aiImgPoints = 10; //图片脱衣
    protected $aiAutoPoints = 10; //自动脱衣
    protected $aiManualPoints = 10; //手动脱衣
   
    //查询用户余额和vip剩余次数
    public function vipTimes()
    {
        if (input("get.type") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "type" => input("get.type"),
            "template_id" => input("get.template_id"),
        ];
        $vipTimesParams = [
            "points" => 0,
            "vip_times" => 0,
            "consume_points" => 0,
        ];

        $uid = $this->uid;
        //查询points
        $vipTimesParams["points"] = AiUser::where(["id" => $uid])->value("points");
        $vipTimesParams["vip_times"] = AiOrder::availableTimes($uid, $params["type"]);
        switch ($params["type"]) {
            case 0:
                //查询视频模板所需金币
                $vipTimesParams["consume_points"]=AiVideoTemplate::where(["id"=>$params["template_id"]])->value("points");
          
                break;
            case 1:
                //查询视频模板所需金币
                $vipTimesParams["consume_points"]=AiImgTemplate::where(["id"=>$params["template_id"]])->value("points");
                break;
            case 2:
                $vipTimesParams["consume_points"] = $this->aiAutoPoints;
                break;
            case 3:
                $vipTimesParams["consume_points"] = $this->aiManualPoints;
        }
        //如果是vip判断是否剩余次数  如果次数不够扣减次数
        $orderData = AiOrder::where(["uid" => $uid, "is_vip" => 1, "pay_status" => 1])->where('vip_expired_time', '>', time())->order("create_time asc")->limit(1)->field("id,name,data")->find();
        if (!$orderData) {
             return responseParams(["code" => 1, "msg" => "succ", "data" => $vipTimesParams]);
        }
        //获取当前vip剩余次数
        $vipTimesParams["vip_times"] = AiOrder::availableTimes($uid, $params["type"]);
        return responseParams(["code" => 1, "msg" => "succ", "data" => $vipTimesParams]);

    }
    //视频换脸
    public function videoAi()
    {
       
        if (input("post.template_id") == "" || input("post.img") == "") {
              return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "template_id" => input("post.template_id"),
            "img" => input("post.img"),
        ];
        //查询视频模板所需金币
        $this->aiVideoPoints=AiVideoTemplate::where(["id"=>$params["template_id"]])->value("points");
        //查询用户当前vip
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,points,vip_expiration,channelCode")->find();

        $useRecordParams = AiUseRecord::$useRecordParams;

        if ($userData["vip_expiration"] < time()) {
            if ($userData["points"] < $this->aiVideoPoints) {
                 return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
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
                  return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
            } catch (\Exception $e) {
                 return responseParams(["code" => 0, "msg" => $e->getMessage(), "data" => ""]);
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
             return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
        }
        //vip已使用完扣减余额
        if ($userData["points"] < $this->aiVideoPoints) {
              return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
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
            return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
        } catch (\Exception $e) {
             return responseParams(["code" => 0, "msg" => $e->getMessage(), "data" => ""]);
          
        }
    }
    //获取视频换脸模板列表
    public function videoTemplateData()
    {
   
        if (input("get.page") == "" || input("get.limit") == "") {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $templateData = AiVideoTemplate::paginate([
            'list_rows' => $params["limit"],
            'page' => $params["page"],
        ]);
        return responseParams(["code" => 1, "msg" => "succ", "data" => $templateData]);
    }
    //获取单个视频模板
    public function videoTemplateFindData()
    {
        if (input("get.id") == "") {
            return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
  
        }
        $params = [
            "id" => input("get.id"),
        ];
        $templateFindData = AiVideoTemplate::where(["id" => $params["id"]])->find();
          return responseParams(["code" => 1, "msg" => "succ", "data" => $templateFindData]);
    }
    //获取图片模板列表
    public function imgTemplateData()
    {
        if (input("get.page") == "" || input("get.limit") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $templateData = AiImgTemplate::paginate([
            'list_rows' => $params["limit"],
            'page' => $params["page"],
        ]);
        return responseParams(["code" => 1, "msg" => "succ", "data" => $templateData]);
    }
    //获取单个图片模板列表
    public function imgTemplateFindData()
    {
        if (input("get.id") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "id" => input("get.id"),
        ];
        $templateFindData = AiImgTemplate::where(["id" => $params["id"]])->find();
         return responseParams(["code" => 1, "msg" => "succ", "data" => $templateFindData]);
    }
    //图片换脸
    public function imgAi()
    {
        if (input("post.template_id") == "" || input("post.img") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
          
        }
        $params = [
            "template_id" => input("post.template_id"),
            "img" => input("post.img"),
        ];
         //查询图片模板所需金币
         $this->aiImgPoints=AiImgTemplate::where(["id"=>$params["template_id"]])->value("points");
        //查询用户当前vip
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,points,vip_expiration,channelCode")->find();
        $useRecordParams = AiUseRecord::$useRecordParams;
        if ($userData["vip_expiration"] < time()) {
            if ($userData["points"] < $this->aiImgPoints) {
                return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
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
                 return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
                
            } catch (\Exception $e) {
                return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
          
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
              return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
         
        }
        //vip已使用完扣减余额
        if ($userData["points"] < $this->aiImgPoints) {
            return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
           
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
            return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
           
        } catch (\Exception $e) {
            return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
        }
    }
    //自动脱衣
    public function imgAutoAi()
    {
        if (input("post.img") == "") {
             return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
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
                 return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
   
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
                  return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
             
            } catch (\Exception $e) {
                 return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
              
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
             return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
           
        }
        //vip已使用完扣减余额
        if ($userData["points"] < $this->aiAutoPoints) {
              return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
           
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
             return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
    
        } catch (\Exception $e) {
             return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
         
        }
    }
    //手动脱衣
    public function imgManualAi()
    {
        if (input("post.img") == "" || input("post.img_layers") == "") {
               return responseParams(["code" => 0, "msg" => "参数错误", "data" => ""]);
         
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
                return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
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
                 return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
   
            } catch (\Exception $e) {
                 return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
           
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
            return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
            
        }
        //vip已使用完扣减余额
        if ($userData["points"] < $this->aiManualPoints) {
             return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
        
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
                 return responseParams(["code" => 1, "msg" => "succ", "data" => ""]);
        } catch (\Exception $e) {
             return responseParams(["code" => 0, "msg" => "请稍后重试", "data" => ""]);
        }
    }
    //批量获取任务
    public function getTaskStatus()
    {
        $recordData = AiUseRecord::where(["status" => 0, "is_del" => 0])->field("id,task_id")->select();
        $recordData = $recordData->toArray() ? $recordData->toArray() : [];

        if ($recordData) {
            $aiApi = new AiApi();
            $aiApi->getTaskStatus($recordData);
        }
    }
    //上传图片
    public function uploadFaceImg()
    {
        if (input("post.type") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "type" => input("post.type"),
        ];
        $aiTypePoints = $this->aiVideoPoints;
        switch ($params["type"]) {
            case 0:
                $aiTypePoints = $this->aiVideoPoints;
                break;
            case 1:
                $aiTypePoints = $this->aiImgPoints;
                break;
            case 2:
                $aiTypePoints = $this->aiAutoPoints;
                break;
            case 3:
                $aiTypePoints = $this->aiManualPoints;
                break;

        }
        $uid = $this->uid;
        $userData = AiUser::where(["id" => $uid])->field("id,username,points,vip_expiration,channelCode")->find();
        $ActivityRecordCount=AiActivityRecord::where(["uid" => $uid])->count();
        //判断是否有金币
        if ($userData["vip_expiration"] < time()) {
            if (AiOrder::availableTimes($uid, $params["type"]) <= 0) {
                if ($userData["points"] < $aiTypePoints) {
                    //第一次跳转 任务中心
                    if($ActivityRecordCount>1){
                        return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
                
                    }
                    //如果完成过金币任务跳转充值
                       return responseParams(["code" => 302, "msg" => "金币不足请先赚取金币", "data" => ""]);
                
                }
            }
        } else {
            if ($userData["points"] < $aiTypePoints) {
              //第一次跳转 任务中心
              if($ActivityRecordCount>1){
                  return responseParams(["code" => 301, "msg" => "金币不足请充值", "data" => ""]);
            }
            //如果完成过金币任务跳转充值
              return responseParams(["code" => 302, "msg" => "金币不足请先赚取金币", "data" => ""]);

            }
        }
        $file = request()->file('image'); // 获取上传的图片

        if ($file) {
            // 设置文件类型和大小限制
            $validate = validate([
                'image' => 'fileSize:5242880|fileExt:jpg,png,gif,jpeg,webp'
            ]);

            // 进行文件验证
            if (!$validate->check(['image' => $file])) {
                return responseParams(["code" => 0, "msg" => "图片类型或者大小不符合要求", "data" => ""]);
           
            }
            // 获取文件扩展名
            $extension = $file->getOriginalExtension();
            // 生成唯一文件名
            $filename = 'img_' . date('Ymd_His') . '_' . uniqid() . '.' . $extension;
            // 指定存储目录（以日期分类存储）
            $directory = 'upload/aiimg/' . date('Ymd');
            //获取文件生成js格式

            // 存储文件到指定目录
            \think\facade\Filesystem::disk('public')->putFileAs($directory, $file, $filename);
            $imgPath = "./" . $directory . "/" . $filename;
            $fileBaseName = pathinfo($imgPath, PATHINFO_FILENAME);
            $newPath = "./" . $directory . "/" . $fileBaseName . ".js";
            $filData = file_get_contents($imgPath);
            file_put_contents($newPath, $filData);
            return responseParams(["code" => 1, "msg" => "上传成功", "data" => "/" . $directory . '/' . $filename]);
        } else {
             return responseParams(["code" => 0, "msg" => "未选择文件", "data" => ""]);
        }

    }

}