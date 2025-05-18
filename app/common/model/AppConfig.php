<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\Db;

class AppConfig extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'name'          => 'string',
        'group'          => 'tinyint',
        'value'          => 'string',
        'remark'          => 'string',
        'sort' => 'int',
        'create_time' => 'int',
        'update_time' => 'int',
    ];
	
}

?>