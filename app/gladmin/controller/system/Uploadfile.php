<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------

namespace app\gladmin\controller\system;


use app\gladmin\model\SystemUploadfile;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="上传文件管理")
 * Class Uploadfile
 * @package app\gladmin\controller\system
 */
class Uploadfile extends AdminController
{

    use \app\gladmin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemUploadfile();
    }

}