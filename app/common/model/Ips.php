<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Ips extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'pid'          => 'int',
        'ip'          => 'string',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	
	public function report($ct)
	{
		$this->save($ct);
	}
}

?>