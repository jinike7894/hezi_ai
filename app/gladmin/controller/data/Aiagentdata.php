<?php
namespace app\gladmin\controller\data;
use app\common\model\User;
use app\common\model\AiUser as AiUserModel;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="ai代理数据管理")
 */
class Aiagentdata extends AdminController
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
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();

            if(empty($where)){
                $where = [
                    [
                        "create_time",
                        ">=",
                        time()
                    ],
                    [
                        "create_time",
                        "<=",
                        time()
                    ]
                ];
            }


            $filteredConditions = array_filter($where, function($condition) {
                return $condition[0] !== "create_time";
            });
            $updatedConditions = array_map(function($condition) {
                $condition[0] = 'u1.' . $condition[0]; // 添加 u1.
                return $condition;
            }, $filteredConditions);

            $map1 = array_filter($where, function($condition) {
                return $condition[0] === "create_time";
            });
            $updatedMap = [];
            $date2 = '';
            foreach ($map1 as $condition) {
                if ($condition[0] === "create_time" && $condition[1] == ">=") {
                    // 获取日期
                    $date = date('Y-m-d', $condition[2]);
                    $date2 = $date;
                    $startTimestamp = strtotime($date . ' 00:00:00'); // 凌晨时间
                    $endTimestamp = strtotime($date . ' 23:59:59'); // 23:59:59时间

                    // 更新条件
                    $updatedMap[] = ["create_time", ">=", $startTimestamp];
                    $updatedMap[] = ["create_time", "<=", $endTimestamp];
                }
            }

            $list = $this->model::alias('u1')->field('u1.id AS agent_id, GROUP_CONCAT(u2.id) AS ids,u1.username,u1.channelCode,u1.create_time')->leftJoin('ai_user u2', 'u2.pid = u1.id')->group('u1.id')->where(['u1.pid' => 0])->where($updatedConditions)->select()->toArray();
            $newList = [];
            foreach ($list as $item) {
                if(!empty($item['ids'])){
                    $newList[] = $item;
                }
            }
            $aiOrder = new \app\common\model\AiOrder();
            $aiProClickRecord = new \app\common\model\AiProductClickRecord();
            for($i=0;$i<count($newList);$i++){
                $newList[$i]['sub'] = $this->model::where(['pid' => $newList[$i]['agent_id']])->where($updatedMap)->count('id');
                $aiOrderRecharge = $aiOrder->field('COUNT(DISTINCT uid) AS recharge_user, SUM(price) AS recharge_amount')->wherein('uid',$newList[$i]['ids'])->where(['pay_status' => '1'])->where($updatedMap)->select()->toArray();
                $newList[$i]['recharge_user'] = $aiOrderRecharge[0]['recharge_user'] ?? 0;
                $newList[$i]['recharge_amount'] = $aiOrderRecharge[0]['recharge_amount'] ?? 0;

                $aiProClick = $aiProClickRecord->field('COUNT(DISTINCT uid) AS user_click_count')->wherein('uid',$newList[$i]['ids'])->where($updatedMap)->select()->toArray();
                $newList[$i]['user_click_count'] = $aiProClick[0]['user_click_count'] ?? 0;
                $newList[$i]['click'] = $aiProClickRecord->wherein('uid',$newList[$i]['ids'])->where($updatedMap)->count('id') ?: 0;
                $newList[$i]['date'] = $date2;
            }
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => count($newList),
                'data'  => $newList,
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