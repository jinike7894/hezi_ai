<?php
namespace app\gladmin\controller\data;
use app\common\model\User;
use app\common\model\AiVideo as aiVideoModel;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\mall
 * @ControllerAnnotation(title="ai视频管理")
 */
class Aivideo extends AdminController
{

    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new aiVideoModel();
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
            $rule = [];
            $this->validate($post, $rule);
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
            $rule = [];
            $this->validate($post, $rule);
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
     * @NodeAnotation(title="批量修改")
     */
    public function batchEdit($id)
    {

        if ($this->request->isPost()) {
            $data = [];

            $isvip = input('post.isvip');
            if (!empty($isvip) && $isvip == 1 ) {
                $data['isvip'] = 1;
                $data['points'] = rand(5,20);
            }else{
                $data['isvip'] = 0;
                $data['points'] = 0;
            }

            $save = '';
            if ($data) {
                try {

                    $save = $this->model->whereIn('id', $id)->update($data);
                } catch (\Exception $e) {
                    $this->error('保存失败:'.$e->getMessage());
                }
            }

            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }

}