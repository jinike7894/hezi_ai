<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
class AiUseRecord extends \think\Model
{
    public static $useRecordParams;
    public static function init() {
        self::$useRecordParams = [
            "uid" => 0,
            "ai_type" => 1,
            "template_id" => 0,
            "img" => "",
            "img_layers" => "",
            "ai_generate_source" => "",
            "is_use_vip" => 0,
            "points" => 0,
            "status" => 0,
            "channelCode" => 0,
            "create_time" => time(),
            "update_time" => time(),
        ];
    }
   
}

?>