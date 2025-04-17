<?php
namespace app\api\controller;

use app\BaseController;
use think\facade\Db;

class Createhtml extends BaseController
{
    public function index()
    {
        for($i=1;$i<41;$i++)
        {
            $content = file_get_contents("http://localhost/?channelCode={$i}");
            //if(!file_exists($i)){mkdir($i);}
            file_put_contents("./{$i}.html",$content);
        }
        echo '生成完成!';
    }
}