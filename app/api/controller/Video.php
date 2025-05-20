<?php
namespace app\api\controller;

use app\api\controller\AiBase;
use app\api\controller\AiApi;
use app\common\model\Products;

use think\facade\Db;
use app\common\model\AiTag;
use app\common\model\AiVideoHistory;
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
            $cate[$i]['videoList'] = AiCate::getVideoDataByCid($cate[$i]['id'],5);
        }
        $list['cateParentList'] = $cateParentList;
        $list['areaList'] = [
            ['area' => 'china','name' => '华语'],['area' => 'japan','name' => '日本'],['area' => 'omei','name' => '欧美'],['area' => 'cartoon','name' => '动漫'],['area' => 'korea','name' => '韩国']
        ];
       $list['companyList'] = [
           ['cid' => 17,'name' => '麻豆传媒'], ['cid' => 19,'name' => '爱豆传媒'], ['cid' => 20,'name' => '七度空间'], ['cid' => 21,'name' => 'Love6'], ['cid' => 22,'name' => '大象传媒'], ['cid' => 23,'name' => '蜜桃传媒'], ['cid' => 24,'name' => '天美传媒'], ['cid' => 25,'name' => '星空传媒'],
       ];
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

       $videoList = AiVideo::where(["cate_id" => $params['cid'], "status" => 1])->field('id as vid, points,title as vod_name, enpic')->cache(3600)->paginate([
           'list_rows' => $limit,
           'page' => $params["page"],
       ]);
       for ($i = 0; $i < count($videoList); $i++) {
           $videoList[$i]['enpic'] = replaceVideoCdn($videoList[$i]['enpic'],'video_img_cdn');
       }
       return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
   }

   //查看更多
   public function videoByCid()
   {
       if (input("get.cid") == "" || input("get.page") == "" || input("get.limit") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "cid" => input("get.cid"),
           "page" => input("get.page"),
           "limit" => input("get.limit"),
       ];
       $cate = AiCate::where("id",$params["cid"])->field("id,title")->find();
       $videoList = AiVideo::where(["cate_id" => $params['cid'], "status" => 1])->field('id as vid, points,title as vod_name, enpic')->cache(3600)->paginate([
           'list_rows' => $params["limit"],
           'page' => $params["page"],
       ]);
       for ($i = 0; $i < count($videoList); $i++) {
           $videoList[$i]['enpic'] = replaceVideoCdn($videoList[$i]['enpic'],'video_img_cdn');
       }
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
       $videoList = AiVideo::where(["status" => 1])->field('id as vid, points,title as vod_name, enpic')->order('id desc')->cache(3600)->paginate([
           'list_rows' => $params["limit"],
           'page' => $params["page"],
       ]);
       for ($i = 0; $i < count($videoList); $i++) {
           $videoList[$i]['enpic'] = replaceVideoCdn($videoList[$i]['enpic'],'video_img_cdn');
       }
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

       $cate = AiCate::where(['pid' => $params['pid'],"is_recommend" => 0])->field("id,title")->order("sort desc")->select();
       for ($i = 0; $i < count($cate); $i++) {
           $cate[$i]['videoList'] = AiCate::getVideoDataByCid($cate[$i]['id'],5);
       }
       return json_encode(["code" => 1, "msg" => "succ", "data" => $cate]);
   }

   //获取筛选分类视频列表
   public function filter(){

       if (input("get.page") == "" || input("get.limit") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }

       $params = [
           "page" => input("get.page"),
           "limit" => input("get.limit"),
           "area" => input("get.area"),
           "cid" => input("get.cid"),
           "sort" => input("get.sort"),
       ];
       $where = [];
       if($params['area'] != ""){
           $where[] = ['area', '=', $params['area']];
       }
       if($params['cid'] != ""){
           $where[] = ['id', '=', $params['cid']];
       }
       $order = '';
       if($params['sort'] == "new"){
           $order = "id desc";
       }
       if($params['sort'] == "hot"){
           $order = "eyes desc";
       }

       $cateIdArray = AiCate::where($where)->field("id")->select()->toArray();

       $ids = array_column($cateIdArray, 'id');

       $videoList = AiVideo::wherein('cate_id',$ids)->field('id as vid,cate_id, points,title as vod_name, enpic ,eyes')->order($order)->cache(3600)->paginate([
           'list_rows' => $params["limit"],
           'page' => $params["page"],
       ]);
       for ($i = 0; $i < count($videoList); $i++) {
           $videoList[$i]['enpic'] = replaceVideoCdn($videoList[$i]['enpic'],'video_img_cdn');
       }
       return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
   }

   //获取视频详情
   public function detail(){
       if (input("get.vid") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "id" => input("get.vid"),
       ];

       $video = AiVideo::where(['id' => $params['id']])->field('id as vid,cate_id,points,title as vod_name,enpic,video as play_url,eyes')->find();

       // Todo 喜欢和收藏 联合查询
       $video->is_favorite = AiFavorite::where(['vid' => $params['id'],'uid' => $this->uid])->count() ? 1 : 0;
       $video->is_collect = AiCollect::where(['vid' => $params['id'],'uid' => $this->uid])->count() ? 1 : 0;


       $relatedVideos = AiVideo::where('id', '<>', $params['id']) // 排除当前视频
           ->where('cate_id', $video->cate_id)
           ->field('id as vid,points,title as vod_name,enpic')
           ->orderRaw('rand()') // 随机排序
           ->limit(6) // 限制数量
           ->select()
           ->toArray();
       for ($i = 0; $i < count($relatedVideos); $i++) {
           $relatedVideos[$i]['enpic'] = replaceVideoCdn($relatedVideos[$i]['enpic'],'video_img_cdn');
       }
       $video->enpic = replaceVideoCdn($video->enpic,'video_img_cdn');
       $video->play_url = replaceVideoCdn($video->play_url,'video_play_cdn');
       $video->eyes += 1;
       $video->save();
       $checkEmpty = AiVideoHistory::where(['uid' => $this->uid, 'vid' => $params['id']])->find();
       if(!$checkEmpty){
           $videoHistory = new AiVideoHistory();
           $videoHistory->uid = $this->uid;
           $videoHistory->vid = $params['id'];
           $videoHistory->save();
       }
       $list['video'] = $video;
       $list['relatedVideoList'] = $relatedVideos;
       return json_encode(["code" => 1, "msg" => "succ", "data" => $list]);
   }

   //视频搜索
   public function search(){

       if (input("post.keyword") == "" || input("post.page") == "" || input("post.limit") == "") {
           return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
       }
       $params = [
           "title" => input("post.keyword"),
           "page" => input("post.page"),
           "limit" => input("post.limit"),
       ];
       $videoList = AiVideo::where('title', 'LIKE', '%' . $params['title'] . '%')
           ->field('id as vid,points,title as vod_name,enpic')
           ->cache(3600)
           ->paginate([
               'list_rows' => $params["limit"],
               'page' => $params["page"],
           ]);
       for ($i = 0; $i < count($videoList); $i++) {
           $videoList[$i]['enpic'] = replaceVideoCdn($videoList[$i]['enpic'],'video_img_cdn');
       }
       return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
   }

    //获取视频历史记录-足迹
    public function history(){
        if (input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }

        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $historyArray = AiVideoHistory::where(['uid' => $this->uid])->field("vid")->select()->toArray();
        $videoList = [];
        if($historyArray){
            $vids = array_column($historyArray, 'vid');
            $videoList = AiVideoHistory::getJoinVideoData($vids,$params['limit'],$params['page']);
        }
        return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
    }
    //获取用户喜欢视频列表
    public function favorite(){
        if (input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }

        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $favoriteArray = AiFavorite::where(['uid' => $this->uid])->field("vid")->select()->toArray();
        $videoList = [];
        if($favoriteArray){
            $vids = array_column($favoriteArray, 'vid');
            $videoList = AiFavorite::getJoinVideoData($vids,$params['limit'],$params['page']);
        }
        return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
    }
    //获取用户收藏视频列表
    public function collect(){
        if (input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }

        $params = [
            "page" => input("get.page"),
            "limit" => input("get.limit"),
        ];
        $collectArray = AiCollect::where(['uid' => $this->uid])->field("vid")->select()->toArray();
        $videoList = [];
        if($collectArray){
            $vids = array_column($collectArray, 'vid');
            $videoList = AiCollect::getJoinVideoData($vids,$params['limit'],$params['page']);
        }
        return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
    }

    //用户喜欢视频
    public function addFavorite()
    {
        if (input("post.vid") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "id" => input("post.vid"),
        ];
        $checkEmpty = AiFavorite::where(['uid' => $this->uid, 'vid' => $params['id']])->find();
        if(!$checkEmpty){
            $result = new AiFavorite();
            $result->uid = $this->uid;
            $result->vid = $params['id'];
            $result->save();
        }else{
            $checkEmpty->delete();
        }
        return json_encode(["code" => 1, "msg" => "succ", "data" => []]);
    }
    //用户收藏视频
    public function addCollect(){
        if (input("post.vid") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "id" => input("post.vid"),
        ];
        $checkEmpty = AiCollect::where(['uid' => $this->uid, 'vid' => $params['id']])->find();
        if(!$checkEmpty){
            $result = new AiCollect();
            $result->uid = $this->uid;
            $result->vid = $params['id'];
            $result->save();
        }else{
            $checkEmpty->delete();
        }
        return json_encode(["code" => 1, "msg" => "succ", "data" => []]);
    }

    //发现页获取所有标签
    public function tagList()
    {
        $tag = AiTag::where(['pid' => 0])->field("id,title")->order("sort desc")->select();
        for ($i = 0; $i < count($tag); $i++) {
            $tag[$i]['tagList'] = AiTag::getTagByPid($tag[$i]['id']);
        }
        $list['tag'] = $tag;
        $list['searchTag'] = AiTag::getSearchTag();
        return json_encode(["code" => 1, "msg" => "succ", "data" => $list]);
    }

    //通过标签获取视频列表
    public function tagVideoList()
    {
        if (input("get.tag_id") == "" || input("get.page") == "" || input("get.limit") == "") {
            return json_encode(["code" => 0, "msg" => "参数错误", "data" => ""]);
        }
        $params = [
            "tag_id" => input("get.tag_id"),
            "page" => input("get.page"),
            "limit" => input("get.limit"),
            "sort" => input("get.sort",'new'),
        ];
        $order = '';
        if($params['sort'] == "new"){
            $order = "id desc";
        }
        if($params['sort'] == "hot"){
            $order = "eyes desc";
        }

        $videoList = AiVideo::where('FIND_IN_SET(?, tags)', [$params['tag_id']])->field('id as vid,cate_id, points,title as vod_name, enpic ,eyes')->order($order)->cache(3600)->paginate([
            'list_rows' => $params["limit"],
            'page' => $params["page"],
        ]);
        return json_encode(["code" => 1, "msg" => "succ", "data" => $videoList]);
    }

}