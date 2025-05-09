<?php
namespace app\api\controller;

use app\BaseController;
use app\gladmin\model\SystemConfig;
use app\common\model\Products;
class Getdata extends BaseController
{
    public $pic_url = null;
	public function initialize()
	{
	    $this->Products = new \app\common\model\Products();
	    $config = new SystemConfig();
	    $this->pic_url = $config:: where(['name'=>'pic_url'])->value('value');
	}
	public function getsplash()
	{
	    $arr1['ip'] = GetIP();
		$arr1['channelCode']  = input('post.channel','');
		$arr1['uuid']  = input('post.uuid','');
		$arr1['width']  = input('post.width',0);
		$arr1['height']  = input('post.height',0);
		$arr1['brand']  = input('post.brand','');
		$arr1['model']  = input('post.model','');
		$arr1['manufacturer']  = input('post.manufacturer','');
		$arr1['arelease']  = input('post.release','');
		$arr1['sdk_int']  = input('post.sdk_int',0);
		$arr1['ua']  = input('post.ua','');
		$sign = input('post.sign','');
		$isCheat = 0;
		$isShow = 0;
		if($sign == sha1(md5('sehe'.$arr1['channelCode'].$arr1['model'].$arr1['uuid'].$arr1['brand'])))	{
		    if(strtolower($arr1['manufacturer']) == 'sprd' && ($arr1['model'] == 'Z9' || $arr1['model']) == 'Z7') {
		        $isCheat = 1;
		    }else if($arr1['brand'] == '-'){
		        $isCheat = 1;
		    }else if($arr1['brand'] == 'Civita' || $arr1['model'] == 'CA081VR' || strtoupper($arr1['manufacturer']) == 'TAOBAO'){
		        $isCheat = 1;
		    }else if($arr1['model'] == 'MuMu' || $arr1['manufacturer'] == 'Netease'){
		        $isCheat = 1;
		    }
		    $channel = \app\common\model\Channelcode::field('time_range')->where(['channelCode' => $arr1['channelCode']])->find();
		    $timeRange = $channel['time_range'] ?? '';
		    if ($timeRange) {
                $hours = explode(',', $timeRange);
                if ($hours && in_array(date('H'), $hours)) {
                    $isShow = 1;
                }
            }
		    
		}else{
		    $isCheat = 1;
		}
		
	    $arr=array();
	    $arr['status'] = 200;
	    $arr['id'] = '9999';
	    $arr['name'] = 'splash';
	    $arr['cdn_url'] = $this->pic_url;
	    $arr['isCheat'] = $isCheat;
	    $arr['isShow'] = $isShow;
	    $arr['splash_img'] = appconfig('splash','splashads');
	    $arr['splash_url'] = appconfig('splash','splash_url');
	    $arr['splash_status'] = appconfig('splash','splash_status');
	    $arr['tab_url'] = appconfig('tabconfig','tab_url');
	    return jiami(json_encode($arr));
	}
	public function gettoppic()
	{
        $list= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl')->where(array('status'=>1,'is_banner'=>1))->order('sort asc')->limit(6)->cache(600)->select();
        $arr= array();
        $arr['status'] = 200;
        $arr['data']['cdn_url'] = $this->pic_url;
        $arr['data']['list'] = $list;
        return jiami(json_encode($arr));
	}

