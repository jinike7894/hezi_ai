<?php
namespace app\api\controller;

use app\BaseController;

class Tongzhi extends BaseController
{
    public function index()
    {
        try {
            $array = get_headers('https://dongfang.walktata.com/?channelCode=1',1);
        } catch (\Exception $e) {
            $array = array();
        }
        if(empty($array) || !preg_match('/200/',$array[0])){
            $url = 'https://api.telegram.org/bot5433247843:AAFAcytBlaUl6Frr5JkLVhABHqXR7OtgzaI/sendMessage?chat_id=-709488839&text=导航放量链接无法访问,请尽快处理(香港节点测试)!' . date('Y-m-d H:i:s');
            curl_file_get_contents($url);
        }else{
            //$url = 'https://api.telegram.org/bot5433247843:AAFAcytBlaUl6Frr5JkLVhABHqXR7OtgzaI/sendMessage?chat_id=-709488839&text=导航放量链接正常!' . date('Y-m-d H:i:s');
            //curl_file_get_contents($url);
        }
    }
    public function send()
    {
        $status = input('param.status');
        $type = input('param.type');
        $txt = '';
        if($status == 1)
        {
            $url = 'https://api.telegram.org/bot5433247843:AAFAcytBlaUl6Frr5JkLVhABHqXR7OtgzaI/sendMessage?chat_id=-709488839&text=导航放量链接正常(大陆节点测试)!' . date('Y-m-d H:i:s');
            curl_file_get_contents($url);
        }else{
            if($type == 0){
                $txt = '放量链接';
            }else{
                $txt = '图片链接';
            }
            $url = 'https://api.telegram.org/bot5433247843:AAFAcytBlaUl6Frr5JkLVhABHqXR7OtgzaI/sendMessage?chat_id=-709488839&text=导航'.$txt.'无法访问,请尽快处理(大陆节点测试)!' . date('Y-m-d H:i:s');
            curl_file_get_contents($url);
        }
    }
    public function ptsend()
    {
        $status = input('param.status');
        $pt = input('param.pt');
        $url = "https://api.telegram.org/bot5589037462:AAEuJ3K4OJMoERIxFEMwecUZLn6EQ3EXVLs/sendMessage?chat_id=-631756549&text={$pt}落地页无法访问,请人工测试并处理!" . date('Y-m-d H:i:s');
        curl_file_get_contents($url);
    }
    public function zhpsend()
    {
        $type = input('param.type');
        $pt = '';
        if($type == 0){
            $pt = '落地页';
        }else if($type == 1){
            $pt = '安卓下载';
        }else{
            $pt = '苹果下载';
        }
        $url = "https://api.telegram.org/bot5513177030:AAFtJz1f4y_12ioJ-xlKiY5fqdk3qYrZjLM/sendMessage?chat_id=-682506165&text={$pt}无法访问,请人工测试并处理!" . date('Y-m-d H:i:s');
        curl_file_get_contents($url);
    }
}