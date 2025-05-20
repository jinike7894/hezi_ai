<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
use app\common\model\AiPointsBill;
class AiCate extends \think\Model
{
    //获取首页推荐列表
    public static function getVideoDataByCid($cid,$limit){
        $videoData = self::alias('cate')
            ->where(["cate.status"=>1,"cate.id"=>$cid])
            ->order("cate.sort desc")
            ->leftJoin('ai_video video', 'video.cate_id = cate.id')
            ->field('video.id as vid,video.points as points,video.title as vod_name,video.enpic as enpic')
            ->limit($limit)
            ->cache(3600)
            ->select()
            ->toArray();
        for ($i = 0; $i < count($videoData); $i++) {
            $videoData[$i]['enpic'] = replaceVideoCdn($videoData[$i]['enpic'],'video_img_cdn');
        }
        return $videoData;
    }
}

?>