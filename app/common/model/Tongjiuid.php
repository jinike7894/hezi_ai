<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class Tongjiuid extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'channelCode'          => 'smallint',
        'uid'          => 'int',
        'zs_clicks'          => 'int',
        'total_clicks'          => 'int',
        'date'          => 'datetime',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	
	public function report($ot)
	{
	    $pid = $ot['pid'];
	    unset($ot['pid']);
		$list = $this->where($ot)->find();
		if(empty($list))
		{
		    if($pid == 46)
		    {
		        $ot['zs_clicks'] = 1;
		    }
			$ot['total_clicks'] = 1;
			$this->save($ot);
		}else{
		    if($pid == 46)
		    {
		        $this->where($ot)->update(['zs_clicks'=>Db::raw('zs_clicks+1'),'total_clicks'=>Db::raw('total_clicks+1')]);
		    }else{
		        $this->where($ot)->update(['total_clicks'=>Db::raw('total_clicks+1')]);
		    }
		}
	}
	
	public function deletedata()
	{
	    $newdate = strtotime("-7 day");
	    $map[] = ['date','<',$newdate];
	    $list = $this->where($map)->delete();
	}
}

?>