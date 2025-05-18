<?php
namespace app\gladmin\controller\data;

use app\common\model\AiPgathers;
use app\common\model\Pgathers;
use app\common\model\Products;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="产品汇总")
 */
class Aiactclickrecord extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new AiPgathers();
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
            list($page, $limit, $where, $cxdate,$channelCode) = $this->buildTableParames1();
            //$count = Products::where($where)->count();
            if(empty($cxdate)){
                $cxdate[] = ['date','=',date('Y-m-d')];
            }
            $where[] = ['ai_activity_switch','=',1];
            $list = $this->model->getlist($where,$cxdate,$channelCode);
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => count($list),
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }
    public function kehu()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where, $cxdate,$channelCode) = $this->buildTableParames1();
            //$count = Products::where($where)->count();
            if(empty($cxdate)){
                $cxdate[] = ['date','=',date('Y-m-d')];
            }
            $list = $this->model->getlist($where,$cxdate,$channelCode);

            $totalShows = 0;
            $totalClicks = 0;
            $totalDownfinish = 0;

            foreach ($list as $k => $item) { // 因为没有分页，总计可以循环求和即可
                $totalShows += $item['shows'];
                $totalClicks += $item['clicks'];
                $totalDownfinish += $item['downfinish'];
            }
            $groupedData=[];

            foreach($list as $k=>$v){

                $uid = $v['k_name'];
                if (!isset($groupedData[$uid])) {
                    // 如果uid还没有在结果数组中，初始化该uid的数组
                    $groupedData[$uid] = [
                        'k_name' => "",
                        "name"=>"",
                        'clicks' => 0,
                        "xingzhi"=>"",
                        "bc_click"=>0,
                        "paotai_click"=>0,
                        "zhibo_click"=>0,
                        "bofangqi_click"=>0,
                        "yaotai_click"=>0,

                    ];
                }
                // 累加相关字段
                $groupedData[$uid]['k_name'] = $v['k_name'];
                $groupedData[$uid]['name'] .= $v['name'].",";
                $groupedData[$uid]['clicks'] += $v['clicks'];
                $groupedData[$uid]['xingzhi'] = $v['cate_title'];
                switch ($v["cate_title"]) {
                    case '炮台':
                        $groupedData[$uid]['xingzhi_num'] = 2;
                        $groupedData[$uid]['paotai_click'] += $v['clicks'];
                        break;
                    case '播放器':
                        $groupedData[$uid]['xingzhi_num'] = 1;
                        $groupedData[$uid]['bofangqi_click'] += $v['clicks'];
                        break;
                    case '直播':
                        $groupedData[$uid]['xingzhi_num'] = 3;
                        $groupedData[$uid]['zhibo_click'] += $v['clicks'];
                        break;
                    case 'BC产品':
                        $groupedData[$uid]['xingzhi_num'] = 4;
                        $groupedData[$uid]['bc_click'] += $v['clicks'];
                        break;
                    case '药台':
                        $groupedData[$uid]['xingzhi_num'] = 5;
                        $groupedData[$uid]['yaotai_click'] += $v['clicks'];
                        break;
                }
                //   $groupedData[$uid]['sj_num'] += $v['sj_num'];
            }
            foreach($groupedData as $k=>$vg){
                //  $groupedData[$k]["paotai_ra"]=round(100*$vg["paotai_click"]/$vg["clicks"],2) ."%";
                //  $groupedData[$k]["bofangqi_ra"]=round(100*$vg["bofangqi_click"]/$vg["clicks"],2) ."%";
                //  $groupedData[$k]["zhibo_ra"]=round(100*$vg["zhibo_click"]/$vg["clicks"],2) ."%";
                //  $groupedData[$k]["yaotai_ra"]=round(100*$vg["yaotai_click"]/$vg["clicks"],2) ."%";
                //  $groupedData[$k]["bc_ra"]=round(100*$vg["bc_click"]/$vg["clicks"],2) ."%";

            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => count($groupedData),
                'data'  => $groupedData,
                'totalRow' => [
                    'shows' => $totalShows,
                    'clicks' => $totalClicks,
                    'downfinish' => $totalDownfinish,
                ],
            ];
            return json($data);
        }
        return $this->fetch();
    }

}