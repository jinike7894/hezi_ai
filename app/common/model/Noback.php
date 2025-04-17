<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Noback extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'channelCode'          => 'tinyint',
        'noback'          => 'tinyint',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	
}

?>