    public function getindexdata()
	{
	    $page = input('param.page',1);
	    $limit = 20;
	    $list= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl')
            ->where(array('status'=>1,'is_banner'=>0, 'is_home' => 1,'ad_position'=>0))
            ->where([['pid', 'not in', [5,7,8,9,10,11]]])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
        $ip = GetIP();
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        foreach ($list as &$item) {
            $item['name'] = str_replace('{city}', str_replace('市', '', $region), $item['name']);
        }
	    $arr= array();
        $arr['status'] = 200;
        $arr['data']['cdn_url'] = $this->pic_url;
        $arr['data']['list'] = $list;
        return jiami(json_encode($arr));
	}
	public function getwxqq()
	{
	    $this->Ads = new \app\common\model\Ads();
	    $list = $this->Ads->getlist();
	    $arr['status'] = 200;
	    $arr['data']['state'] = 0;
	    $arr['data']['cdn_url'] = $this->pic_url;
	    /*if($list)
	    {
	        $arr['data']['type'] = $list['type'];
	        $arr['data']['value'] = $list['value'];
	        $arr['data']['qrcode'] = $list['pic'];
	    }else{
	        $arr['data']['type'] = '';
	        $arr['data']['value'] = '';
	        $arr['data']['qrcode'] = '';
	    }*/
	    $arr['data']['type'] = '';
	    $arr['data']['value'] = '';
	    $arr['data']['qrcode'] = 'https://sj1.cavk68.com:89/1/';
	    return jiami(json_encode($arr));
	}
	public function search()
	{
	    $page = input('post.page',1);
	    $limit = input('post.limit',10);
	    $wd = input('post.keywords');
	    $list =$this->Products->search($page,$limit,$wd);
	    $arr= array();
        $arr['status'] = 200;
        $arr['data']['cdn_url'] = $this->pic_url;
        $arr['data']['list'] = $list;
        return jiami(json_encode($arr));
	}
	public function detail()
	{
	    $this->Ptype = new \app\common\model\Ptype();
	    $id=input('param.id');
		$token = input("accessToken");
        if (!$token) {
			return json_encode(["code" => 401, "msg" => "请登录", "data" => ""]);
        }
		$uid = decodeToken($token)->id ?? null;

	    $channel = intval(input('param.channel'));
	    if($channel == 0){$channel = 1;}
	    $list = $this->Products->field('id,img,name,slogan,androidurl,iosurl,is_apk,pid,is_browser,downnum,pics,content')->where(array('status'=>1,'id'=>$id))->cache(600)->find();
	    $arr= array();
	    if(!empty($list))
	    {
            $ip = GetIP();
            $location = get_location($ip);
            $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
            $list['name'] = str_replace('{city}', str_replace('市', '', $region), $list['name']);
	        $arr['status'] = 200;
	        $arr['data']['cdn_url'] = $this->pic_url;
	        $list['pname'] = $this->Ptype->where(array('id'=>$list['pid']))->cache(3600)->value('title');
	        $list['pics'] =explode('|',$list['pics']);
	        $list['downnum'] = round($list['downnum'] /10000) . '万';
	        $list['androidurl'] = cjqd($channel,$list['androidurl']);
	        $list['iosurl'] = cjqd($channel,$list['iosurl']);
	         //图片后缀转js
            $list['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$list['img']);
            if(!empty($list['pics'])){
                     $newPic=[];
                    foreach($list['pics'] as $pk=>$pv){
                          $newPic[]= str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$pv);
                    }
                  $list['pics']= $newPic; 
            }
	        $arr['data']['list'] = $list;
			$arr['data']['related'] = Products::getAiActivityData(2, $uid, 1, 2);
			
	    }else{
	        $arr['status'] = 404;
	    }
		
        return jiami(json_encode($arr));
	}
	public function gettypeproducts()
	{
	    $page = input('param.page',1);
	    $limit = input('param.limit',10);
	    $pid = input('param.pid',1);
	    $list =$this->Products->getproducts($page,$limit,$pid);
	     //图片后缀转js
	    if(!empty($list)){
	        foreach($list as $lk=>&$lv){
	            $lv["img"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$lv['img']);
	            $lv["pics"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$lv['pics']);
	            $lv["glory"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$lv['glory']);
	            $lv["fav"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$lv['fav']);
	        }
	    }
	     //图片后缀转js
	    $arr= array();
        $arr['status'] = 200;
        $arr['data']['cdn_url'] = $this->pic_url;
        $arr['data']['list'] = $list;
        $map = [
            ['pid','=',$pid],
            ['status','=',1],
            ['ad_position','=',1],
        ];
        $topList = $this->Products->field('id,img,name,slogan,is_best,glory,fav,txt,downnum,androidurl,androidurl as url,is_apk,is_browser,iosurl,cid')->where($map)->cache(600)->select();
        //图片后缀转js
	    if(!empty($topList)){
	        foreach($topList as $tk=>&$tv){
	            $tv["img"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$tv['img']);
	            $tv["pics"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$tv['pics']);
	            $tv["glory"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$tv['glory']);
	            $tv["fav"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$tv['fav']);
	        }
	    }
	     //图片后缀转js
        $map1 = [
            ['pid','=',$pid],
            ['status','=',1],
            ['ad_position','=',2],
        ];
        $bottomList = $this->Products->field('id,img,name,slogan,is_best,glory,fav,txt,downnum,androidurl,androidurl as url,is_apk,is_browser,iosurl,cid')->where($map1)->cache(600)->select();
         //图片后缀转js
	    if(!empty($bottomList)){
	        foreach($bottomList as $bk=>&$bv){
	            $bv["img"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$bv['img']);
	            $bv["pics"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$bv['pics']);
	            $bv["glory"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$bv['glory']);
	            $bv["fav"]=str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$bv['fav']);
	        }
	    }
	     //图片后缀转js
        $arr['data']['topList'] = $topList;
        $arr['data']['bottomList'] = $bottomList;
        return jiami(json_encode($arr));
	}
	
	public function getpopup()
	{
	    $arr=array();
	    $arr['status'] = 200;
	    $popup_type = appconfig('popup','popup_type');
        $arr['data']['cdn_url'] = $this->pic_url;
	    $arr['data']['type'] = $popup_type;
	    $arr['data']['popup_status'] = appconfig('popup','popup_status');
	    if($popup_type == 'product')
	    {
	        $list = $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl')->where(array('id'=>appconfig('popup','pid')))->cache(600)->find();
	        $arr['data']['img'] = $list['img'];
	        $arr['data']['id'] = $list['id'];
	        $arr['data']['name'] = $list['name'];
	        $arr['data']['is_browser'] = $list['is_browser'];
	        $arr['data']['is_apk'] = $list['is_apk'];
	        $arr['data']['iosurl'] = $list['iosurl'];
	        $arr['data']['androidurl'] = $list['androidurl'];
	       // $arr['data']['url'] = '';
	       // $arr['data']['is_browser'] = '';
	    }else{
	        $arr['data']['id'] = '9998';
	        $arr['data']['name'] = '外部广告';
	        $arr['data']['img'] = appconfig('popup','ads_image');
	        $arr['data']['url'] = appconfig('popup','ads_url');
	        $arr['data']['is_browser'] = appconfig('popup','ads_browser');
	        $arr['data']['androidurl'] =  appconfig('popup','ads_url');
	        $arr['data']['is_apk'] = 0;
	        $arr['data']['iosurl'] = '';
	    }
	    return jiami(json_encode($arr));
	}
	public function gethotkeywords()
	{
	    $arr=array();
	    $arr['status'] = 200;
	    $hotword = appconfig('search','hotword');
	    $arr['data']['hotkeywords'] = explode(',',$hotword);
	    return jiami(json_encode($arr));
	}
}