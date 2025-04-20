<?php
// 应用公共文件

use app\common\service\AuthService;
use think\facade\Cache;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
if (!function_exists('__url')) {

    /**
     * 构建URL地址
     * @param string $url
     * @param array $vars
     * @param bool $suffix
     * @param bool $domain
     * @return string
     */
    function __url(string $url = '', array $vars = [], $suffix = true, $domain = false)
    {
        return url($url, $vars, $suffix, $domain)->build();
    }
}

if (!function_exists('password')) {

    /**
     * 密码加密算法
     * @param $value 需要加密的值
     * @param $type  加密类型，默认为md5 （md5, hash）
     * @return mixed
     */
    function password($value)
    {
        $value = sha1('blog_') . md5($value) . md5('_encrypt') . sha1($value);
        return sha1($value);
    }

}

if (!function_exists('get_location')) {
    function get_location($ip, $key = '') {
        $location = \app\common\extend\ipLocation\IpLocation::getLocation($ip);
        if ($key !== '') {
            $location = $location[$key] ?? '';
        }
        return $location;
    }
}

if (!function_exists('xdebug')) {

    /**
     * debug调试
     * @deprecated 不建议使用，建议直接使用框架自带的log组件
     * @param string|array $data 打印信息
     * @param string $type 类型
     * @param string $suffix 文件后缀名
     * @param bool $force
     * @param null $file
     */
    function xdebug($data, $type = 'xdebug', $suffix = null, $force = false, $file = null)
    {
        /*!is_dir(runtime_path() . 'xdebug/') && mkdir(runtime_path() . 'xdebug/');
        if (is_null($file)) {
            $file = is_null($suffix) ? runtime_path() . 'xdebug/' . date('Ymd') . '.txt' : runtime_path() . 'xdebug/' . date('Ymd') . "_{$suffix}" . '.txt';
        }
        file_put_contents($file, "[" . date('Y-m-d H:i:s') . "] " . "========================= {$type} ===========================" . PHP_EOL, FILE_APPEND);
        $str = (is_string($data) ? $data : (is_array($data) || is_object($data)) ? print_r($data, true) : var_export($data, true)) . PHP_EOL;
        $force ? file_put_contents($file, $str) : file_put_contents($file, $str, FILE_APPEND);*/
    }
}

if (!function_exists('sysconfig')) {

    /**
     * 获取系统配置信息
     * @param $group
     * @param null $name
     * @return array|mixed
     */
    function sysconfig($group, $name = null)
    {
        $where = ['group' => $group];
        $value = empty($name) ? Cache::get("sysconfig_{$group}") : Cache::get("sysconfig_{$group}_{$name}");
        if (empty($value)) {
            if (!empty($name)) {
                $where['name'] = $name;
                $value = \app\gladmin\model\SystemConfig::where($where)->value('value');
                Cache::tag('sysconfig')->set("sysconfig_{$group}_{$name}", $value, 3600);
            } else {
                $value = \app\gladmin\model\SystemConfig::where($where)->column('value', 'name');
                Cache::tag('sysconfig')->set("sysconfig_{$group}", $value, 3600);
            }
        }
        return $value;
    }
}

if (!function_exists('appconfig')) {

    /**
     * 获取系统配置信息
     * @param $group
     * @param null $name
     * @return array|mixed
     */
    function appconfig($group, $name = null)
    {
        $where = ['group' => $group];
        $value = empty($name) ? Cache::get("appconfig_{$group}") : Cache::get("appconfig_{$group}_{$name}");
        if (empty($value)) {
            if (!empty($name)) {
                $where['name'] = $name;
                $value = \app\common\model\AppConfig::where($where)->value('value');
                Cache::tag('appconfig')->set("appconfig_{$group}_{$name}", $value, 3600);
            } else {
                $value = \app\common\model\AppConfig::where($where)->column('value', 'name');
                Cache::tag('appconfig')->set("appconfig_{$group}", $value, 3600);
            }
        }
        return $value;
    }
}
if(!function_exists('aesEncrypt')){
    /**
     * 加密
     * @param $phone
     * @return bool
     */
    //php AES加密算法
    function aesEncrypt($data)
    {
        $privateKey = "pz8yvtyidpxv97a6";
        $iv = "pe8y9tx1guglgke3";
        $option = 0;
        //加密
        $decrypted = openssl_encrypt($data,'AES-128-CBC', $privateKey, $option, $iv);
        return $decrypted;
        
    }
}

