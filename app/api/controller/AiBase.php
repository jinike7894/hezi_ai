<?php
namespace app\api\controller;

use app\BaseController;
use think\facade\Request;
use think\Response;
class Aibase extends BaseController
{
    public $uid;
    public function initialize(){
        $action = request()->action();
        if ($action != 'registerUser'&&$action != "loginByPasswd"&&$action != "customerService") {
           // 获取请求头中的 token
        $token = Request::header("accessToken");
      
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