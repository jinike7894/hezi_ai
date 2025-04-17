<?php

namespace app\gladmin\controller;


use app\gladmin\model\SystemAdmin;
use app\gladmin\model\SystemQuick;
use app\common\controller\AdminController;
use think\App;
use think\facade\Env;

class Index extends AdminController
{

    /**
     * 后台主页
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        return $this->fetch('', [
            'admin' => session('admin'),
        ]);
    }

    /**
     * 后台欢迎页
     * @return string
     * @throws \Exception
     */
    public function welcome()
    {
        $quicks = SystemQuick::field('id,title,icon,href')
            ->where(['status' => 1])
            ->order('sort', 'desc')
            ->limit(8)
            ->select();
        $tongji = new \app\common\model\Tongji();
        // 获取按钮产品  按钮分类ID 后台添加后为16,TODO 每个平台不一样
        $roducts = new \app\common\model\Products(); // 排除按钮商品的点击数
        $buttonPros = $roducts->where(['pid' => 16])->column('id');
        $todaytongjiMap = [
            ['date', '=', date('Y-m-d')],
            ['pid', 'not in', $buttonPros],
        ];
        $todayClicks = $tongji->where($todaytongjiMap)->sum('clicks');
        $ytongjiMap = [
            ['date', '=', date('Y-m-d', strtotime('-1 day'))],
            ['pid', 'not in', $buttonPros],
        ];
        $yesterdayClicks = $tongji->where($ytongjiMap)->sum('clicks');
        $seventongjiMap = [
            ['date', '>', date('Y-m-d', strtotime('-7 day'))],
            ['pid', 'not in', $buttonPros],
        ];
        $sevenDaysCLicks = $tongji->where($seventongjiMap)->sum('clicks');
        
        $qdTongji = new \app\common\model\Qdtongji();
        $todaySjNum = $qdTongji->where(['date' => date('Y-m-d')])->sum('sj_num');
        $yesterdaySjNum = $qdTongji->where(['date' => date('Y-m-d', strtotime('-1 day'))])->sum('sj_num');
        $sevendaysSjNum = $qdTongji->where([['date', '>', date('Y-m-d', strtotime('-7 day'))]])->sum('sj_num');
        

        $this->assign('todayClicks', $todayClicks);
        $this->assign('yesterdayClicks', $yesterdayClicks);
        $this->assign('sevenDaysCLicks', $sevenDaysCLicks);
        $this->assign('todaySjNum', $todaySjNum);
        $this->assign('yesterdaySjNum', $yesterdaySjNum);
        $this->assign('sevendaysSjNum', $sevendaysSjNum);
        $this->assign('quicks', $quicks);
        return $this->fetch();
    }

    /**
     * 修改管理员信息
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editAdmin()
    {
        $id = session('admin.id');
        $row = (new SystemAdmin())
            ->withoutField('password')
            ->find($id);
        empty($row) && $this->error('用户信息不存在');
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $this->isDemo && $this->error('演示环境下不允许修改');
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row
                    ->allowField(['head_img', 'password', 'phone', 'remark', 'update_time'])
                    ->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * 修改密码
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editPassword()
    {
        $id = session('admin.id');
        $row = (new SystemAdmin())
            ->withoutField('password')
            ->find($id);
        if (!$row) {
            $this->error('用户信息不存在');
        }
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $this->isDemo && $this->error('演示环境下不允许修改');
            $rule = [
                'password|登录密码'       => 'require',
                'password_again|确认密码' => 'require',
            ];
            $this->validate($post, $rule);
            if ($post['password'] != $post['password_again']) {
                $this->error('两次密码输入不一致');
            }

            try {
                $save = $row->save([
                    'password' => password($post['password']),
                ]);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            if ($save) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

}
