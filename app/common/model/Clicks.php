<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Clicks extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'pid'          => 'smallint',
        'channelCode'          => 'string',
        'clicks'          => 'int',
        'downfinish'          => 'int',
		'period'          => 'string',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
    public function report($tt)
    {
        $type = $tt['type'];
	    unset($tt['type']);
		if(date('i')<30){$min = ':00';}else{$min = ':30';}
		$map[] = ['pid','=',$tt['pid']];
		$map[] = ['channelCode','=',$tt['channelCode']];
		$map[] = ['period','=',date('Y-m-d H').$min];
		$plist = $this->where($map)->find();
		if($plist)
		{
		    if($type == 'downfinish')
		    {
		        $this->where($map)->inc('downfinish')->update();
		    }else{
		        $this->where($map)->inc('clicks')->update();
		    }
		}else{
		    $tt['period'] = date('Y-m-d H').$min;
		    if($type == 'downfinish')
		    {
		        $tt['clicks'] = 0;
		        $tt['downfinish'] = 1;
		    }else{
		        $tt['clicks'] = 1;
		        $tt['downfinish'] = 0;
		    }
		    $this->save($tt);
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