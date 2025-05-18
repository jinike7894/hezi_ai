<?php
namespace app\gladmin\controller\data;

use app\common\model\Pgathers;
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
 * @ControllerAnnotation(title="产品汇总-查看")
 */
class Pgathershow extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Pgathers();
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
            $id=input('param.id');
            $count = Tongji::field('channelCode')->group('channelCode')->count();
            if(empty($cxdate)){
				$cxdate[] = ['date','=',date('Y-m-d')];
			}
            $list= $this->model->getlistshow($id,$cxdate);
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