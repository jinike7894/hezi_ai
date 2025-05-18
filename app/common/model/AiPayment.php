<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class AiPayment extends \think\Model
{
	// 设置字段信息
    // protected $schema = [
    //     'id'          => 'int',
    //     'name'          => 'string',
    //     'pay_icon'          => 'string',
    //     'show_tips'          => 'string',
    //     'discount'          => 'string',
    //     'appid'          => 'string',
    //     'secret'          => 'string',
    //     'sort'          => 'int',
    //     'create_time' => 'int',
    //     'update_time' => 'int',
    //     'delete_time' => 'int',
    // ];
    //获取单个支付通道
    public static function getPayMentFind($id){
        $data=self::where(["id"=>$id,"is_del"=>0])->field("id,name,discount,appid,secret,pay_gateway,pay_type,pid")->find();
        if($data){
            return $data;
        }
        return false;
    }
	
}

?>

