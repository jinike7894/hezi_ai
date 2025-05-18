<?php
namespace app\index\controller;

use app\BaseController;
use think\facade\View;
use app\gladmin\model\SystemConfig;
use think\facade\Db;
class Index extends BaseController
{
	public function initialize()
	{
		$this->Products= new \app\common\model\Products();
	}
    public function index()
    {
        return 'welcome';
	}
	
	public function getip()
	{
	    $ip = GetIP();
	    $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        echo json_encode(['city' => str_replace(['市','省'], ['', ''], $region)]);
	}
	
	public function getip1()
	{
	    $ip = GetIP();
	    $location = get_location($ip);
	    $c = input('get.callback');
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        echo $c . '(' . json_encode(['city' => str_replace(['市','省'], ['', ''], $region)]) . ')';
	}
	
	public function test()
	{
	    $ga = new \google\authenticator();
	    $secret = $ga->createSecret();
	    echo $secret . "<br />";
	    $qrCodeUrl = $ga->getQRCodeGoogleUrl('红灯区-媒介', $secret);
	    echo  $qrCodeUrl;
	}
	
	public function epass(){
	   echo password('gljy123');
	}
	//渠道号同步免杀
	public function syncQd(){
	    $channelData=Db::name("channelcode")->where(["status"=>1])->select()->toArray();
	    $postData=["platform"=> "ty"];
	    if(!empty($channelData)){
	        $channelPostData=[];
	        foreach($channelData as $k=>$v){
	            $channelPostData[]=["channel_id"=>$v["channelCode"],"channel_name"=>$v["remark"]];
	        }
	        $postData["channel_list"]=$channelPostData;
	       
	    }
	   //  dump(json_encode($postData, JSON_UNESCAPED_UNICODE));die;
	    $res=$this->Curl_request("http://119.29.106.196:5455/api/apk/channel/build",json_encode($postData, JSON_UNESCAPED_UNICODE));
	    $response=json_decode($res,true);
	    if($response["code"]=="200"){
	         echo "success";
	         die;
	    }
	     echo $response["msg"];
	}
	public function Curl_request($url, $data = "", $method = 'POST'){
    $ch = curl_init();

// 设置cURL选项
curl_setopt($ch, CURLOPT_URL, $url); // 替换为你的目标URL
curl_setopt($ch, CURLOPT_POST, 1); // 设置为POST请求
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // 设置POST字段为JSON数据
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 要求cURL返回响应而不是直接输出
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json', // 设置内容类型为JSON
    'X-API-KEY:EfF715IPGQszDSnCOdXPG5Nv3qqW4XSS' // 设置内容长度（可选）
));
// 执行cURL请求并获取响应
$response = curl_exec($ch);
// 检查是否有错误发生
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
// 关闭cURL会话
curl_close($ch);
return $response;
}
}
