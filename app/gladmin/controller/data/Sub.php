<?php
namespace app\gladmin\controller\data;

use app\common\model\Subid;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="商城商品管理")
 */
class Sub extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Subid();
    }
	public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames4();
            if(empty($where))
            {
                $where[] = ['date', '=', date('Y-m-d')];
            }
            $count = $this->model->where($where)->cache(600)->count();
            $list = $this->model->where($where)->cache(600)->page($page, $limit)->order($this->sort)->select();
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