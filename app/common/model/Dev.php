<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class Dev extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'channelCode'   => 'string',
        'subid'          => 'int',
        'uuid'          => 'string',
        'ip'          => 'string',
        'width'          => 'int',
        'height'          => 'int',
        'brand'          => 'string',
        'model'          => 'string',
        'manufacturer'   => 'string',
        'arelease'          => 'string',
        'sdk_int'          => 'int',
        'ua'          => 'string',
        'date'        => 'string',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	
	public function report($ot)
	{
	    $map[] = [['ip','=',$ot['ip']],['channelCode','=',$ot['channelCode']],['date','=',date('Y-m-d')]];
	    $list = $this->field('uuid')->where($map)->find();
	    if (!empty($ot['ua']) && strlen($ot['ua']) > 255) {
	        $ot['ua'] = substr($ot['ua'], 0, 255);
	    }
        try{
        	if(empty($list)) {
        	    $ot['date'] = date('Y-m-d');
        		$this->save($ot);
        		
        	    $channelCode = new Channelcode();
        	    $qdtongji = new Qdtongji();
        	    $result = $channelCode::where(array('channelCode'=>$ot['channelCode']))->find();
        	    if(empty($result))
        	    {
        	        $channelCode->save(['uid' => 0,'channelCode' => $ot['channelCode']]);
        	        $ct['channelCode'] = $ot['channelCode'];
        	        $ct['sj_num'] = 1;
        	        $ct['sum'] = 1;
        	        $ct['date'] = date('Y-m-d');
        	        $q = $qdtongji::where(array('channelCode'=>$ct['channelCode'],'date'=>$ct['date']))->find();
        	        if(empty($q))
        	        {
        	            $qdtongji->save($ct);
        	        }else{
        	            $qdtongji->where(array('channelCode'=>$ct['channelCode'],'date'=>$ct['date']))->inc('sj_num')->inc('sum')->update();
        	        }
        	        //只存东方凯撒数据
        	        if($ot['channelCode'])
        	        {
        	            $subid = new Subid();
        	            $map1[] = ['channelCode', '=', $ot['channelCode']];
        	            $map1[] = ['subid', '=', $ot['subid']];
        	            $map1[] = ['date', '=', date('Y-m-d')];
        	            $r = $subid::where($map1)->find();
        	            if(empty($r))
        	            {
        	                $sub['channelCode'] = $ot['channelCode'];
        	                $sub['subid'] = $ot['subid'];
        	                $sub['date'] = date('Y-m-d');
        	                $sub['sum'] = 1;
        	                $subid->save($sub);
        	            }else{
        	                $subid->where($map1)->inc('sum')->update();
        	            }
        	        }
        	     }else{
        	         $mininum = $result['mininum'];
        	         $ratio = $result['ratio'];
        	         $ct['channelCode'] = $ot['channelCode'];
        	         $ct['sj_num'] = 1;
        	         $ct['sum'] = 1;
        	         $ct['date'] = date('Y-m-d');
        	         $q = $qdtongji::where(array('channelCode'=>$ct['channelCode'],'date'=>$ct['date']))->find();
        	         if(empty($q))
        	         {
        	             $qdtongji->save($ct);
        	         }else{
        	             if($mininum > 0 && $q['sum'] > $mininum && $ratio > 0)
        	             {
        	                 //判断当日安装量已经达到最低标准，当需要扣量
        	                 if(qsj($ratio))
        	                 {
        	                     $qdtongji->where(array('channelCode'=>$ct['channelCode'],'date'=>$ct['date']))->inc('sj_num')->update();
        	                 }else{
        	                     $qdtongji->where(array('channelCode'=>$ct['channelCode'],'date'=>$ct['date']))->inc('sj_num')->inc('sum')->update();
        	                 }
        	             }else{
        	                 $qdtongji->where(array('channelCode'=>$ct['channelCode'],'date'=>$ct['date']))->inc('sj_num')->inc('sum')->update();
        	             }
        	         }
        	        //只存东方凯撒数据
        	        if($ot['channelCode'])
        	        {
        	            $subid = new Subid();
        	            $map1[] = ['channelCode', '=', $ot['channelCode']];
        	            $map1[] = ['subid', '=', $ot['subid']];
        	            $map1[] = ['date', '=', date('Y-m-d')];
        	            $r = $subid::where($map1)->find();
        	            if(empty($r))
        	            {
        	                $sub['channelCode'] = $ot['channelCode'];
        	                $sub['subid'] = $ot['subid'];
        	                $sub['date'] = date('Y-m-d');
        	                $sub['sum'] = 1;
        	                $subid->save($sub);
        	            }else{
        	                $subid->where($map1)->inc('sum')->update();
        	            }
        	        }
        	    }
		    }
        
        } catch (\Exception $e) {
            
        }
	}
	
	public function getlist($where,$cxdate)
	{
	    $channelCode = $this->field('channelCode')->where($where)->group('channelCode')->cache(600)->select();
	    for($i=0;$i<count($channelCode);$i++)
	    {
	        $list[$i]['channelCode'] = $channelCode[$i]['channelCode'];
	        $list[$i]['create_time'] = $cxdate;
	        $where1[] = ['channelCode','=',$channelCode[$i]['channelCode']];
	        $list[$i]['count'] = $this->where($where)->where($where1)->cache(600)->count();
	        unset($where1);
	    }
	    return $list;
	}
	
	public function deldata()
	{
	    $time = strtotime("-2 day");
	    $map[] = ['create_time','<',$time];
	    $result = $this->where($map)->delete();
	}
}

?>