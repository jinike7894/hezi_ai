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

namespace app\common\command;


use app\admin\model\MallComics;
use EasyAdmin\console\CliEcho;
use EasyAdmin\tool\CommonTool;
use EasyAdmin\upload\driver\alioss\Oss;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Manhua extends Command
{

    protected function configure()
    {
        $this->setName('Manhua')
            ->setDescription('将静态资源上传到oss上');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln("========正在上传静态资源到OSS上：========" . date('Y-m-d H:i:s'));
        ini_set('memory_limit', '-1');

            $dir = root_path() . 'public' . DIRECTORY_SEPARATOR . 'upload';
            $list = CommonTool::readDirAllFiles($dir);

             foreach ($list as $key => $val){
                 list($objectName, $filePath) = [$key, $val];
                 if (strpos($objectName, '_file') !== false) {
                     continue;
                 }
                 if (strpos($objectName, '.js') !== false) {
                     continue;
                 }
                 var_dump($objectName);
                 $pdata = file_get_contents($filePath);
                 $parr = str_split($pdata);
                 $decArr = [];
                 foreach ($parr as $value) {
                     $decArr[] = hexdec(bin2hex($value)) ^ 136;
                 }
                 $path_info = pathinfo($val);
                 $file_extension = $path_info['extension'];
                 $aa = str_replace('.' . $file_extension, '_file.' . $file_extension, $val);
                 $bb = str_replace('.' . $file_extension, '_file.' . 'js', $val);
                 file_put_contents($aa, pack('C*', ...$decArr));
                 file_put_contents($bb, pack('C*', ...$decArr));
                 var_dump('finish');
             }
        exit;
    }

}