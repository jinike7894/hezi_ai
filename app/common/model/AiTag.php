<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
use app\common\model\AiPointsBill;
class AiTag extends \think\Model
{
    //获取发现页标签列表
    public static function getTagByPid($pid){
        $tagData = self::where(["status"=>1,"pid"=>$pid])
            ->order("id asc")
            ->field("id as tag_id,title,img")
            ->cache(3600)
            ->select()
            ->toArray();
        return $tagData;
    }

    //获取搜索页标签列表
    public static function getSearchTag()
    {
        $searchTagData = self::where(["status"=>1])
            ->where('pid','>',0)
            ->where('img','<>',null)
            ->order("sort desc")
            ->field("id as tag_id,title,img")
            ->cache(3600)
            ->select()
            ->toArray();
        return $searchTagData;
    }
}

?>