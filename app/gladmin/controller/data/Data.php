<?php
namespace app\gladmin\controller\data;

use app\common\model\Products;
use app\common\model\Tongji;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\data
 * @ControllerAnnotation(title="数据报表")
 */
class Data extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Products();
    }
    public function index(){
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where, $cxdate, $channelCode) = $this->buildTableParames2();
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
			$this->Tongji = new \app\common\model\Tongji();
			if(empty($cxdate)){
				$cxdate[] = ['date','=',date('Y-m-d')];
			}
			for($i=0;$i<count($list);$i++){
				$map = $cxdate;
				$map[] = ['pid','=', $list[$i]['id']];
				if(!empty($channelCode)){
				    $map[] = ['channelCode','=',$channelCode];
				}
				$list[$i]['clicks'] = $this->Tongji->where($map)->group('pid')->sum('clicks');
				$list[$i]['channelCode'] = $channelCode;
				if($list[$i]['clicks']){
					$list[$i]['cost'] = @round($list[$i]['income'] / $list[$i]['clicks'],2);
				}else{
					$list[$i]['cost'] = 0;
				}
				
				$map = null;
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
     /**
     * @NodeAnotation(title="列表")
     */
	public function index1()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where, $cxdate, $channelCode) = $this->buildTableParames2();
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
			$this->Tongji = new \app\common\model\Tongji();
			if(empty($cxdate)){
				$cxdate[] = ['date','=',date('Y-m-d')];
			}
			for($i=0;$i<count($list);$i++){
				$map = $cxdate;
				$map[] = ['pid','=', $list[$i]['id']];
				if(!empty($channelCode)){
				    $map[] = ['channelCode','=',$channelCode];
				}
				$list[$i]['clicks'] = $this->Tongji->where($map)->group('pid')->sum('clicks');
				$list[$i]['channelCode'] = $channelCode;
				if($list[$i]['clicks']){
					$list[$i]['cost'] = @round($list[$i]['income'] / $list[$i]['clicks'],2);
				}else{
					$list[$i]['cost'] = 0;
				}
				
				$map = null;
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
    public function getchannelCode()
	{
		if ($this->request->isAjax()) {
		    $clist = Tongji::field('channelCode')->group('channelCode')->select();
		    for($i=0;$i<count($clist);$i++)
		    {
		        $list[$i] = $clist[$i]['channelCode'];
		    }
            return json($list);
        }
        return $this->fetch();
	}
    public function getchannelCode1()
	{
		if ($this->request->isAjax()) {
		    $clist = Tongji::field('channelCode')->group('channelCode')->select();
		    for($i=0;$i<count($clist);$i++)
		    {
		        $list[$i]['id'] = $clist[$i]['channelCode'];
		        $list[$i]['title'] = $clist[$i]['channelCode'];
		    }
            return json($list);
        }
        return $this->fetch();
	}

}