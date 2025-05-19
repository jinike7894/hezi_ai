<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;
use app\gladmin\model\SystemConfig;
use app\common\model\AiPointsBill;
class AiCollect extends \think\Model
{
    public static function getJoinVideoData($vids,$limit,$page){
        $data = self::alias('record')
            ->where(["video.status"=>1])
            ->whereIn('video.id', $vids)
            ->order("record.id desc")
            ->leftJoin('ai_video video', 'video.id = record.vid')
            ->field('video.id as vid,video.cate_id, video.points, video.title as vod_name, video.enpic , video.eyes')
            ->cache(3600)
            ->paginate([
                'list_rows' => $limit,
                'page' => $page
            ]);

        return $data;
    }
}

?>