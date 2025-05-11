<?php
namespace app\gladmin\controller\data;
use app\common\model\User;
use app\common\model\AiOrder as  AiOrderModel;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="ai订单管理")
 */
class Aiorder extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new AiOrderModel();
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
            $list = $this->model->where($where)->page($page, $limit)->order('id desc')->select();
            $aiUser = new \app\common\model\AiUser();
            $aiPayment = new \app\common\model\AiPayment();
            for($i=0;$i<count($list);$i++){
                $list[$i]['username'] = $aiUser->where(array('id'=>$list[$i]['uid']))->value('username') ?: '';
                $list[$i]['pay_type_name'] = $aiPayment->where(array('id'=>$list[$i]['pay_type_id']))->value('name') ?: '';
                $list[$i]['rate'] = $aiPayment->where(array('id'=>$list[$i]['pay_type_id']))->value('rate') ?: '';
                $list[$i]['receipt'] = $list[$i]['price'] - $list[$i]['price']*($list[$i]['rate']/100);
                $list[$i]['pay_time'] = $list[$i]['pay_time'] ?date('Y-m-d H:i:s',$list[$i]['pay_time']) : '';
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
     * @NodeAnotation(title="冲正")
     */
    public function correct($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            if($row['pay_status'] == 1){
                $this->error('不能操作已支付的订单');
            }
            try {
                $save = AiOrderModel::notify($row['order_num']);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }


    /**
     * @NodeAnotation(title="新增")
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $hours = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
        $this->assign('hours', $hours);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $hours = ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];
        $this->assign('row', $row);
        $this->assign('hours', $hours);
        return $this->fetch();
    }

}