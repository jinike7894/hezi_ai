<?php
namespace app\gladmin\controller\data;

use app\common\model\AppConfig;
use app\gladmin\service\TriggerService;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * Class Goods
 * @package app\gladmin\controller\data
 * @ControllerAnnotation(title="软件配置")
 */
class Config extends AdminController
{

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new AppConfig();
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="保存")
     */
    public function save()
    {
        $this->checkPostRequest();
        $post = $this->request->post();
        try {
            foreach ($post as $key => $val) {
                $this->model
                    ->where('name', $key)
                    ->update([
                        'value' => $val,
                    ]);
            }
            TriggerService::updateAppconfig();
        } catch (\Exception $e) {
            $this->error('保存失败');
        }
        $this->success('保存成功');
    }

}