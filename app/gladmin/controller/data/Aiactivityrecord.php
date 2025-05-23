<?php
namespace app\gladmin\controller\data;

use app\common\model\AiActivityRecord as ActivityRecord;
use app\common\model\AiUser;
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
class Aiactivityrecord extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new ActivityRecord();
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
            $count = $this->model->where($where)->count();
            $list = $this->model->where($where)->where(["status"=>1])->page($page, $limit)->order('id desc')->select();
            $aiUser = new \app\common\model\AiUser();
            $aiPayment = new \app\common\model\AiPayment();
            for($i=0;$i<count($list);$i++){
                $list[$i]['uid'] = $aiUser->where(array('id'=>$list[$i]['uid']))->value('username') ?: '';
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
    public function edit($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
           
            $rule = [];
            $this->validate($post, $rule);
            if ($row['status'] == 2) {
                $this->error('该申请已操作过');
            }
            $userData = AiUser::where(["id" => $row["uid"]])->find();

                $acticityRecord=$this->model->find($id);
                
                $res=ActivityRecord::activityFinishNotify($acticityRecord['activity_order_num']);
            
                if (!$res) {
                    $this->error('操作失败');
                }   
                 $this->success('操作成功');
        }

        $this->assign('row', $row);
        return $this->fetch();
    }

}