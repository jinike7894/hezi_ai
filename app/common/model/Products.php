<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Products extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'name'          => 'string',
        'k_name'          => 'string',
        'ad_position'     => 'tinyint',
        'is_home'          => 'tinyint',
        'is_banner'          => 'tinyint',
        'is_browser'          => 'tinyint',
        'img'        => 'string',
        'androidurl'      => 'string',
        'is_apk'          => 'int',
        'iosurl'       => 'string',
        'slogan'          => 'string',
        'is_best'          => 'int',
        'glory'          => 'string',
        'fav'          => 'string',
        'txt'          => 'string',
        'pid'          => 'int',
        'cid'          => 'int',
        'due_date'     => 'string',
        'sort'          => 'int',
        'downnum'          => 'int',
        'status'          => 'tinyint',
        'pics'      => 'string',
        'content'      => 'string',
		'remark'          => 'string',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
		"ai_activity_switch"=>"int",
		"ai_activity_show_type"=>"int",
		"ai_activity_free_points"=>"int",
		"ai_activity_pro_type"=>"int",
		"ai_activity_sort"=>"int",
		"ai_activity_update_switch"=>"int",
    ];
	
	public function search($page=1,$limit=10,$wd)
	{
	    $map[]=['name','like',"%{$wd}%"];
	    $map[] = ['status','=',1];
	    $map[] = ['is_banner','=',0];
        $map[] = ['ad_position','=',0];
	    $map1[] = ['slogan','like',"%{$wd}%"];
	    $map1[] = ['status','=',1];
	    $map1[] = ['is_banner','=',0];
        $map1[] = ['ad_position','=',0];
	    $list = $this->field('id,img,name,slogan,downnum')->whereOr([$map,$map1])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
	    if(count($list)==0)
	    {
	        $map2[] = ['status','=',1];
	        $map2[] = ['is_banner','=',0];
            $map2[] = ['ad_position','=',0];
	        $page--;
	        $list = $this->field('id,img,name,slogan,downnum')->where($map2)->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
	    }
	    return $list;
	}
	
	public function getproducts($page=1,$limit=10,$pid=1)
	{
	    $map[] = ['status','=',1];
	    if(is_array($pid)){
	        $map[]=['pid','in',$pid];
	    }else{
	        $map[]=['pid','=',$pid];
    	    if (in_array($pid, [9,10,11])) {
    	       $map[] = ['is_banner','=',1];
    	    }else{
    	         $map[] = ['is_banner','=',0];
    	    }
	    }
	    
        $map[] = ['ad_position','=',0];
	    if($pid == 3)
	    {
	        $list = $this->field('id,img,pics,name,slogan,is_best,glory,fav,txt,downnum,androidurl,androidurl as url,is_apk,is_browser,iosurl,cid')->where($map)->order('is_best desc,sort asc,id asc')->page($page, $limit)->select();
	    }else{
	        $list = $this->field('id,img,pics,name,slogan,is_best,glory,fav,txt,downnum,androidurl,androidurl as url,is_apk,is_browser,iosurl,cid')->where($map)->order('sort asc,id asc')->page($page, $limit)->select();
	    }
        $ip = GetIP();
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
	    for($i=0;$i<count($list);$i++) {
	        //$list[$i]['downnum'] = round($list[$i]['downnum'] /10000) . '万';
            $list[$i]['name'] = str_replace('{city}', str_replace('市', '', $region), $list[$i]['name']);
	    }
	 
	    return $list;
	}
	
	
	public function getproductsTotal($pid=1)
	{
	    $map[] = ['status','=',1];
	    if(is_array($pid)){
	        $map[]=['pid','in',$pid];
	    }else{
	        $map[]=['pid','=',$pid];
    	    if (in_array($pid, [9,10,11])) {
    	       $map[] = ['is_banner','=',1];
    	    }else{
    	         $map[] = ['is_banner','=',0];
    	    }
	    }

        $map[] = ['ad_position','=',0];
        
        $query = $this->field('id,img,pics,name,slogan,is_best,glory,fav,txt,downnum,androidurl,androidurl as url,is_apk,is_browser,iosurl')->where($map)->order('sort asc,id asc');

	   $total = $query->count();     

        return $total;
	}
	//获取已加入ai任务中心的产品 is_over联合activity表查询
	public static function getAiActivityData($type,$uid,$page,$limit,$where=[]){
		$productToAiActivityData=self::alias('product')
		->where(['product.ai_activity_switch'=>1,"product.status"=>1])
		->where($where)
		->whereNull('product.delete_time')
		->whereIn('product.ai_activity_show_type', [$type, 3])
		->order("product.sort asc")
// 		->leftJoin('ai_activity_record activity', 'activity.pid = product.id  and activity.uid='.$uid)
		->field('product.id,product.name,product.is_apk,product.is_browser,product.img, product.androidurl,product.ai_activity_show_type,product.ai_activity_free_points,product.ai_activity_pro_type,product.ai_activity_update_switch')
		->paginate([
			'list_rows' => $limit,
			'page' => $page
		]);
		if($productToAiActivityData){
		    $productToAiActivityData=$productToAiActivityData->toArray();
		    foreach($productToAiActivityData["data"] as $pk=>$pv){
		        $productToAiActivityData["data"][$pk]["is_finish"]=0;
		             if($pv["ai_activity_update_switch"]==1){
		                   $todayStart = strtotime(date('Y-m-d 00:00:00')); // 获取当天零点时间戳
                           $todayEnd = strtotime(date('Y-m-d 23:59:59'));  // 获取当天最后一秒时间戳
                           $recordData = AiActivityRecord::where([
                                         "pid" =>$pv["id"],
                                         "uid" => $uid
                                          ])->where("create_time", ">", $todayStart)
                                          ->where("create_time", "<=", $todayEnd)
                                          ->find();
                           if($recordData){
                               $productToAiActivityData["data"][$pk]["is_finish"]=1;
                           }
		             }else{
		                  $recordData = AiActivityRecord::where([
                                         "pid" => $pv["id"],
                                         "uid" => $uid
                                          ])->find();
                            if($recordData){
                               $productToAiActivityData["data"][$pk]["is_finish"]=1;
                           }
		             }
		    }
		}
		
	
		return $productToAiActivityData;
	}
}

?>