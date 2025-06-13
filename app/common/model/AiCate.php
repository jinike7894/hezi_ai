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

    public function getPidMenuList()
    {
        $map[] = ['status', '=', 1];
        $list        = $this->field('id,pid,title')
            ->where($map)
            ->select()
            ->toArray();
        $pidMenuList = $this->buildPidMenu(0, $list);
        $pidMenuList = array_merge([[
            'id'    => 0,
            'pid'   => 0,
            'title' => '顶级菜单',
        ]], $pidMenuList);
        return $pidMenuList;
    }

    protected function buildPidMenu($pid, $list, $level = 0)
    {
        $newList = [];
        foreach ($list as $vo) {
            if ($vo['pid'] == $pid) {
                $level++;
                foreach ($newList as $v) {
                    if ($vo['pid'] == $v['pid'] && isset($v['level'])) {
                        $level = $v['level'];
                        break;
                    }
                }
                $vo['level'] = $level;
                if ($level > 1) {
                    $repeatString = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    $markString   = str_repeat("{$repeatString}├{$repeatString}", $level - 1);
                    $vo['title']  = $markString . $vo['title'];
                }
                $newList[] = $vo;
                $childList = $this->buildPidMenu($vo['id'], $list, $level);
                !empty($childList) && $newList = array_merge($newList, $childList);
            }

        }
        return $newList;
    }

    public function getCateInfo($id = 0)
    {
        $list = $this->field('id,pid,title')->where(array('id'=>$id))->cache(600)->find();
        return $list;
    }
}

?>