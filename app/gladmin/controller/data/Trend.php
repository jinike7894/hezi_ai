<?php
namespace app\gladmin\controller\data;

use app\common\model\Clicks;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="走势图")
 */
class Trend extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Clicks();
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
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model->where($where)->group('period')->count();
            $today = date('Y-m-d');
            $yesterday = date('Y-m-d',strtotime("-1 day"));
            $weekday = date('Y-m-d',strtotime("-7 day"));
            if(empty($where))
            {
                
                $where[] = ['period','like',"{$today}%"];
                $list = $this->model->field('id,sum(clicks) as tatal_clicks,period')->where($where)->page($page, $limit)->group('period')->order('period desc')->cache(600)->select();
                for($i=0;$i<48;$i++)
                {
                    $period = date('Y-m-d H:i',(strtotime($weekday) + $i*1800));
                    $wwhere[] = ['period','=',$period];
                    $week[$i] = $this->model->where($wwhere)->group('period')->cache(600)->sum('clicks');
                    $period = date('Y-m-d H:i',(strtotime($yesterday) + $i*1800));
                    $ywhere[] = ['period','=',$period];
                    $ydata[$i] = $this->model->where($ywhere)->group('period')->cache(600)->sum('clicks');
                    $period = date('Y-m-d H:i',(strtotime($today) + $i*1800));
                    $twhere[] = ['period','=',$period];
                    $tdata[$i] = $this->model->where($twhere)->group('period')->cache(600)->sum('clicks');
                    $wwhere = $ywhere = $twhere = null;
                }
            }else{
                $qdcx = false;
                $cpcx = false;
                for($i=0;$i<count($where);$i++)
                {
                    if($where[$i][0]=='channelCode')
                    {
                        $qdcx = true;
                    }else if($where[$i][0]=='pid')
                    {
                        $cpcx = true;
                    }
                }
                if($qdcx && $cpcx)
                {
                    $list = $this->model->field('id,pid,channelCode,sum(clicks) as tatal_clicks,period')->where($where)->page($page, $limit)->group('period')->order('period desc')->cache(600)->select();
                    for($i=0;$i<48;$i++)
                    {
                        $period = date('Y-m-d H:i',(strtotime($weekday) + $i*1800));
                        $wwhere = $where;
                        $wwhere[] = ['period','=',$period];
                        $week[$i] = $this->model->where($wwhere)->group('period')->cache(600)->sum('clicks');
                        $period = date('Y-m-d H:i',(strtotime($yesterday) + $i*1800));
                        $ywhere = $where;
                        $ywhere[] = ['period','=',$period];
                        $ydata[$i] = $this->model->where($ywhere)->group('period')->cache(600)->sum('clicks');
                        $period = date('Y-m-d H:i',(strtotime($today) + $i*1800));
                        $twhere = $where;
                        $twhere[] = ['period','=',$period];
                        $tdata[$i] = $this->model->where($twhere)->group('period')->cache(600)->sum('clicks');
                        $wwhere = $ywhere = $twhere = null;
                    }
                }else if($qdcx && !$cpcx){
                    $list = $this->model->field('id,channelCode,sum(clicks) as tatal_clicks,period')->where($where)->page($page, $limit)->group('period')->order('period desc')->cache(600)->select();
                    for($i=0;$i<48;$i++)
                    {
                        $period = date('Y-m-d H:i',(strtotime($weekday) + $i*1800));
                        $wwhere = $where;
                        $wwhere[] = ['period','=',$period];
                        $week[$i] = $this->model->where($wwhere)->group('period')->cache(600)->sum('clicks');
                        $period = date('Y-m-d H:i',(strtotime($yesterday) + $i*1800));
                        $ywhere = $where;
                        $ywhere[] = ['period','=',$period];
                        $ydata[$i] = $this->model->where($ywhere)->group('period')->cache(600)->sum('clicks');
                        $period = date('Y-m-d H:i',(strtotime($today) + $i*1800));
                        $twhere = $where;
                        $twhere[] = ['period','=',$period];
                        $tdata[$i] = $this->model->where($twhere)->group('period')->cache(600)->sum('clicks');
                        $wwhere = $ywhere = $twhere = null;
                    }
                }else if(!$qdcx && $cpcx){
                    $list = $this->model->field('id,pid,sum(clicks) as tatal_clicks,period')->where($where)->page($page, $limit)->group('period')->order('period desc')->cache(600)->select();
                    for($i=0;$i<48;$i++)
                    {
                        $period = date('Y-m-d H:i',(strtotime($weekday) + $i*1800));
                        $wwhere = $where;
                        $wwhere[] = ['period','=',$period];
                        $week[$i] = $this->model->where($wwhere)->group('period')->cache(600)->sum('clicks');
                        $period = date('Y-m-d H:i',(strtotime($yesterday) + $i*1800));
                        $ywhere = $where;
                        $ywhere[] = ['period','=',$period];
                        $ydata[$i] = $this->model->where($ywhere)->group('period')->cache(600)->sum('clicks');
                        $period = date('Y-m-d H:i',(strtotime($today) + $i*1800));
                        $twhere = $where;
                        $twhere[] = ['period','=',$period];
                        $tdata[$i] = $this->model->where($twhere)->group('period')->cache(600)->sum('clicks');
                        $wwhere = $ywhere = $twhere = null;
                    }
                }else{
                    $list = $this->model->field('id,sum(clicks) as tatal_clicks,period')->where($where)->page($page, $limit)->group('period')->order('period desc')->cache(600)->select();
                    for($i=0;$i<48;$i++)
                    {
                        $period = date('Y-m-d H:i',(strtotime($weekday) + $i*1800));
                        $wwhere[] = ['period','=',$period];
                        $week[$i] = $this->model->where($wwhere)->group('period')->cache(600)->sum('clicks');
                        $period = date('Y-m-d H:i',(strtotime($yesterday) + $i*1800));
                        $ywhere[] = ['period','=',$period];
                        $ydata[$i] = $this->model->where($ywhere)->group('period')->cache(600)->sum('clicks');
                        $period = date('Y-m-d H:i',(strtotime($today) + $i*1800));
                        $twhere[] = ['period','=',$period];
                        $tdata[$i] = $this->model->where($twhere)->group('period')->cache(600)->sum('clicks');
                        $wwhere = $ywhere = $twhere = null;
                    }
                }
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
                'week' => $week,
                'ydata' => $ydata,
                'tdata' => $tdata,
            ];
            return json($data);
        }
        return $this->fetch();
    }

}