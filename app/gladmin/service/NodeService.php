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

namespace app\gladmin\service;


use EasyAdmin\auth\Node;

class NodeService
{

    /**
     * 获取节点服务
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function getNodelist()
    {
        $basePath = base_path() . 'gladmin' . DIRECTORY_SEPARATOR . 'controller';
        $baseNamespace = "app\gladmin\controller";

        $nodeList  = (new Node($basePath, $baseNamespace))->getNodelist();

        return $nodeList;
    }
}