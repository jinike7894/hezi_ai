<?php
namespace app\index\controller;

use app\BaseController;
use think\facade\View;

class Api extends BaseController
{
	public function initialize()
	{
		//$this->Ips= new \app\common\model\Ips();
		$this->Tongji= new \app\common\model\Tongji();
		//$this->Tongjiuid= new \app\common\model\Tongjiuid();
	}
    public function tongji()
    {
		$ip = GetIP();
		$pid = input('post.url');
		$domain = input('post.domain');
		$channelCode = intval(input('post.channelCode',0));
		$ot['pid'] = intval($pid);
		$ot['date'] = date('Y-m-d');
		$ot['channelCode'] = $channelCode;
		$this->Clicks = new \app\common\model\Clicks();
		//$ct['pid'] = intval($pid);
		//$ct['ip'] = $ip;
		$result = $this->Tongji->report($ot); //上报点击次数
		$tt['pid'] = intval($pid);
		$tt['channelCode'] = $channelCode;
		$result1 = $this->Clicks->report($tt); 
		//$result1 = $this->Ips->report($ct); //上报详细数据
		/*if($channelCode == 6 || $channelCode == 17 || $channelCode == 1 || $channelCode == 9)
		{
		    $ot['uid'] = intval(input('post.uid',0));
		    $result2 = $this->Tongjiuid->report($ot);
		}*/
	}	
	public function gourl()
	{
	    $url = input('param.url');
	    $jmurl = base64_decode($url);
	    header("Location:{$jmurl}");
	}
	public function gojmurl()
	{
	    $url = rawurldecode($_GET['url']);
	    $jmurl = openssl_decrypt($url, 'AES-128-ECB', 'daohang', 0);
	    header("Location:{$jmurl}");
	}
}
