<?php
 
namespace app\common\model;
/**
 * RefreshCosCdn
 * 刷新腾讯云CDN
 */
class CosCdn
{
    /*需要填写你的密钥，可从  https://console.qcloud.com/capi 获取 SecretId 及 $secretKey*/
    private $secretKey = '';
    private $secretId = '';
    private $action = 'PurgePathCache'; //
    private $httpUrl = 'cdn.tencentcloudapi.com';
    private $isHttps = true;
    private $httpMethod = 'POST';
    private $commonParams = [];
    private $privateParams = [];
    
    protected $endpoint = "cdn.tencentcloudapi.com";
    protected $service = "cdn";
    protected $version = "2018-06-06";
    
 
    public function __construct($config)
    {
        if(empty($config['privateParams'])){
            if($this->action=='RefreshCdnDir'){
                E('请设置需要刷新的目录');
            }else{
                E('请设置需要刷新的Url');
            }
        }else{
            $this->privateParams = $config['privateParams']; //需要刷新的url或者目录
        }
 
        $this->commonParams = [
            'Nonce' => rand(),
            'Timestamp' => time(null),
            'Action' => !empty($config['action']) ? $config['action'] : $this->action,
            'SecretId' => $this->secretId,
        ];
    }
 
    public function refresh()
    {
        $FullHttpUrl = $this->httpUrl . "/v2/index.php";
        /***************对请求参数 按参数名 做字典序升序排列，注意此排序区分大小写*************/
        $ReqParaArray = array_merge($this->commonParams, $this->privateParams);
        ksort($ReqParaArray);
 
        /**********************************生成签名原文**********************************
         * 将 请求方法, URI地址,及排序好的请求参数  按照下面格式  拼接在一起, 生成签名原文，此请求中的原文为
         * GETcvm.api.qcloud.com/v2/index.php?Action=DescribeInstances&Nonce=345122&Region=gz
         * &SecretId=AKIDz8krbsJ5yKBZQ    ·1pn74WFkmLPx3gnPhESA&Timestamp=1408704141
         * &instanceIds.0=qcvm12345&instanceIds.1=qcvm56789
         * ****************************************************************************/
        $SigTxt = $this->httpMethod . $FullHttpUrl . "?";
 
        $isFirst = true;
        foreach ($ReqParaArray as $key => $value) {
            if (!$isFirst) {
                $SigTxt = $SigTxt . "&";
            }
            $isFirst = false;
 
            /*拼接签名原文时，如果参数名称中携带_，需要替换成.*/
            if (strpos($key, '_')) {
                $key = str_replace('_', '.', $key);
            }
 
            $SigTxt = $SigTxt . $key . "=" . $value;
        }
 
        /*********************根据签名原文字符串 $SigTxt，生成签名 Signature******************/
        $Signature = base64_encode(hash_hmac('sha1', $SigTxt, $this->secretKey, true));
 
 
        /***************拼接请求串,对于请求参数及签名，需要进行urlencode编码********************/
        $Req = "Signature=" . urlencode($Signature);
        foreach ($ReqParaArray as $key => $value) {
            $Req = $Req . "&" . $key . "=" . urlencode($value);
        }
 
        /*********************************发送请求********************************/
        if ($this->httpMethod === 'GET') {
            if ($this->isHttps === true) {
                $Req = "https://" . $FullHttpUrl . "?" . $Req;
            } else {
                $Req = "http://" . $FullHttpUrl . "?" . $Req;
            }
 
            $Rsp = file_get_contents($Req);
 
        } else {
            if ($this->isHttps === true) {
                $Rsp = $this->sendPost("https://" . $FullHttpUrl, $Req, $this->isHttps);
            } else {
                $Rsp = $this->sendPost("http://" . $FullHttpUrl, $Req, $this->isHttps);
            }
        }
        return json_decode($Rsp, true);
    }
 
    private function sendPost($FullHttpUrl, $Req, $isHttps)
    {
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Req);
 
        curl_setopt($ch, CURLOPT_URL, $FullHttpUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($isHttps === true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
 
        $result = curl_exec($ch);
 
        return $result;
    }
}