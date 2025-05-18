<?php
namespace app\api\controller;

use app\BaseController;
use think\facade\Db;
class Cron extends BaseController
{
    public function initialize()
    {
        $this->Products= new \app\common\model\Products();
    }
    public function dopic()
    {
        $gif_arr = glob("./upload/*/*.gif");
        $png_arr = glob("./upload/*/*.png");
        $jpg_arr = glob("./upload/*/*.jpg");
        $webp_arr = glob("./upload/*/*.webp");
        foreach ($gif_arr as $gif)
        {
            $newgif = str_ireplace(".gif",".js",$gif);
            if(!file_exists($newgif))
            {
                copy($gif,$newgif);
            }
        }
        foreach ($png_arr as $png)
        {
            $newpng = str_ireplace(".png",".js",$png);
            if(!file_exists($newpng))
            {
                copy($png,$newpng);
            }
        }
        foreach ($jpg_arr as $jpg)
        {
            $newjpg = str_ireplace(".jpg",".js",$jpg);
            if(!file_exists($newjpg))
            {
                copy($jpg,$newjpg);
            }
        }
        foreach ($webp_arr as $web)
        {
            $newweb = str_ireplace(".webp",".js",$web);
            if(!file_exists($newweb))
            {
                copy($web,$newweb);
            }
        }
        //$result = $this->Products->todopic();
    }
    public function deldata()
    {
        //删除一周前的数据
        $this->Clicks= new \app\common\model\Clicks();
        $this->Tongji= new \app\common\model\Tongji();
        $this->ShowTongji= new \app\common\model\Showtongji();
        $this->Dev= new \app\common\model\Dev();
        $this->Show= new \app\common\model\Show();
        $r=$this->Clicks->deldata();
        echo $this->Clicks->getLastSql() . "\n";
        $q=$this->Tongji->deldata();
        echo $this->Tongji->getLastSql() . "\n";
        $s=$this->ShowTongji->deldata();
        echo $this->ShowTongji->getLastSql() . "\n";
        $t=$this->Dev->deldata();
        echo $this->Dev->getLastSql() . "\n";
        $o=$this->Show->deldata();
        echo $this->Show->getLastSql() . "\n";
        $time = strtotime("-4 day");
	    $map[] = ['create_time','<',date('Y-m-d H:i:s',$time)];
        //清理pv数据
        $res=Db::name("pv_staticcs")->where($map)->delete();
        dump($res);
        //清理downpv数据
        $res=Db::name("down_pv_staticcs")->where($map)->delete();
        dump($res);
    }
    public function autoc()
    {
        //动态调整扣量比例
        //公式 点击数*系数/单价
        $this->ChannelCode= new \app\common\model\Channelcode();
        $this->Tongji= new \app\common\model\Tongji();
        $this->Qdtongji= new \app\common\model\Qdtongji();
        $list = $this->ChannelCode->where(array('autoc'=>1))->select();
        $today = date('Y-m-d');
        // 获取按钮产品  按钮分类ID 后台添加后为16,TODO 每个平台不一样
        $this->Products = new \app\common\model\Products(); // 排除按钮商品的点击数
        $buttonPros = $this->Products->where(['pid' => 16])->column('id');
        for($i=0;$i<count($list);$i++)
        {
            $clicks = $this->Tongji->field("sum(clicks) as totalclicks")->where([['channelCode', '=', $list[$i]['channelCode']],['date', '=', $today],['pid', 'not in', $buttonPros]])->find();
            $auto_num = ceil($clicks['totalclicks'] * $list[$i]['coefficient'] / $list[$i]['price']);
            $qdtj = $this->Qdtongji->where(array('channelCode'=>$list[$i]['channelCode'],'date'=>$today))->find();
            if($qdtj)
            {
                if($auto_num > $qdtj['sum'] && $list[$i]['ratio'] >= 5){
                    $q = $this->ChannelCode->where(array('id'=>$list[$i]['id']))->dec('ratio', 5)->update();
                }else if($auto_num < $qdtj['sum'] && $list[$i]['ratio'] <= 95){
                    $q = $this->ChannelCode->where(array('id'=>$list[$i]['id']))->inc('ratio', 5)->update();
                } else if ($list[$i]['ratio'] > 100) {
                    $q = $this->ChannelCode->where(array('id'=>$list[$i]['id']))->update(['ratio' => 100]);
                }
            }
            
        }
    }
}