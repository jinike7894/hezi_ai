<?php
namespace app\gladmin\controller\data;

use app\common\model\AiActivityRecord as ActivityRecord;
use app\common\model\Pgathers;
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
            dd("ok");
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