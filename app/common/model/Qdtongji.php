<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use think\facade\Db;

class Qdtongji extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'channelCode'          => 'string',
        'sj_num'          => 'int',  //每日实际安装数
        'sum'          => 'int', //每日安装数
        'date'          => 'date',
        'status'          => 'tinyint',  //是否结算
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	
}

?>