if(!function_exists('aesDecrypt')){
    //php AES解密算法
    function aesDecrypt($data)
    {
        $privateKey = "pz8yvtyidpxv97a6";
        $iv = "pe8y9tx1guglgke3";
        $option = 0;
        //解密
        $decrypted = openssl_decrypt($data, 'AES-128-CBC', $privateKey, $option, $iv);
        return $decrypted;
    }
}
if(!function_exists('isMobile')){
    function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[\d]{9}$|^17[0,1,3,6,7,8]{1}\d{8}$|^18[\d]{9}$|^19[\d]{9}$#', $mobile) ? true : false;
        
    }
}

if (!function_exists('array_format_key')) {

    /**
     * 二位数组重新组合数据
     * @param $array
     * @param $key
     * @return array
     */
    function array_format_key($array, $key)
    {
        $newArray = [];
        foreach ($array as $vo) {
            $newArray[$vo[$key]] = $vo;
        }
        return $newArray;
    }

}

if (!function_exists('auth')) {

    /**
     * auth权限验证
     * @param $node
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function auth($node = null)
    {
        $authService = new AuthService(session('admin.id'));
        $check = $authService->checkNode($node);
        return $check;
    }

}
if (!function_exists('getIP')) {
	function getIP(){
		if (isset($_SERVER)) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$realip = $_SERVER['HTTP_CLIENT_IP'];
			} else {
				$realip = $_SERVER['REMOTE_ADDR'];
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$realip = getenv( "HTTP_X_FORWARDED_FOR");
			} elseif (getenv("HTTP_CLIENT_IP")) {
				$realip = getenv("HTTP_CLIENT_IP");
			} else {
				$realip = getenv("REMOTE_ADDR");
			}
		}
		return $realip;
	}
}
if (!function_exists('curl_file_get_contents')) {
	function curl_file_get_contents($durl)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $durl);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	}
}
if (!function_exists('remoteimg')) {
    function remoteimg($url,$img)
    {
        if(substr($img,0,4) == 'http'){
            return $img;
        }else{
            return $url . $img;
        }
    }
}
if (!function_exists('cjqd')) {
    function cjqd($channelCode,$url)
    {
        if(stripos($url,'{channelCode}') !== false){
            $url = str_replace('{channelCode}',$channelCode,$url);
        }
        if(stripos($url,'{randstr}') !== false){
            $randstr = substr(md5(time()),1,6);
            $url = str_replace('{randstr}',$randstr,$url);
        }
        return $url;
    }
}
if (!function_exists('cjqd2')) {
    function cjqd2($channelCode,$url)
    {
        if($url == '#') return $url;
        if(stripos($url,'{channelCode}') !== false){
            return '/index/api/gourl?url=' . base64_encode(str_replace('{channelCode}',$channelCode,$url));
        }else{
            return '/index/api/gourl?url=' . base64_encode($url);
        }
    }
}
if (!function_exists('cjqd1')) {
    function cjqd1($channelCode,$url)
    {
        if($url == '#') return $url;
        if(stripos($url,'{channelCode}') !== false){
            return '/index/api/gojmurl?url=' . rawurlencode(openssl_encrypt(str_replace('{channelCode}',$channelCode,$url), 'AES-128-ECB', 'daohang', 0));
        }else{
            return '/index/api/gojmurl?url=' . rawurlencode(openssl_encrypt($url, 'AES-128-ECB', 'daohang', 0));
        }
    }
}
if (!function_exists('jiami')) {
    function jiami($str)
    {
        return openssl_encrypt($str, 'AES-128-ECB', '=^%7@84%0^!8892-', 0);
    }
}
if (!function_exists('jiemi')) {
    function jiemi($str)
    {
        return openssl_decrypt($str, 'AES-128-ECB', '=^%7@84%0^!8892-', 0);
    }
}
if (!function_exists('unicode_encode')) {
    /**
     * * $str 原始中文字符串
* $encoding 原始字符串的编码，默认utf-8
* $prefix 编码后的前缀，默认"&#"
* $postfix 编码后的后缀，默认";"
*/
function unicode_encode($str, $encoding = 'utf-8', $prefix = '&#', $postfix = ';') {
 //将字符串拆分
 $str = iconv("UTF-8", "gb2312", $str);
 $cind = 0;
 $arr_cont = array();
 for ($i = 0; $i < strlen($str); $i++) {
 if (strlen(substr($str, $cind, 1)) > 0) {
  if (ord(substr($str, $cind, 1)) < 0xA1) { //如果为英文则取1个字节
  array_push($arr_cont, substr($str, $cind, 1));
  $cind++;
  } else {
  array_push($arr_cont, substr($str, $cind, 2));
  $cind+=2;
  }
 }
 }
 foreach ($arr_cont as &$row) {
 $row = iconv("gb2312", "UTF-8", $row);
 }
 //转换Unicode码
 $unicodestr = '';
 foreach ($arr_cont as $key => $value) {
 $unicodestr.= $prefix . base_convert(bin2hex(iconv('utf-8', 'UCS-4', $value)), 16, 10) .$postfix;
 }
 return $unicodestr;
}
}

