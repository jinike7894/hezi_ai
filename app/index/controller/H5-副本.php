<?php
namespace app\index\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Log;
use app\gladmin\model\SystemConfig;
use think\facade\Db;

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
class H5 extends BaseController
{
	public function initialize()
	{
		$this->Products= new \app\common\model\Products();
		$config = new \app\gladmin\model\SystemConfig();
	    $this->pic_url = $config::where(['name'=>'pic_url'])->value('value');
	    $this->api_url = $config::where(['name'=>'tg_url'])->value('value');
	}
    public function index($category='sy',$pag=1,$channel)
    {
        if (empty($channel)) {
            return response('403 access forbidden!', 403);
        }
    	
    	$i = input('param.i/d',1);
    	   //     var_dump(['category'=> $category]);
        
        // var_dump(['pag'=> $pag]);
                
        // var_dump(['channel'=> $channel]);
        
        
        //ini_set('default_charset', 'UTF-8');
        // TODO 新增统计ip数
        $ip = GetIP();
        $ua = request()->header('user-agent');
        $arr['ip'] = $ip;
        $arr['ua'] = $ua;
        $channelTemp = explode('_', $channel);
        $arr['channelCode']  = $channelTemp[0];
		$arr['subid'] = intval($channelTemp[1] ?? 0);
		$arr['uuid']  = md5($ip);
        //$dev = new \app\common\model\Dev();
	    //$result = $dev->report($arr);
	    
	    $channelData = \app\common\model\Channelcode::where(['channelCode'=>$arr['channelCode']])->find();
	    
        $ua = strtolower($ua);
        $system = 'Android';
        if (strpos($ua, 'iphone') !== false || strpos($ua, 'ipad') !== false) {
            $system = 'iPhone';
        }
        
        $banners = $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl')->where(array('status'=>1,'is_banner'=>1))->order('sort asc')->limit(6)->select();
        
        $test_yp = trim($channelData['try_yp'] ?? '');
        foreach ($banners as &$item) {
            $item['url'] = $test_yp ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            if (strpos($item['img'], 'http') === false) {
                //$item['img'] = $this->pic_url . $item['img'];
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
        }
        $this->app->view->assign('banners', $banners);
        

 

        // 「新」推荐-轮播图 54
        $page = 1;
        $limit = 100;
        $pid = 54;
        $tuijianlunbo =$this->Products->getproducts($page,$limit,$pid);
        foreach ($tuijianlunbo as &$item) {
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
        }

        $this->app->view->assign('tuijianlunbo', $tuijianlunbo);
        
        // 「新」推荐-九宫格 13
        $page = 1;
        $limit = 100;
        $pid = 13;
        $tuijianjiugongge =$this->Products->getproducts($page,$limit,$pid);
      
        foreach ($tuijianjiugongge as &$item) {
            $item['url'] = $test_yp ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            //加密字符串开始
            $unicode = unpack('n*', mb_convert_encoding($item['name'], 'UTF-16BE', 'UTF-8'));
            $str = '';
            foreach ($unicode as $value) {
                $str .= '&#'.$value . ';';
            }
            $item['name'] = $str;
            //加密字符串结束
        }
        $this->app->view->assign('tuijianjiugongge', $tuijianjiugongge);
        
    
        // 「新」推荐-一排两个 42
        $page = 1;
        $limit = 100;
        $pid = 42;
        $tuijianyipailiang =$this->Products->getproducts($page,$limit,$pid);
        foreach ($tuijianyipailiang as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            $item['names'] = explode('<br />', nl2br($item['name']));
        }
        $this->app->view->assign('tuijianyipailiang', $tuijianyipailiang);
        
    
        // 「新」推荐-同城约炮 14
        $page = 1;
        $limit = 100;
        $pid = 14;
        $tuijiantongcheng =$this->Products->getproducts($page,$limit,$pid);
        foreach ($tuijiantongcheng as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            $item['names'] = explode('<br />', nl2br($item['name']));
            $item['pics'] = explode('|', $item['pics']);
            $item['yueNum'] = mt_rand(190,4303);
            $item['juli'] = mt_rand(129,3444);
        }
        $this->app->view->assign('tuijiantongcheng', $tuijiantongcheng);
        
    
        // 「新」看片-九宫格 29
        $page = 1;
        $limit = 100;
        $pid = 29;
        $kanpianjiugongge =$this->Products->getproducts($page,$limit,$pid);
        foreach ($kanpianjiugongge as &$item) {
            $item['url'] = $test_yp ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            //加密字符串开始
            $unicode = unpack('n*', mb_convert_encoding($item['name'], 'UTF-16BE', 'UTF-8'));
            $str = '';
            foreach ($unicode as $value) {
                $str .= '&#'.$value . ';';
            }
            $item['name'] = $str;
            //加密字符串结束
        }
        $this->app->view->assign('kanpianjiugongge', $kanpianjiugongge);
        
        
 
        // 「新」看片-一排两个 43
        $page = 1;
        $limit = 100;
        $pid = 43;
        $kanpianyipailiang =$this->Products->getproducts($page,$limit,$pid);
        foreach ($kanpianyipailiang as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            $item['names'] = explode('<br />', nl2br($item['name']));
        }
        $this->app->view->assign('kanpianyipailiang', $kanpianyipailiang);
        
        
  
        // 「新」直播-直播 28
        $page = 1;
        $limit = 100;
        $pid = 28;
        $zhibozhibo =$this->Products->getproducts($page,$limit,$pid);
        foreach ($zhibozhibo as &$item) {
            $item['url'] = $test_yp ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
                $item['sellNum'] = mt_rand(20000,100000);
                $item['onlineNum'] = round($item['sellNum'] * 0.63);
                            //加密字符串开始
            $unicode = unpack('n*', mb_convert_encoding($item['name'], 'UTF-16BE', 'UTF-8'));
            $str = '';
            foreach ($unicode as $value) {
                $str .= '&#'.$value . ';';
            }
            $item['name'] = $str;
            //加密字符串结束
        }
        $this->app->view->assign('zhibozhibo', $zhibozhibo);
        
 
        // 「新」约啪-空降上门. 30
        $page = 1;
        $limit = 100;
        $pid = 30;
        $yuepashangmen =$this->Products->getproducts($page,$limit,$pid);
        foreach ($yuepashangmen as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            $item['names'] = explode('<br />', nl2br($item['name']));
            $item['pics'] = explode('|', $item['pics']);
            $item['yueNum'] = mt_rand(190,4303);
            $item['juli'] = mt_rand(129,3444);
        }
        $this->app->view->assign('yuepashangmen', $yuepashangmen);
        
   
        // 「新」约啪-同城约炮. 46
        $page = 1;
        $limit = 100;
        $pid = 46;
        $yuepaotongcheng =$this->Products->getproducts($page,$limit,$pid);
        foreach ($yuepaotongcheng as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            $item['names'] = explode('<br />', nl2br($item['name']));
            $item['pics'] = explode('|', $item['pics']);
            $item['yueNum'] = mt_rand(190,4303);
            $item['juli'] = mt_rand(129,3444);
        }
        $this->app->view->assign('yuepaotongcheng', $yuepaotongcheng);
        
        

        // 「新」博彩-九宫格 67
        $page = 1;
        $limit = 100;
        $pid = 67;
        $bocaijiugongge =$this->Products->getproducts($page,$limit,$pid);
        foreach ($bocaijiugongge as &$item) {
            $item['url'] = $test_yp ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            //加密字符串开始
            $unicode = unpack('n*', mb_convert_encoding($item['name'], 'UTF-16BE', 'UTF-8'));
            $str = '';
            foreach ($unicode as $value) {
                $str .= '&#'.$value . ';';
            }
            $item['name'] = $str;
            //加密字符串结束
        }
        $this->app->view->assign('bocaijiugongge', $bocaijiugongge);
        

        // 「新」博彩-一排两个. 68
        $page = 1;
        $limit = 100;
        $pid = 68;
        $bocaiyipailiang =$this->Products->getproducts($page,$limit,$pid);
        foreach ($bocaiyipailiang as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
            $item['names'] = explode('<br />', nl2br($item['name']));
        }
        $this->app->view->assign('bocaiyipailiang', $bocaiyipailiang);
        

        // 「新」商城-商城. 15
        $page = 1;
        $limit = 100;
        $pid = 15;
        $shangchengshangcheng =$this->Products->getproducts($page,$limit,$pid);
        foreach ($shangchengshangcheng as &$item) {
            $item['url'] = $test_yp ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            if (strpos($item['img'], 'http') === false) {
                $item['img'] = str_ireplace(['.png','.jpg','.gif','.jpeg','.webp'],'.js',$item['img']);
            }
                $item['names'] = explode('<br />', nl2br($item['name']));
                $item['sellNum'] = mt_rand(20000,100000);
                $item['onlineNum'] = round($item['sellNum'] * 0.63);
        }
        $this->app->view->assign('shangchengshangcheng', $shangchengshangcheng);


        

        

        
        
        

        


        
 
        
        

        

	    
        // 返回跳转广告链接
        $page = 1;
	    $limit = 1;
	    $pid = 57;
	    $backjumpList =$this->Products->getproducts($page,$limit,$pid);
	    $backjumpUrl = '';
	    $backjumpAdId = 0;
	    $links = [];
	    foreach ($backjumpList as &$item) {
            $backjumpUrl = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            $backjumpAdId = $item['id'];
            
            $links[$backjumpUrl] = intval($item['content']) <=0 ? 10 : intval($item['content']);
        }
        if($links){
            $total_weight = array_sum($links);
            $random_num = mt_rand(1, $total_weight);
            $current_weight = 0;
            foreach ($links as $link => $weight) {
                $current_weight += $weight;
                if ($random_num <= $current_weight) {
                    $backjumpUrl = $link;
                    break;
                }
            }
            $backjumpstatus = isset($channelData['backjumpstatus']) ? $channelData['backjumpstatus'] : 1;
            if(!$backjumpstatus){
                $backjumpUrl = '';
            }
        }
        
	    $this->app->view->assign('backjumpUrl', $backjumpUrl);
	    $this->app->view->assign('backjumpAdId', $backjumpAdId);
	    //返回跳转广告链接结束
	    
	    // test 产品
	    $testPro = $this->Products->where(['status'=>1,'id'=>180])->find();
	    if ($testPro) {
	        $testPro['url'] = $system == 'Android' ? cjqd($channel, $testPro['androidurl']) : (cjqd($channel, $testPro['iosurl']) ?: cjqd($channel, $testPro['androidurl']));
	    }
	    $this->app->view->assign('testPro', $testPro);
	    
	    $this->app->view->assign('channel', $arr['channelCode']);
	    $this->app->view->assign('linkId', $arr['subid']);
	    $this->app->view->assign('key', 'rinimei');
	    $this->app->view->assign('base_url', $this->api_url);
	    $tongjiCode = trim($channelData['statistics_code']??'');
	    $this->app->view->assign('tongjiCode', $tongjiCode);
	    
	    $la51 = trim($channelData['51la_code']??'');
        $cnzz = trim($channelData['cnzz_code']??'');
        $channelCode = $arr['channelCode'];
        if (in_array($channelCode, ['13','14','15','16','17','18'])) {
            //la51 = '{id:"JzoRaQM3rDHratXf",ck:"JzoRaQM3rDHratXf"}';
            //$cnzz = '1281283260';
        }
	    $this->app->view->assign('cnzz', $cnzz);
	    $this->app->view->assign('la51', $la51);
        $html = $this->app->view->fetch();
        ob_start(); // 开始输出缓冲区
        header('Content-Type: text/html'); // 设置响应头为 text/html
        echo $html; // 输出 HTML 内容
        ob_end_flush(); // 输出缓冲区内容并关闭缓冲区
	}
	
	 public function index1($channel)
    {
        if (empty($channel)) {
            return response('403 access forbidden!', 403);
        }
        // TODO 新增统计ip数
        $ip = GetIP();
        $ua = request()->header('user-agent');
        $arr['ip'] = $ip;
        $arr['ua'] = $ua;
        $channelTemp = explode('_', $channel);
        $arr['channelCode']  = $channelTemp[0];
		$arr['subid'] = intval($channelTemp[1] ?? 0);
		$arr['uuid']  = md5($ip);
        //$dev = new \app\common\model\Dev();
	    //$result = $dev->report($arr);
	    
	    $channelData = \app\common\model\Channelcode::where(['channelCode'=>$arr['channelCode']])->find();
	    
        $ua = strtolower($ua);
        $system = 'Android';
        if (strpos($ua, 'iphone') !== false || strpos($ua, 'ipad') !== false) {
            $system = 'iPhone';
        }
        
        $banners = $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl')->where(array('status'=>1,'is_banner'=>1))->order('sort asc')->limit(6)->cache(600)->select();
        
        $test_yp = trim($channelData['try_yp'] ?? '');
        foreach ($banners as &$item) {
            $item['url'] = $test_yp ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
        }
        $this->app->view->assign('banners', $banners);
        
        $page = 1;
	    $limit = 240;
	    $indexList= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl')
            ->where(array('status'=>1,'is_banner'=>0, 'ad_position'=>0))
            ->where(['pid' => '13'])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
        
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        $test_home = trim($channelData['try_home'] ?? '');
        foreach ($indexList as &$item) {
            //$item['name'] = str_replace('{city}', str_replace('市', '', $region), $item['name']);
            $item['name'] = str_replace(['{','}'], ['',''], $item['name']);
            $item['url'] = $test_home ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
        }
        $this->app->view->assign('indexList', $indexList);
        // 推荐
        $page = 1;
	    $limit = 240;
	    $indexTjList= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl,downnum')
            ->where(array('status'=>1,'is_banner'=>0, 'ad_position'=>0))
            ->where(['pid' => '1'])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
        
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        $test_home = trim($channelData['try_home'] ?? '');
        foreach ($indexTjList as &$item) {
            //$item['name'] = str_replace('{city}', str_replace('市', '', $region), $item['name']);
            $item['name'] = str_replace(['{','}'], ['',''], $item['name']);
            $item['url'] = $test_home ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['downnum'] = round($item['downnum'] /10000) . '万';
        }
        $this->app->view->assign('indexTjList', $indexTjList);
        
        // 同城
        $page = 1;
	    $limit = 8;
	    $pid = 14;
	    $tongChengList =$this->Products->getproducts($page,$limit,$pid);
	    foreach ($tongChengList as $key => &$item) {
            $item['url'] = $test_yp ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['names'] = explode('<br />', nl2br($item['name']));
            $item['yueNum'] = ["52","146","118","278","169","498","175","88","796","188"][$key];
            $item['juli'] = ["569","2378","1389","6543","327","118","6968","8745","569","1126"][$key];
        }
        //\think\facade\Log::error(json_encode($tongChengList));
	    $this->app->view->assign('tongchengList', $tongChengList);
	    // 直播
        $page = 1;
	    $limit = 10;
	    $pid = 15;
	    $zhiboList =$this->Products->getproducts($page,$limit,$pid);
	    $test_zb = trim($channelData['try_zb'] ?? '');
	    foreach ($zhiboList as &$item) {
            $item['url'] = $test_zb ?: ($system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl'])));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['onlineNum'] = mt_rand(1000,10000);
        }
	    $this->app->view->assign('zhiboList', $zhiboList);
	    
	    // shiping
	    $page = 1;
	    $limit = 240;
	    $indexSpList= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl')
            ->where(array('status'=>1,'is_banner'=>0, 'ad_position'=>0))
            ->where(['pid' => '27'])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
        
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        //$test_home = trim($channelData['try_home'] ?? '');
        foreach ($indexSpList as &$item) {
            //$item['name'] = str_replace('{city}', str_replace('市', '', $region), $item['name']);
            $item['name'] = str_replace(['{','}'], ['',''], $item['name']);
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
        }
        $this->app->view->assign('indexSpList', $indexSpList);
        // 推荐
        $page = 1;
	    $limit = 240;
	    $indexSpTjList= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl,downnum')
            ->where(array('status'=>1,'is_banner'=>0, 'ad_position'=>0))
            ->where(['pid' => '29'])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
        
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        foreach ($indexSpTjList as &$item) {
            // $item['name'] = str_replace('{city}', str_replace('市', '', $region), $item['name']);
            $item['name'] = str_replace(['{','}'], ['',''], $item['name']);
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['downnum'] = round($item['downnum'] /10000) . '万';
        }
        $this->app->view->assign('indexSpTjList', $indexSpTjList);
        
        // shiping同城
        $page = 1;
	    $limit = 8;
	    $pid = 30;
	    $tongChengSpList =$this->Products->getproducts($page,$limit,$pid);
	    foreach ($tongChengSpList as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['names'] = explode('<br />', nl2br($item['name']));
            $item['yueNum'] = ["56","129","108","268","139","552","245","176","985","356"][$key];
            $item['juli'] = ["690","2178","1989","6143","927","1218","6968","8145","1569","1326"][$key];
        }
	    $this->app->view->assign('tongChengSpList', $tongChengSpList);
	    // 直播
        $page = 1;
	    $limit = 10;
	    $pid = 28;
	    $zhiboSpList =$this->Products->getproducts($page,$limit,$pid);
	    foreach ($zhiboSpList as &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['onlineNum'] = mt_rand(1000,10000);
        }
	    $this->app->view->assign('zhiboSpList', $zhiboSpList);
	    
	    //zhibo
	    // 直播
        $page = 1;
	    $limit = 10;
	    $pid = 31;
	    $zhiboZbList =$this->Products->getproducts($page,$limit,$pid);
	    foreach ($zhiboZbList as &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['onlineNum'] = mt_rand(1000,10000);
        }
	    $this->app->view->assign('zhiboZbList', $zhiboZbList);
	    
	    $page = 1;
	    $limit = 240;
	    $indexZBTjList= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl,downnum')
            ->where(array('status'=>1,'is_banner'=>0, 'ad_position'=>0))
            ->where(['pid' => '38'])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
        
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        foreach ($indexZBTjList as &$item) {
            // $item['name'] = str_replace('{city}', str_replace('市', '', $region), $item['name']);
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['downnum'] = round($item['downnum'] /10000) . '万';
        }
        $this->app->view->assign('indexZBTjList', $indexZBTjList);
        
        // 直播 shiping同城
        $page = 1;
	    $limit = 8;
	    $pid = 39;
	    $zbTongchengList = $this->Products->getproducts($page,$limit,$pid);
	    foreach ($zbTongchengList as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['names'] = explode('<br />', nl2br($item['name']));
            $item['yueNum'] = ["66","143","126","259","169","489","161","116","798","176"][$key];
            $item['juli'] = ["569","3478","2138","7679","1321","696","8215","4389","988","1390"][$key];
        }

	    $this->app->view->assign('zbTongchengList', $zbTongchengList);
	    
	    // qinglou
	    // 青楼
        $page = 1;
	    $limit = 10;
	    $pid = 32;
	    $tongChengQlList =$this->Products->getproducts($page,$limit,$pid);
	    foreach ($tongChengQlList as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['names'] = explode('<br />', nl2br($item['name']));
            $item['yueNum'] = ["79","171","116","298","144","492","165","96","798","188"][$key];
            $item['juli'] = ["429","2178","1289","7543","827","918","9968","8348","1562","3126"][$key];
        }
	    $this->app->view->assign('tongChengQlList', $tongChengQlList);
	    
	    //zhanqian
	    $page = 1;
	    $limit = 240;
	    $indexZhqList= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl')
            ->where(array('status'=>1,'is_banner'=>0, 'ad_position'=>0))
            ->where(['pid' => '36'])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
        
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        //$test_home = trim($channelData['try_home'] ?? '');
        foreach ($indexZhqList as &$item) {
            $item['name'] = str_replace('{city}', str_replace('市', '', $region), $item['name']);
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
        }
        $this->app->view->assign('indexZhqList', $indexZhqList);
        // 推荐
        $page = 1;
	    $limit = 240;
	    $indexZhqTjList= $this->Products->field('id,img,name,androidurl,is_apk,is_browser,iosurl,downnum')
            ->where(array('status'=>1,'is_banner'=>0, 'ad_position'=>0))
            ->where(['pid' => '34'])->order('sort asc,id asc')->page($page, $limit)->cache(600)->select();
        
        $location = get_location($ip);
        $region = ($location['city'] ?? '') ?: ($location['province'] ?? '');
        foreach ($indexZhqTjList as &$item) {
            // $item['name'] = str_replace('{city}', str_replace('市', '', $region), $item['name']);
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['downnum'] = round($item['downnum'] /10000) . '万';
        }
        $this->app->view->assign('indexZhqTjList', $indexZhqTjList);
        
        // shiping同城
        $page = 1;
	    $limit = 8;
	    $pid = 35;
	    $tongChengZhqList = $this->Products->getproducts($page,$limit,$pid);
	    foreach ($tongChengZhqList as $key => &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['names'] = explode('<br />', nl2br($item['name']));
            $item['yueNum'] = ["66","143","126","259","169","489","161","116","798","176"][$key];
            $item['juli'] = ["569","3478","2138","7679","1321","696","8215","4389","988","1390"][$key];
        }

	    $this->app->view->assign('tongChengZhqList', $tongChengZhqList);
	    // 直播
        $page = 1;
	    $limit = 10;
	    $pid = 33;
	    $zhiboZhqList =$this->Products->getproducts($page,$limit,$pid);
	    foreach ($zhiboZhqList as &$item) {
            $item['url'] = $system == 'Android' ? cjqd($channel, $item['androidurl']) : (cjqd($channel, $item['iosurl']) ?: cjqd($channel, $item['androidurl']));
            // if (strpos($item['img'], 'http') === false) {
            //     $item['img'] = $this->pic_url . $item['img'];
            // }
            $item['onlineNum'] = mt_rand(1000,10000);
        }
	    $this->app->view->assign('zhiboZhqList', $zhiboZhqList);
	    
	    // test 产品
	    $testPro = $this->Products->where(['status'=>1,'id'=>180])->find();
	    if ($testPro) {
	        $testPro['url'] = $system == 'Android' ? cjqd($channel, $testPro['androidurl']) : (cjqd($channel, $testPro['iosurl']) ?: cjqd($channel, $testPro['androidurl']));
	    }
	    $this->app->view->assign('testPro', $testPro);
	    $this->app->view->assign('channel', $arr['channelCode']);
	    $this->app->view->assign('linkId', $arr['subid']);
	    $this->app->view->assign('key', 'rinimei');
	    $this->app->view->assign('base_url', $this->api_url);
	    $tongjiCode = trim($channelData['statistics_code']??'');
	    $this->app->view->assign('tongjiCode', $tongjiCode);
	    
	    
        return $this->app->view->fetch('h52/index');
	}
	 public function statistics($channel){
        // $ip = GetIP();
        $ip_array = explode(",", GetIP());
        $ip = trim($ip_array[0]);
        $channelCode = $channel;
        $res=Db::name("pv_staticcs")->save(["ip"=>$ip,"channelCode"=>$channelCode,"date"=>date("Y-m-d"),"create_time"=>date("Y-m-d H:i:s")]);
        if($res){
                return json_encode(["code"=>200,"msg"=>"ok!!!".date("Y-m-d H:i:s")]);            
        }
    }
    //点击下载的pv
    public function down_statistics($channel){
        // $ip = GetIP();
        $ip_array = explode(",", GetIP());
        $ip = trim($ip_array[0]);
        $channelCode = $channel;
        $res=Db::name("down_pv_staticcs")->save(["ip"=>$ip,"channelCode"=>$channelCode,"date"=>date("Y-m-d"),"create_time"=>date("Y-m-d H:i:s")]);
        if($res){
                return json_encode(["code"=>200,"msg"=>"downpvok!!!".date("Y-m-d H:i:s")]);            
        }
    }
    public function down_auto_statistics($channel){
        // $ip = GetIP();
        $ip_array = explode(",", GetIP());
        $ip = trim($ip_array[0]);
        $channelCode = $channel;
        $res=Db::name("down_auto_pv_staticcs")->save(["ip"=>$ip,"channelCode"=>$channelCode,"date"=>date("Y-m-d"),"create_time"=>date("Y-m-d H:i:s")]);
        if($res){
                return json_encode(["code"=>200,"msg"=>"autodownpvok!!!".date("Y-m-d H:i:s")]);            
        }
    }
}
