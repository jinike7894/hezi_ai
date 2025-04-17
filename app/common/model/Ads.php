<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Ads extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'type'          => 'string',
        'value'          => 'string',
		'pic'          => 'string',
		'status'          => 'tinyint',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
    
    public function getlist()
    {
        $list=$this->where(array('status'=>1))->orderRaw('rand()')->limit(1)->find();
        return $list;
    }
}

?>