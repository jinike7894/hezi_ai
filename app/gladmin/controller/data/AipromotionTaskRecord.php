<?php
namespace app\gladmin\controller\data;
use app\common\model\AiUser;
use app\common\model\AiPromotionTaskRecord as AiPromotionTaskRecordModel;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

class AipromotionTaskRecord extends AdminController
{
     use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new AiPromotionTaskRecordModel();
    }
    /**
     * @NodeAnotation(title="审核佣金任务记录")
     */
      public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model->where($where)->whereIn("status", [0,1, 2])->count();
            $list = $this->model->where($where)->whereIn("status", [0,1, 2])->page($page, $limit)->order('status asc,id desc')->select();
            $aiUser = new \app\common\model\AiUser();
            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['uid'] = $aiUser->where(array('id' => $list[$i]['uid']))->value('username') ?: '';
                if($list[$i]['apply_time']!=0){
                    $list[$i]['apply_time'] =date("Y-m-d H:i:s", $list[$i]['apply_time']); ;
                }
               
            }
            $data = [
                'code' => 0,
                'msg' => '',
                'count' => $count,
                'data' => $list,
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
            if ($row['status'] == 2 || $row['status'] == 3) {
                $this->error('该申请已操作过');
            }
            $userData = AiUser::where(["id" => $row["uid"]])->find();
            $acticityRecord = $this->model->find($id);



            if($post['status']==1){
                $res = AiPromotionTaskRecordModel::taskFinishNotify($acticityRecord['activity_order_num']);
            }
            if (!$res) {
                $this->error('操作失败');
            }
            $this->success('操作成功');
        }

        $this->assign('row', $row);
        return $this->fetch();
    }
}