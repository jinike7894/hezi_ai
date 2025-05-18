<?php
namespace app\common\model;

use Think\Model;
use Think\Page;
use Think\facade\Db;

class User extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'username'          => 'string',
        'password'          => 'string',
        'status' => 'tinyint',
        'ip'          => 'string',
        'nickname'          => 'string',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
    
    public function datasend()
    {
        $to_file_name = "user.sql"; //导出文件名
        //获取表名
        $sqlStr=null;
        file_put_contents($to_file_name,'-- ---------');
        $sql = "show create table dh_user";
        $res = Db::query($sql);
        $info = "-- ----------------------------\r\n";
        $info .= "-- Table structure for `dh_user`\r\n";
        $info .= "-- ----------------------------\r\n";
        $info .= "DROP TABLE IF EXISTS `dh_user`;\r\n";
        $sqlStr = $info.$res[0]['Create Table'].";\r\n\r\n";
        //追加到文件
        file_put_contents($to_file_name,$sqlStr,FILE_APPEND);
        
        $sql = "select * from dh_user";
        $res = Db::query($sql);
        //如果表中没有数据，则跳出循环
        $info = "-- ----------------------------\r\n";
        $info .= "-- Records for `dh_user`\r\n";
        $info .= "-- ----------------------------\r\n";
        file_put_contents($to_file_name,$info,FILE_APPEND);
        //读取数据
        foreach($res as $v){
            $sqlStr = "INSERT INTO `dh_user` VALUES (";
            foreach($v as $zd){
                if($zd === null)
                {
                    $sqlStr .= "null, ";
                }else{
                    $sqlStr .= "'".$zd."', ";
                }
            }
            //去掉最后一个逗号和空格
            $sqlStr = substr($sqlStr,0,strlen($sqlStr)-2);
            $sqlStr .= ");\r\n";
            file_put_contents($to_file_name,$sqlStr,FILE_APPEND);
        }
        file_put_contents($to_file_name,"-- over---------------------\r\n",FILE_APPEND);
        file_put_contents($to_file_name,"\r\n",FILE_APPEND);
        file_get_contents('http://127.0.0.1:5439/api/tongbu/getnewdata'); //调用CPA用户端拉取服务端用户数据
    }
	
}

?>