<?php
namespace app\gladmin\controller\data;
use app\common\model\AiOrder as AiOrderModel;
use app\common\model\AiPointsBill as AiPointsBillModel;
use app\common\model\User;
use app\common\model\AiUser as  AiUserModel;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="ai用户管理")
 */
class Aireport extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new AiUserModel();
    }
    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            $aiorderlist = AiOrderModel::field('DATE(FROM_UNIXTIME(create_time)) AS date,
    COUNT(DISTINCT CASE WHEN is_first = 1 THEN uid END) AS first_charge_count,
    COUNT(DISTINCT CASE WHEN is_first = 0 THEN uid END) AS repeat_charge_count,
    COUNT(DISTINCT uid) AS total_charge_count,
    SUM(CASE WHEN is_first = 1 THEN price ELSE 0 END) AS first_charge_amount,
    SUM(CASE WHEN is_first = 0 THEN price ELSE 0 END) AS repeat_charge_amount,
    SUM(price) AS total_charge_amount')->group('DATE(FROM_UNIXTIME(create_time))')->order('date desc')->select()->toArray();
            $aiuserlist = AiUserModel::field('DATE(FROM_UNIXTIME(create_time)) AS date,
    COUNT(id) AS registered_users')->group('DATE(FROM_UNIXTIME(create_time))')->order('date desc')->select()->toArray();
            $aipointslist = AiPointsBillModel::field('DATE(FROM_UNIXTIME(create_time)) AS date,
    SUM(points) AS total_coin_consumed')->where([['points_type','=',0]])->group('DATE(FROM_UNIXTIME(create_time))')->order('date desc')->select()->toArray();
            $all_data = [$aiorderlist, $aiuserlist, $aipointslist];
            $merged_data = [];
            foreach (array_merge(...$all_data) as $data) {
                $date = $data['date'];
                $merged_data[$date] = array_merge(
                    $merged_data[$date] ?? ['date' => $date, 'registered_users' => 0, 'first_charge_count' => 0, 'repeat_charge_count' => 0, 'total_charge_count' => 0, 'first_charge_amount' => 0, 'repeat_charge_amount' => 0, 'total_charge_amount' => 0, 'total_coin_consumed' => 0],
                    $data
                );
            }
            $merged_data = array_values($merged_data);
            usort($merged_data, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => count($merged_data),
                'data'  => $merged_data,
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
            $post['balance'] = $post['newbalance'];
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

}