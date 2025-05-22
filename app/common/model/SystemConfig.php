<?php
namespace app\common\model;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * 有关时间的模型
 * Class TimeModel
 * @package app\common\model
 */
class SystemConfig extends \think\Model
{
     public static function getConfig($name)
    {
        $configData = self::where(["name" => $name])->field("id,name,value,remark")->find();
        if ($configData) {
            return $configData["value"];
        } else {
            return "";
        }
    }
}