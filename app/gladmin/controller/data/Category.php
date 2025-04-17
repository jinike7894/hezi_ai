<?php
namespace app\gladmin\controller\data;

use app\common\model\Pcategory;
use app\common\model\Products;
use app\common\model\Tongji;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="产品类别管理")
 */
class Category extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Pcategory();
    }
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            //list($page, $limit, $where) = $this->buildTableParames();
            list($page, $limit, $where, $cxdate,$channelCode) = $this->buildTableParames1();
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select()->toArray();
            $tongji = new Tongji();
            $product = new Products();
            if(empty($cxdate)){
                $cxdate[] = ['date','=',date('Y-m-d')];
            }
            $totalClicks = $tongji->where($cxdate)->sum('clicks');
            foreach ($list as &$listItem) {
                $map1 = [];
                if($cxdate) {
                    $map1 = array_merge($map1, $cxdate);
                }
                $products = $product->field('id')->where(['cid' => $listItem['id']])->select()->toArray();
                $productIds = array_column($products, 'id');
                $map1[] = ['pid', 'in', $productIds];
                $listItem['clicks'] = $tongji::where($map1)->sum('clicks');
                $listItem['ratio'] = $totalClicks > 0 ? @round($listItem['clicks']/$totalClicks*100,2) : '0';
                $listItem['date'] = @substr($cxdate[0][2], 0, 10);
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
                'totalRow' => [
                    'clicks' => $totalClicks,
                ],
            ];
            return json($data);
        }
        return $this->fetch();
    }
    public function getpcate()
	{
		if ($this->request->isAjax()) {
		    $list = $this->model->field('id,title')->select();
            return json($list);
        }
        return $this->fetch();
	}
	
	public function show()
	{
	    if ($this->request->isAjax()) {
    	    $cid = input('id', 1);
    		$date = input('date', date('Y-m-d'));
    		$where[] = ['date', '=', $date];
    		$tongji = new Tongji();
            $product = new Products();
    		$products = $product->field('id')->where(['cid' => $cid])->select()->toArray();
    		$productIds = array_column($products, 'id');
    		$where[] = ['pid', 'in', $productIds];
    		$totalClicks = $tongji->where($where)->sum('clicks');
    		$list = $tongji::field('sum(clicks) as total_clicks,channelCode')->where($where)->group('channelCode')->select();
    		foreach ($list as &$listItem) {
    		    $listItem['ratio'] = $totalClicks > 0 ? @round($listItem['total_clicks']/$totalClicks*100,2) : '0';
    		    $listItem['date'] = $date;
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