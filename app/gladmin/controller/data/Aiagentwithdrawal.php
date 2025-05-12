<?php
namespace app\gladmin\controller\data;
use app\common\model\User;
use app\common\model\AiWithdrawalRecord as AiWithdrawalRecordModel;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use app\common\model\AiBalanceBill;
use app\common\model\AiUser;
use think\App;
use think\facade\Db;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="ai代理提款管理")
 */
class Aiagentwithdrawal extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new AiWithdrawalRecordModel();
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
            $list = $this->model->where($where)->order("create_time desc")->page($page, $limit)->select();
            $aiUser = new \app\common\model\AiUser();
            $rate = sysconfig('site', 'usdt_exchange_rate');
            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['rate'] = $rate;
                $list[$i]['usdt'] = round($list[$i]['amount'] / $rate, 2) . ' U';
                $list[$i]['amount'] = $list[$i]['amount'] ? $list[$i]['amount'] . ' 元' : '0 元';
                $list[$i]['coin_wallet_type'] = $aiUser->where(array('id' => $list[$i]['uid']))->value('coin_wallet_type') ?: '';
                $list[$i]['finish_time'] = $list[$i]['finish_time'] ? date('Y-m-d H:i:s', $list[$i]['finish_time']) : '';
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
     * @NodeAnotation(title="二维码")
     */
    public function qrcode($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        $this->assign('row', $row);
        $rate = sysconfig('site', 'usdt_exchange_rate');
        $usdt = round($row['amount'] / $rate, 2);
        $aiUser = new \app\common\model\AiUser();
        $coin_wallet_type = $aiUser->where(array('id' => $row['uid']))->value('coin_wallet_type') ?: '';
        $this->assign('coin_wallet_type', $coin_wallet_type);
        $this->assign('usdt', $usdt);
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
            if ($row['status'] != 0) {
                $this->error('该提现申请已操作过');
            }
            $userData = AiUser::where(["id" => $row["uid"]])->find();
            try {


                if ($post['status'] == 1) {
                    $post['finish_time'] = time();
                }
             
                Db::startTrans();
                if ($post["status"] == 2) {
                    //dh_ai_balance_bill 表增加记录
                     AiBalanceBill::createBill($userData,$row["amount"],2,1);
                    //dh_ai_user表增加余额
                    AiUser::where(["id"=>$row["uid"]])->inc('balance',$row["amount"])->update();
                }

                //dh_ai_withdrawal_record表更改状态
                $save = $row->save($post);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->error('保存失败'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }

        $this->assign('row', $row);
        $rate = sysconfig('site', 'usdt_exchange_rate');
        $usdt = round($row['amount'] / $rate, 2);
        $aiUser = new \app\common\model\AiUser();
        $coin_wallet_type = $aiUser->where(array('id' => $row['uid']))->value('coin_wallet_type') ?: '';
        $this->assign('coin_wallet_type', $coin_wallet_type);
        $this->assign('usdt', $usdt);
        return $this->fetch();
    }

}