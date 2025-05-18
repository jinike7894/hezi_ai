<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class Subid extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'channelCode'          => 'string',
        'subid'          => 'int',  //子ID
        'sum'          => 'int', //每日安装数
        'click'          => 'int', //每日点击数
        'date'          => 'date',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	
}

?>