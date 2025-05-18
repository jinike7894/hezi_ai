<?php
namespace app\gladmin\controller\data;

use app\common\model\User;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="用户管理")
 */
class Users extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new User();
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
            $rule = [
                'username'  =>  'require',
                'password'  =>  'require|min:4',
                'rpassword'  =>  'require|min:4|confirm:password',
            ];
            $message  =   [
                'username.require' => '用户名必填项',
                'password.require'     => '密码必填项',
                'password.min'   => '密码长度不能小于四位',
                'rpassword.require'  => '验证密码为必填项',
                'rpassword.min'   => '验证密长度不能小于四位',
                'rpassword.confirm'        => '两次输入的密码不符',    
            ];
            $this->validate($post, $rule);
            try {
                $post['username'] = trim($post['username']);
                $post['password'] = password($post['password']);
                $save = $this->model->save($post);
                $this->model->datasend();
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="批量新增")
     */
    public function batchAdd()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [
                'username'  =>  'require|min:4',
            ];
            $message  =   [
                'username.require' => '用户名必填项',
                'username.min'   => '用户名长度不能小于四位',
            ];
            $this->validate($post, $rule);
            try {
                $usernames = preg_split('/\r?\n/', $post['username']);
                $datas = [];
                foreach ($usernames as $username) {
                    $username = trim($username);
                    if ($username) {
                        $data['username'] = $username;
                        $data['nickname'] = $username;
                        $data['password'] = password($username);
                        $datas[] = $data;
                        
                    }
                }
                $save = false;
                if ($datas) {
                    $save = $this->model->saveAll($datas);
                    $this->model->datasend();
                }
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
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
            $rule = [
                'rpassword'  =>  'confirm:password',
            ];
            $message  =   [
                'rpassword'        => '两次输入的密码不符',    
            ];
            $this->validate($post, $rule);
            if(empty($post['password']))
            {
                unset($post['password']);
            }else{
                $post['password'] = password($post['password']);
            }
            try {
                $save = $row->save($post);
                $this->model->datasend();
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="删除")
     */
    public function delete($id)
    {
        $this->checkPostRequest();
        $row = $this->model->whereIn('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            $save = $row->delete();
            $this->model->datasend();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
    }
    
    public function getuser()
    {
        if ($this->request->isAjax()) {
		    $list = $this->model->field('id,username,nickname')->select();
		    for($i=0;$i<count($list);$i++)
		    {
		        $list[$i]['title'] = $list[$i]['username'];
		    }
            return json($list);
        }
        return $this->fetch();
    }

}