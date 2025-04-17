<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Channels extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'type'          => 'string',
        'downtime'          => 'int',
		'cost'          => 'decimal',
		'income'          => 'decimal',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	function getlist($where,$cxdate)
	{
	    $products = new Products();
	    $tongji = new Tongji();
	    
	    $list = $tongji::where($where)->group('channelCode')->select();
	    for($i=0;$i<count($list);$i++)
	    {
	        $where1[] =$cxdate;
	        $where1[] = ['channelCode','=',$list[$i]['channelCode']];
	        $list[$i]['clicks'] = $tongji::where($where1)->sum('clicks');
	        $where1 = null;
	        $map1 = null;
	    }
	    return $list;
	}
}

?>