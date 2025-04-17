<?php
namespace app\api\controller;

use app\BaseController;

class Delete extends BaseController
{
	public function initialize()
	{
		$this->Tongji = new \app\common\model\Tongji();
		$this->Tongjiuid = new \app\common\model\Tongjiuid();
	}
    public function delsday()
    {
        $this->Tongji->deletedata();
        $this->Tongjiuid->deletedata();
    }
}