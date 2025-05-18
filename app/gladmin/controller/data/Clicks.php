<?php
namespace app\gladmin\controller\data;

use app\common\model\Qdtongji;
use app\common\model\Channels;
use app\common\model\Tongji;
use app\common\model\Products;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Db;
/**
 * Class Goods
 * @package app\gladmin\controller\data
 * @ControllerAnnotation(title="渠道汇总")
 */
class Clicks extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Channels();
    }
    /**
     * @NodeAnotation(title="列表")
     */
	public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames4();
            if(empty($where)) {
				$where[] = ['date','=',date('Y-m-d')];
			}
            // 获取按钮产品  按钮分类ID 后台添加后为16,TODO 每个平台不一样
            $this->Products = new \app\common\model\Products(); // 排除按钮商品的点击数
            $buttonPros = $this->Products->where(['pid' => 16])->column('id');
            $where[] = ['pid', 'not in', $buttonPros];
            $count = Tongji::where($where)->group('channelCode')->count();
            $list = Tongji::field('id,channelCode,sum(shows) as total_shows,sum(clicks) as total_clicks,sum(downfinish) as total_downfinish,date')->where($where)->group('channelCode,date')->select();
            for($i=0;$i<count($list);$i++) {
                $qdTongji = Qdtongji::field('sj_num,sum')->where(['channelCode'=>$list[$i]['channelCode'],'date'=>$list[$i]['date']])->find();
                $list[$i]['sj_num'] = $qdTongji['sj_num'] ?? 0;
                $list[$i]['ratio'] = $list[$i]['total_clicks'] > 0 ? @round($list[$i]['total_downfinish']/$list[$i]['total_clicks'],2)  : '0';
                $list[$i]['ratio1'] = $list[$i]['sj_num'] > 0 ? @round($list[$i]['total_clicks']/$list[$i]['sj_num'],2)  : '0';
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }
    public function user_report()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames4();
            if(empty($where)) {
				$where[] = ['date','=',date('Y-m-d')];
			}
            // 获取按钮产品  按钮分类ID 后台添加后为16,TODO 每个平台不一样
            $this->Products = new \app\common\model\Products(); // 排除按钮商品的点击数
            $buttonPros = $this->Products->where(['pid' => 16])->column('id');
            $where[] = ['pid', 'not in', $buttonPros];
            $count = Tongji::where($where)->group('channelCode')->count();
            $list = Tongji::field('id,channelCode,sum(shows) as total_shows,sum(clicks) as total_clicks,sum(downfinish) as total_downfinish,date')->where($where)->group('channelCode,date')->select();
           
            for($i=0;$i<count($list);$i++) {
                $qdTongji = Qdtongji::field('sj_num,sum')->where(['channelCode'=>$list[$i]['channelCode'],'date'=>$list[$i]['date']])->find();
                //安装
                $list[$i]['sj_num'] = $qdTongji['sj_num'] ?? 0;
                //点击
                $list[$i]['ratio'] = $list[$i]['total_clicks'] > 0 ? @round($list[$i]['total_downfinish']/$list[$i]['total_clicks'],2)  : '0';
                //点击/安装
                $list[$i]['ratio1'] = $list[$i]['sj_num'] > 0 ? @round($list[$i]['total_clicks']/$list[$i]['sj_num'],2)  : '0';
                $channelRes=Db::name("channelcode")->where(["channelCode"=>$list[$i]['channelCode']])->find();
                $list[$i]['uid'] =$channelRes["uid"];
                $userData=Db::name("user")->where(["id"=>$list[$i]['uid']])->find();
                $list[$i]['username'] =$userData?$userData["username"]:"";
            }
            
           
            $res_arr=[];//["username"=>"","click"=>1,"uid"=>1]
            $groupedData=[];
            foreach($list as $k=>$v){
            
               $uid = $v['uid'];
               if (!isset($groupedData[$uid])) { 
                   // 如果uid还没有在结果数组中，初始化该uid的数组 
                   $groupedData[$uid] = [
                       'username' => "", 
                       'total_clicks' => 0, 
                       'sj_num' => 0, 
                       'ratio' => 0, 
                   ];
               }
                   // 累加相关字段 
                   $groupedData[$uid]['username'] = $v['username']; 
                   $groupedData[$uid]['total_clicks'] += $v['total_clicks'];
                   $groupedData[$uid]['sj_num'] += $v['sj_num'];
               }
            
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $groupedData,
            ];
            return json($data);
        }
        return $this->fetch();
    }
    /**
     * @NodeAnotation(title="查看")
     */
    public function show()
    {
        if ($this->request->isAjax()) {
            
            
			$channel = input('channelCode', 1);
			$date = input('date', date('Y-m-d'));
			$where = [['channelCode', '=', $channel], ['date', '=', $date]];
			
            //$count = Tongji::where($where)->group('channelCode')->count();
            // 获取按钮产品  按钮分类ID 后台添加后为16,TODO 每个平台不一样
            $this->Products = new \app\common\model\Products(); // 排除按钮商品的点击数
            $buttonPros = $this->Products->where(['pid' => 16])->column('id');
            $where[] = ['pid', 'not in', $buttonPros];
            $list = Tongji::field('id,channelCode,sum(shows) as total_shows,sum(clicks) as total_clicks,sum(downfinish) as total_downfinish,date,pid')->where($where)->group('pid')->select()->toArray();
            $pids = array_column($list, 'pid');
    	    $products = Products::field('id,name,k_name')->whereIn('id', $pids)->select()->toArray();
    	    $products = array_column($products, null, 'id');
    	   
            for($i=0;$i<count($list);$i++){
                $list[$i]['ratio'] = $list[$i]['total_clicks'] > 0 ? @round($list[$i]['total_downfinish']/$list[$i]['total_clicks'],2)  : '0';
                $list[$i]['ratio1'] = $list[$i]['total_shows'] > 0 ? @round($list[$i]['total_clicks']/$list[$i]['total_shows'],2)  : '0';
                $list[$i]['pro_name'] = $products[$list[$i]['pid']]['name'] ?? '';
                $list[$i]['k_pro_name'] = $products[$list[$i]['pid']]['k_name'] ?? '';
            }
            $count = count($list);
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }
}