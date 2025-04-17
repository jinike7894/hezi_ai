<?php
namespace app\gladmin\controller\data;

use app\common\model\Tjcode;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Session;

/**
 * Class Url
 * @package app\gladmin\controller\url
 * @ControllerAnnotation(title="CDN刷新")
 */
class Tjdaima extends AdminController
{

    use \app\gladmin\traits\Curd;

    protected $relationSearch = true;
    protected $sort = [
        'id'   => 'asc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Tjcode();
    }
	public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            for($i=0;$i<count($list);$i++)
            {
                $tj = '';
                if(stripos($list[$i]['tjcode'],"hm.baidu.com")!==false)
                {
                    $tj .="|百度统计";
                }
                if(stripos($list[$i]['tjcode'],"sdk.51.la")!==false)
                {
                    $tj .="|51啦统计";
                }
                if(stripos($list[$i]['tjcode'],"www.googletagmanager.com")!==false)
                {
                    $tj .="|GOOGLE统计";
                }
                $list[$i]['tjcode'] = $tj;
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
}