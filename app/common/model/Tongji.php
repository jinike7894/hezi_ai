<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class   Tongji extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'pid'          => 'int',
        'channelCode'          => 'string',
        'shows'          => 'int',
        'clicks'          => 'int',
        'downfinish'          => 'int',
        'date'          => 'datetime',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	
	public function report($ot)
	{
	    $type = $ot['type'];
	    unset($ot['type']);
	    $subid = $ot['subid'];
	    unset($ot['subid']);
		$list = $this->where($ot)->find();
		if(empty($list))
		{
		    if($type == 'show')
		    {
		        $ot['clicks'] = 0;
		        $ot['downfinish'] = 0;
		        $ot['shows'] = 1;
		    }else if($type == 'downfinish')
		    {
		        $ot['shows'] = 0;
		        $ot['clicks'] = 0;
		        $ot['downfinish'] = 1;
		    }else{
		        $ot['shows'] = 0;
		        $ot['clicks'] = 1;
		        $ot['downfinish'] = 0;
		    }
			$this->save($ot);
		}else{
		    if($type == 'show'){
		        $this->where($ot)->update(['shows'=>Db::raw('shows+1')]);
		    }else if($type == 'downfinish'){
		        $this->where($ot)->update(['downfinish'=>Db::raw('downfinish+1')]);
		    }else{
		        $this->where($ot)->update(['clicks'=>Db::raw('clicks+1')]);
		    }
		}
		        	        if($ot['channelCode'])
                	        {
                	            $subidModel = new Subid();
                	            $map1[] = ['channelCode', '=', $ot['channelCode']];
                	            $map1[] = ['subid', '=', $subid];
                	            $map1[] = ['date', '=', date('Y-m-d')];
                	            $r = $subidModel::where($map1)->find();
                	            if(empty($r))
                	            {
                	                $sub['channelCode'] = $ot['channelCode'];
                	                $sub['subid'] = $subid;
                	                $sub['date'] = date('Y-m-d');
                	                $sub['click'] = 1;
                	                $subidModel->save($sub);
                	            }else{
                	                $subidModel->where($map1)->inc('click')->update();
                	            }
                	        }
	}
	public function deldata()
	{
	    $time = strtotime("-4 day");
	    $map[] = ['create_time','<',$time];
	    $result = $this->where($map)->delete();
	}
}

?>