<?php
namespace app\gladmin\controller\data;
use app\common\model\AiOrder as AiOrderModel;
use app\common\model\AiProductClickRecord;
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
class Aichannelreport extends AdminController
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
            list($page, $limit, $where) = $this->buildTableParames4();
            if(empty($where) || $where[0][0] == 'channelCode') {
                $where[] = ['date','=',date('Y-m-d')];
            }
            $searchDate = null;
            foreach ($where as $k => $v) {
                if($v[0] == 'date' &&  $v[1] == '>=') {
                    $map1[] = ['create_time','>=',strtotime($v[2].' 00:00:00')];
                    $searchDate = $v[2];
                }elseif ($v[0] == 'date' &&  $v[1] == '='){
                    $map1[] = ['create_time','>=',strtotime($v[2].' 00:00:00')];
                    $map1[] = ['create_time','<=',strtotime($v[2].' 23:59:59')];
                    $searchDate = $v[2];
                }
                elseif ($v[0] == 'date' &&  $v[1] == '<='){
                    $map1[] = ['create_time','<=',strtotime($v[2].' 23:59:59')];
                    $searchDate = $v[2];
                }
                elseif ($v[0] == 'channelCode') {
                    $map1[] = ['channelCode','LIKE',$v[2]];
                }
            }
            $clicklist = AiProductClickRecord::field('count(id) as clicks,channelCode')->where($map1)->group('channelCode')->select()->toArray();
            $registerlist = AiUserModel::field('count(id) as register_user,channelCode')->where($map1)->group('channelCode')->select()->toArray();
            $chargecountlist = AiOrderModel::field('COUNT(DISTINCT uid) AS user_charge_count, SUM(price) AS total_charge_amount,channelCode')->where($map1)->group('channelCode')->select()->toArray();
            $clickcountlist = AiProductClickRecord::field('COUNT(DISTINCT uid) as user_click_count,channelCode')->where($map1)->group('channelCode')->select()->toArray();
            $all_data = [$clicklist, $registerlist, $chargecountlist,$clickcountlist];
            $merged_data = [];
            foreach (array_merge(...$all_data) as $data) {
                $date = $data['channelCode'];
                $merged_data[$date] = array_merge(
                    $merged_data[$date] ?? ['clicks' => 0, 'register_user' => 0, 'user_charge_count' => 0, 'user_click_count' => 0, 'date' => $searchDate],
                    $data
                );
            }
            $merged_data = array_values($merged_data);
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
     * @NodeAnotation(title="修改密码")
     */
    public function changepw($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            if($post['passwd']!=$post['repasswd']){
                $this->error('两次密码不一致');
            }
            $post['plain_passwd'] = $post['passwd'];
            $post['passwd'] = md5($post['passwd']);
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