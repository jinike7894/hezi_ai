<?php
namespace app\gladmin\controller\data;
use app\common\model\User;
use app\common\model\Channelcode;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="渠道管理")
 */
class Channelcodes extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Channelcode();
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
            $list = $this->model->where($where)->page($page, $limit)->select();
            for($i=0;$i<count($list);$i++)
            {
                $ulist = User::where(array('id'=>$list[$i]['uid']))->find();
                if(empty($ulist))
                {
                    $list[$i]['title'] = '未分配';
                }else{
                    $list[$i]['title'] = $ulist['username'] . '(' . $ulist['nickname'] . ')';
                }
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
     * @NodeAnotation(title="新增")
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                // $post['uid'] = ($post['uid'] ?? 0) ?: 0;
                $userfind = new User(); 
                if($userfind->where(["username"=>trim($post['username'])])->find()){
                    echo  json_encode(["code"=>0,"msg"=>"用户已存在"]);
                    return;
                }
                //新增用户
                $userpost['username'] = trim($post['username']);
                $userpost['password'] = password(123456);
                // 创建并保存用户记录 
                $user = new User($userpost); 
                $user->save();
               
                $post["uid"]=$user->id;
                $save = $this->model->save($post);
                $user->datasend();
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
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
            if(empty($post['password']))
            {
                unset($post['password']);
            }
            try {
                $post['uid'] = ($post['uid'] ?? 0) ?: 0;
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