<?php
namespace app\api\controller;

use app\BaseController;
use \think\facade\Db;

class Tongbu extends BaseController
{
    public function index()
    {
        $to_file_name = "tongbu.sql"; //导出文件名
        //获取表名
        $tablist = array('dh_user','dh_channelcode','dh_qdtongji');
        $sqlStr=null;
        file_put_contents($to_file_name,'-- ---------');
        foreach ($tablist as $val)
        {
            $sql = "show create table ".$val;
            $res = Db::query($sql);
            $info = "-- ----------------------------\r\n";
            $info .= "-- Table structure for `".$val."`\r\n";
            $info .= "-- ----------------------------\r\n";
            $info .= "DROP TABLE IF EXISTS `".$val."`;\r\n";
            $sqlStr = $info.$res[0]['Create Table'].";\r\n\r\n";
            //追加到文件
            file_put_contents($to_file_name,$sqlStr,FILE_APPEND);
        }
        foreach ($tablist as $val)
        {
            $sql = "select * from ".$val;
            $res = Db::query($sql);
            //如果表中没有数据，则跳出循环
            $info = "-- ----------------------------\r\n";
            $info .= "-- Records for `".$val."`\r\n";
            $info .= "-- ----------------------------\r\n";
            file_put_contents($to_file_name,$info,FILE_APPEND);
            //读取数据
            foreach($res as $v){
                $sqlStr = "INSERT INTO `".$val."` VALUES (";
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
        }
        file_put_contents($to_file_name,"-- over---------------------\r\n",FILE_APPEND);
        file_put_contents($to_file_name,"\r\n",FILE_APPEND);
    }
    
    public function dhclick()
    {
        header('Content-Type: application/json');
        $kname = input('get.kname');
        $date = date('Y-m-d');
        $this->Tongji= new \app\common\model\Tongji();
        $this->Products = new \app\common\model\Products();
        $pids = $this->Products->where(['k_name' => $kname])->column('id');
        
        $arr = [];
        for($i = 0; $i<=3;$i++){
            $dateStr = '-'.$i.' day';
            $newDate = date('Y-m-d', strtotime($dateStr, strtotime($date)));
            $clicks = $this->Tongji->where([['pid','in', $pids],['date','=', $newDate]])->sum('clicks');
            $arr[$newDate] = $clicks;
        }
     
        echo json_encode($arr);
    }
}