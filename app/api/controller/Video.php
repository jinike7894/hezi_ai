<?php
namespace app\api\controller;

use app\api\controller\AiBase;
use app\api\controller\AiApi;
use app\common\model\Products;

use think\facade\Db;
use app\common\model\AiActivityRecord;
use app\common\model\AiUser;
use app\common\model\AiCollect;
use app\common\model\AiFavorite;
use app\common\model\AiCate;
use app\common\model\AiVideo;
use app\common\model\AiImgTemplate;
class Video extends AiBase
{


   //获取视频首页列表
   public function index(){
       $cate = AiCate::where(["is_recommend" => 1])->where('pid','>',0)->field("id,title")->order("sort desc")->select();

       $cateParentList = AiCate::where(['pid' => 0])->field("id as cate_pid,title")->order("sort desc")->select();
        for ($i = 0; $i < count($cate); $i++) {
            $cate[$i]['videoList'] = AiCate::getRecommendVideoData($cate[$i]['id'],5);
        }
        $list['cateParentList'] = $cateParentList;
        $list['cateVideo'] = $cate;
       return json_encode(["code" => 1, "msg" => "succ", "data" => $list]);
   }

   //换一批
   public function loadNewBatch()
   {
       if (input("get.cid") == "" || input("get.page") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "cid" => input("get.cid"),
           "page" => input("get.page"),
       ];
       $limit = 5;

       $videoList = AiVideo::where(["cate_id" => $params['cid'], "status" => 1])->field('id as vid, points,title as vod_name, enpic')->paginate([
           'list_rows' => $limit,
           'page' => $params["page"],
       ]);
       return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
   }

   //查看更多
   public function videoByCid()
   {
       if (input("get.cid") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "cid" => input("get.cid"),
       ];
       $cate = AiCate::where("id",$params["cid"])->field("id,title")->find();
       $videoList = AiVideo::where(["cate_id" => $params['cid'], "status" => 1])->field('id as vid, points,title as vod_name, enpic')->select()->toArray();
       $list['title'] = $cate->title;
       $list['videoList'] = $videoList;
       return json_encode(["code" => 1, "msg" => "succ", "data" => $list]);
   }

   //新货
   public function newVideoList()
   {
       if (input("get.page") == "" || input("get.limit") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "page" => input("get.page"),
           "limit" => input("get.limit"),
       ];
       $videoList = AiVideo::where(["status" => 1])->field('id as vid, points,title as vod_name, enpic')->order('id desc')->paginate([
           'list_rows' => $params["limit"],
           'page' => $params["page"],
       ]);
       return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
   }

   //获取分类视频列表
   public function cateVideoList()
   {
       if (input("get.cate_pid") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "pid" => input("get.cate_pid"),
       ];

       $cate = AiCate::where(['pid' => $params['pid']])->field("id,title")->order("sort desc")->select();
       for ($i = 0; $i < count($cate); $i++) {
           $cate[$i]['videoList'] = AiCate::getVideoDataByCid($cate[$i]['id'],5);
       }
       return json_encode(["code" => 1, "msg" => "succ", "data" => $cate]);
   }

   //获取筛选分类视频列表
   public function filter(){

   }

   //获取视频详情
   public function detail(){
       if (input("get.vid") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "id" => input("get.vid"),
       ];

       $video = AiVideo::where(['id' => $params['id']])->field('id as vid,cate_id,points,title as vod_name,enpic,video as play_url')->find();

       // Todo 喜欢和收藏 联合查询
       $video->is_favorite = AiFavorite::where(['vid' => $params['id'],'uid' => $this->uid])->count() ? 1 : 0;
       $video->is_collect = AiCollect::where(['vid' => $params['id'],'uid' => $this->uid])->count() ? 1 : 0;


       $relatedVideos = AiVideo::where('id', '<>', $params['id']) // 排除当前视频
           ->where('cate_id', $video->cate_id)
           ->field('id as vid,points,title as vod_name,enpic')
           ->orderRaw('rand()') // 随机排序
           ->limit(6) // 限制数量
           ->select();
       $list['video'] = $video;
       $list['relatedVideoList'] = $relatedVideos;
       return json_encode(["code" => 1, "msg" => "succ", "data" => $list]);
   }

   //视频搜索
   public function search(){

       if (input("get.keyword") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "title" => input("get.keyword"),
       ];
       $videoList = AiVideo::where('title', 'LIKE', '%' . $params['title'] . '%')
           ->field('id as vid,points,title as vod_name,enpic')
           ->select();
       return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
   }

}