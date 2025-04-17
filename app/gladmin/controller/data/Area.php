<?php
namespace app\gladmin\controller\data;

use app\common\model\Areas;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\data
 * @ControllerAnnotation(title="地区管理")
 */
class Area extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Areas();
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
            $count = $this->model->where($where)->group('type')->count();
            if(empty($cxdate))
			{
				$cxdate[] = ['date','>=',date('Y-m-d',strtotime("-1 day"))];
				$cxdate[] = ['date','<=',date('Y-m-d',strtotime("-1 day"))];
			}
            $list = $this->model->getlist($where,$cxdate,$channelCode);
            //$count = $this->model->getcount($where);
            //$list = $this->model->where($where)->page($page, $limit)->order($this->sort)->group('type')->select();
			/*$this->Tongji = new \app\common\model\Tongji();

            
			for($i=0;$i<count($list);$i++)
			{
				$map = $cxdate;
				$map[] = ['pid','=', $list[$i]['id']];
				$list[$i]['clicks'] = $this->Tongji->where($map)->sum('clicks');
				if($list[$i]['clicks'])
				{
					$list[$i]['cost'] = @round($list[$i]['income'] / $list[$i]['clicks'],2);
				}else{
					$list[$i]['cost'] = 0;
				}
				
				$map = null;
			}*/
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