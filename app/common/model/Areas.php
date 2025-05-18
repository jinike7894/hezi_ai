<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Areas extends \think\Model
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
	function getlist($where,$cxdate,$channelCode)
	{
	    $products = new Products();
	    $tongji = new Tongji();
	    //$list1 = $products::field('id')->where($where1)->select()->toArray();
	    
	    $list = $products::field('id,type,income')->where($where)->group('type')->select();
	    for($i=0;$i<count($list);$i++)
	    {
	        $where1 = $where;
	        $where1[] = ['type','=',$list[$i]['type']];
	        $list[$i]['income'] = $products::where($where1)->sum('income');
	        $list1 = $products::field('id')->where($where1)->select()->toArray();
	        $ids = array_column($list1,'id');
	        $ids = implode(',',$ids);
	        if($cxdate)
	        {
	            $map1[] = $cxdate;
	        }
	        if(!empty($channelCode))
	        {
	            $map1[] = ['channelCode','=',$channelCode];
	        }
	        $map1[] =['pid','in',$ids];
	        $list[$i]['clicks'] = $tongji::where($map1)->sum('clicks');
	        $list[$i]['channelCode'] = $channelCode;
	        if($list[$i]['clicks'] ==0)
	        {
	            $list[$i]['cost'] = 0;
	        }else{
	            $list[$i]['cost'] = @round($list[$i]['income'] / $list[$i]['clicks'],2);
	        }
	        $where1 = null;
	        $map1 = null;
	    }
	    return $list;
	}
}

?>