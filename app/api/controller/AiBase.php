<?php
namespace app\api\controller;

use app\BaseController;
use think\facade\Request;
use think\Response;
class AiBase extends BaseController
{
    public $uid;
    public function initialize(){
        $action = request()->action();
        if ($action != 'registerUser'&&$action != "loginByPasswd"&&$action != "customerService"&&$action != "payNotify"&&$action != "getTaskStatus"&&$action != "videoTemplateData"&&$action != "videoTemplateFindData"&&$action != "getPaymentlist") {
           // 获取请求头中的 token
        // $token = Request::header("AccessToken");
        $token = input("accessToken");
        if (!$token) {
            abort(Response::create(["code" => 401, "msg" => "请登录", "data" => ""],"json"));
        }
         $this->uid = decodeToken($token)->id ?? null;
         if(!$this->uid){
            abort(Response::create(["code" => 401, "msg" => "已过期请登录", "data" => ""],"json"));
         }
        }
          
    }
}