<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Show extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'pid'          => 'smallint',
        'channelCode'          => 'string',
        'shows'          => 'int',
		'period'          => 'string',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
    public function report($tt)
    {
		if(date('i')<30){$min = ':00';}else{$min = ':30';}
		$map[] = ['pid','=',$tt['pid']];
		$map[] = ['channelCode','=',$tt['channelCode']];
		$map[] = ['period','=',date('Y-m-d H').$min];
		$plist = $this->where($map)->find();
		if($plist)
		{
		    $this->where($map)->inc('shows')->update();
		}else{
		    $tt['period'] = date('Y-m-d H').$min;
		    $tt['shows'] = 1;
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