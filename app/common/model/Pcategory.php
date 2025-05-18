<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class Pcategory extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'title'          => 'string',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
}

?>