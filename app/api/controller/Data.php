<?php
namespace app\api\controller;

use app\BaseController;
use think\facade\Log;
use think\facade\Cache;

class Data extends BaseController
{
	public function initialize()
	{
		
	}
	public function getannounce()
	{
	    $arr=array();
	    $arr['status'] = 200;
	    $arr['announce'] = appconfig('announcement','announce');
	    return jiami(json_encode($arr));
	}
	public function getversion()
	{
	    $arr=array();
	    $arr['status'] = 200;
	    $arr['version'] = (int)appconfig('upgrade','version');
	    $arr['forceupdate'] = appconfig('upgrade','forceupdate');
	    $arr['update_url'] = appconfig('upgrade','update_url');
	    $arr['update_tips'] = appconfig('upgrade','update_tips');
	    return jiami(json_encode($arr));
	}
	public function getshare()
	{
	    $arr=array();
	    $arr['status'] = 200;
	    $arr['share_url'] = appconfig('share','share_url');
	    $arr['share_qrcode'] = 'https://api.qrserver.com/v1/create-qr-code/?data=' . appconfig('share','share_url');
	    return jiami(json_encode($arr));
	}
	public function install()
	{
        $ip_array = explode(",", GetIP());
        $client_ip = trim($ip_array[0]);
        
	    $arr['ip'] = $client_ip;
		$arr['channelCode']  = input('post.channel','', 'htmlspecialchars');
		$arr['subid'] = intval(input('post.linkId'));
		if (strlen((string)$arr['subid']) > 9) {
            $arr['subid']=0;
        }
		$arr['uuid']  = md5($arr['ip']);
		$arr['width']  = input('post.width','', 'htmlspecialchars');
		$arr['height']  = input('post.height','', 'htmlspecialchars');
		$arr['brand']  = input('post.brand','', 'htmlspecialchars');
		$arr['model']  = input('post.model','', 'htmlspecialchars');
		$arr['manufacturer']  = input('post.manufacturer','', 'htmlspecialchars');
		$arr['arelease']  = input('post.release','', 'htmlspecialchars');
		$arr['sdk_int']  = input('post.sdk_int','', 'htmlspecialchars');
		$arr['ua']  = input('post.ua','', 'htmlspecialchars');
		
		$sign = input('post.sign');
		$res = [];
		if ($sign == sha1('rinimei'.sha1('rinimei'.$arr['channelCode'].$arr['subid'])))
		{
		    $this->Dev = new \app\common\model\Dev();
		    $result = $this->Dev->report($arr);
		    $res['result'] = 'ok!';
		}else{
		    $res['result'] = 'ok1';
		}
		return json_encode($res);
	}
	public function tongji()
    {
        // $timeStart = time();
        // \think\facade\Log::error('tongji start:' . $timeStart);
        header('Cache-Control: no-cache,must-revalidate');
		header('Pragma: no-cache');
		$ip_array = explode(",", GetIP());
        $client_ip = trim($ip_array[0]);
        
		$ip = $client_ip;
		//Log::error('Tongji:' . json_encode(input('post.')) . 'ip: ' . $ip . ',ua: ' . request()->server()['HTTP_USER_AGENT']);
		
		$channel = input('post.channel');
		$id = input('post.id');
		$uuid = md5($ip);
		$type = input('post.type'); //show/download/downfinish
		$sign = input('post.sign');
		if($id < 0) return;  //特殊统计，不计入服务器
		if ($type == 'download') { // 点击记3次
    		//$key = "ip_{$ip}_proid_{$id}";
    		$key = "ip_{$ip}";
    		$redis = Cache::store('redis');
    		$count = $redis->get($key) ?: 0;
    		$ipredisKey = 'ip_black';
    		if ($count && $count >= 500) { // 同一个产品同ip每天最多记10次
    		    // 存入ip到list
    		    $ordcount = $redis->hget($ipredisKey, $ip) ?: $count;
    		    $redis->hset($ipredisKey, $ip, $ordcount+1);
    		    return json_encode(['status' => 200, 'msg' => 'ok!']);
    		}
    		$redis->set($key, $count+1, strtotime(date('Y-m-d 00:00:00', strtotime('+1 day'))) - time());
		}
// 		if ($type == 'downfinish') { // 下载记1次
//     		$key = "ip_{$ip}_proid_{$id}_downfinish";
//     		$redis = Cache::store('redis');
//     		$count = $redis->get($key) ?: 0;
//     		if ($count && $count > 1) { // 同一个产品同ip每天最多记1
//     		    return json_encode(['status' => 200, 'msg' => 'ok!']);
//     		}
//     		$redis->set($key, $count+1, strtotime(date('Y-m-d 00:00:00', strtotime('+1 day'))) - time());
// 		}
// 		$time1 = time();
// 		\think\facade\Log::error('tongji check redis:' . ($time1 - $timeStart));
		$arr = array();
		if ($sign == sha1('rinimei'.sha1('rinimei'.$channel.$type.$id)))
		{
		    if($type == 'show')
		    { // 展示
		        $this->Show= new \app\common\model\Show();
		        $this->Tongji = new \app\common\model\Tongji();
		        //时段统计
		        $st['pid'] = intval($id);
		        $st['channelCode'] = $channel;
		        $result = $this->Show->report($st);
		        //每日统计
		        $ot['pid'] = intval($id);
		        $ot['channelCode'] = $channel;
		        $ot['date'] = date('Y-m-d');
		        $ot['type'] = $type;
		        $ot['subid'] = intval(input('post.linkId'));
		        if (strlen((string)$ot['subid']) > 9) {
                    $ot['subid']=0;
                }
		        $result = $this->Tongji->report($ot);
		    }else{
		        
		        //点击或产品下载完成
		        $this->Clicks = new \app\common\model\Clicks();
		        $this->Tongji = new \app\common\model\Tongji();
		        $ct['pid'] = intval($id);
		        $ct['channelCode'] = $channel;
		        $ct['type'] = $type;
		        $result = $this->Clicks->report($ct);
		      //  $time2 = time();
		      //  \think\facade\Log::error('tongji click:' . ($time2 - $time1));
		        $ot['pid'] = intval($id);
		        $ot['channelCode'] = $channel;
		        $ot['date'] = date('Y-m-d');
		        $ot['type'] = $type;
		        $ot['subid'] = intval(input('post.linkId'));
		        if (strlen((string)$ot['subid']) > 9) {
                    $ot['subid']=0;
                }
		        $result = $this->Tongji->report($ot);
		      //  $time3 = time();
		      //  \think\facade\Log::error('tongji tongji:' . ($time3 - $time2));
		        //产品增加一次下载
		        $products = new \app\common\model\Products();
		        //$products::where(array('id'=>$id))->inc('downnum')->update();
		      //  $time4 = time();
		      //  \think\facade\Log::error('tongji update pro:' . ($time4 - $time3));
		    }
		    $arr['status'] = 200;
		    $arr['msg'] = '!ok';
		}else{
		    $arr['status'] = 0;
		    $arr['msg'] = 'ok!';
		}
// 		$timeend = time();
//         \think\facade\Log::error('tongji end:' . $timeend);
		return json_encode($arr);
	}
}