<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class Showtongji extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'pid'          => 'int',
        'channelCode'          => 'string',
        'shows'          => 'int',
        'date'          => 'datetime',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	
	public function report($ot)
	{
		$list = $this->where($ot)->find();
		if(empty($list))
		{
			$ot['shows'] = 1;
			$this->save($ot);
		}else{
			$this->where($ot)->update(['shows'=>Db::raw('shows+1')]);
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