if (!function_exists('unicode_decode')) {
    /**
     * * $str Unicode编码后的字符串
     * * $encoding 原始字符串的编码，默认GBK
     * * $prefix 编码字符串的前缀，默认"&#"
     * * $postfix 编码字符串的后缀，默认";"
    */
    function unicode_decode($unistr, $encoding = 'GBK', $prefix = '&#', $postfix = ';') {
        $arruni = explode($prefix, $unistr);
        $unistr = '';
        for($i = 1, $len = count($arruni); $i < $len; $i++) {
            if (strlen($postfix) > 0) {
                $arruni[$i] = substr($arruni[$i], 0, strlen($arruni[$i]) - strlen($postfix));
            }
            $temp = intval($arruni[$i]);
            $unistr .= ($temp < 256) ? chr(0) . chr($temp) : chr($temp / 256) . chr($temp % 256);
        }
        return mb_convert_encoding('UCS-2', $encoding, $unistr);
    }
}
if (!function_exists('UnicodeEncode')) {
    function UnicodeEncode($str){
        //split word
        $unicodeStr = str_replace("\"","",json_encode($str));
        return $unicodeStr;
    }
}
if (!function_exists('qsj')) {
    function qsj($num){
        $sjs = mt_rand(0,99) + 1;
        if(($num>=1 && $num <=99 && $sjs >=1 && $sjs <= $num) || $num >=100)
        {
            return true;
        }else{
            return false;
        }
    }
}
function calculateAverage($arr) { 
    // 计算数组元素的总和 
    $sum = array_sum($arr); 
    // 获取数组的元素个数
    $count = count($arr);
    if($count==0){
        return 0;  
    }
    // 计算平均值 
    $average = $sum / $count;
    return $average; 
    
}
//图片后缀转js
function imgtypeTojs($pic_path){
     if (strpos($pic_path, 'http') === false) {
                $pic_path = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$pic_path);
        }
     return $pic_path;
}
//下载按钮切换文字
function downFont($cid){
        $downfont="在线观看~";
        switch ($cid) {
            case 1:
                //播放器
                //在线观看
                 $downfont="在线观看";
                break;
             case 3:
                 //直播
                 //进入直播
                $downfont="进入直播";
                break;
             case 4:
                //炮台
                //点击选妃
                 $downfont="点击选妃";
                // code...
                break;
             case 12:
                //药台
                //点击选购
                 $downfont="点击选购";
                // code...
                break;
             case 6:
                //bc产品
                //进入游戏
                 $downfont="进入游戏";
                // code...
                break;
            default:
               //在线观看
                break;
        }
        return $downfont;
}
//创建详情url
function createDetailUrl($channel,$id){
      return "./detail/".$channel.".html?id=".$id;
}
//创建详情2url
function createDetailUrl2($channel,$id){
      return "./detail2/".$channel.".html?id=".$id;
}
//生成token
 function generateToken($data){
    $jwt = JWT::encode($data, "ai", 'HS256');
    return $jwt;
}
//解析token
function decodeToken($token){
   
    $data = JWT::decode($token, new Key("ai", 'HS256'));
    return $data;
}
//生成随机用户名
function generateUniqueUserName(){
    $characters = 'abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $username = substr(str_shuffle($characters), 0, 8);
    return $username;
}
//生成订单号
function orderUniqueCode(){
    $order_sn = date('YmdHis') . substr(microtime(), 2, 6) . sprintf('%03d', rand(0, 999));
    return $order_sn;
    
}