<?php

namespace app\gladmin\controller;


use app\common\model\AiOrder as AiOrderModel;
use app\common\model\AiUser as AiUserModel;
use app\common\model\AiBalanceBill as AiBalanceBillModel;
use app\common\model\AiUseRecord as AiUseRecordModel;
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

        $today_map[] = ['create_time','>=',strtotime(date('Y-m-d',time()).' 00:00:00')];
        $today_map[] = ['create_time','<=',strtotime(date('Y-m-d',time()).' 23:59:59')];

        $yesterday_map[] = ['create_time','>=',strtotime('yesterday midnight')];
        $yesterday_map[] = ['create_time','<=',strtotime('yesterday 23:59:59')];

        $month_map[] = ['create_time','>=',strtotime('first day of this month midnight')];
        $month_map[] = ['create_time','<=',strtotime('last day of this month 23:59:59')];

        $last_month_map[] = ['create_time','>=',strtotime('first day of last month midnight')];
        $last_month_map[] = ['create_time','<=',strtotime('last day of last month 23:59:59')];

        /**
         * 注册人数
         */
        $today_registeruser = AiUserModel::where($today_map)->count('id');
        $yesterday_registeruser = AiUserModel::where($yesterday_map)->count('id');
        $month_registeruser = AiUserModel::where($month_map)->count('id');
        $last_month_registeruser = AiUserModel::where($last_month_map)->count('id');
        /**
         * 注册人数END
         */

        /**
         * 充值人数、充值金额
         */
        $todayChargecountlist = AiOrderModel::field('COUNT(DISTINCT uid) AS user_charge_count, SUM(price) AS total_charge_amount')->where($today_map)->where(['pay_status' => '1'])->select()->toArray();
        $todayChargeUserCount = $todayChargecountlist[0]['user_charge_count'] ?? 0;
        $todayChargeAmount = $todayChargecountlist[0]['total_charge_amount'] ?? 0;


        $yesterdayChargecountlist = AiOrderModel::field('COUNT(DISTINCT uid) AS user_charge_count, SUM(price) AS total_charge_amount')->where($yesterday_map)->where(['pay_status' => '1'])->select()->toArray();
        $yesterdayChargeUserCount = $yesterdayChargecountlist[0]['user_charge_count'] ?? 0;
        $yesterdayChargeAmount = $yesterdayChargecountlist[0]['total_charge_amount'] ?? 0;


        $monthChargecountlist = AiOrderModel::field('COUNT(DISTINCT uid) AS user_charge_count, SUM(price) AS total_charge_amount')->where($month_map)->where(['pay_status' => '1'])->select()->toArray();
        $monthChargeUserCount = $monthChargecountlist[0]['user_charge_count'] ?? 0;
        $monthChargeAmount = $monthChargecountlist[0]['total_charge_amount'] ?? 0;


        $last_monthChargecountlist = AiOrderModel::field('COUNT(DISTINCT uid) AS user_charge_count, SUM(price) AS total_charge_amount')->where($last_month_map)->where(['pay_status' => '1'])->select()->toArray();
        $last_monthChargeUserCount = $last_monthChargecountlist['user_charge_count'] ?? 0;
        $last_monthChargeAmount = $last_monthChargecountlist['total_charge_amount'] ?? 0;

        /**
         * 充值人数、充值金额END
         */

        /**
         * 结算金额
         */
        $todayOrderSettlement = AiOrderModel::field('SUM(price - (price * (current_rate / 100))) AS total_settlement_amount')->where($today_map)->where(['pay_status' => 1])->select()->toArray();
        $today_total_settlement_amount = $todayOrderSettlement[0]['total_settlement_amount'] ?? 0;


        $yesterdayOrderSettlement = AiOrderModel::field('SUM(price - (price * (current_rate / 100))) AS total_settlement_amount')->where($yesterday_map)->where(['pay_status' => 1])->select()->toArray();
        $yesterday_total_settlement_amount = $yesterdayOrderSettlement[0]['total_settlement_amount'] ?? 0;


        $monthOrderSettlement = AiOrderModel::field('SUM(price - (price * (current_rate / 100))) AS total_settlement_amount')->where($month_map)->where(['pay_status' => 1])->select()->toArray();
        $month_total_settlement_amount = $monthOrderSettlement[0]['total_settlement_amount'] ?? 0;


        $last_monthOrderSettlement = AiOrderModel::field('SUM(price - (price * (current_rate / 100))) AS total_settlement_amount')->where($last_month_map)->where(['pay_status' => 1])->select()->toArray();
        $last_month_total_settlement_amount = $last_monthOrderSettlement[0]['total_settlement_amount'] ?? 0;
        /**
         * 结算金额END
         */


        /**
         * 代理收益
         */
        $today_agent_income = AiBalanceBillModel::where($today_map)->where(['bill_type' => '0'])->sum('amount');
        $yesterday_agent_income = AiBalanceBillModel::where($yesterday_map)->where(['bill_type' => '0'])->sum('amount');
        $month_agent_income = AiBalanceBillModel::where($month_map)->where(['bill_type' => '0'])->sum('amount');
        $last_month_agent_income = AiBalanceBillModel::where($last_month_map)->where(['bill_type' => '0'])->sum('amount');
        /**
         * 代理收益END
         */


        /**
         * 点数消耗
         */
        $today_use_count = AiUseRecordModel::field('ai_type, COUNT(id) AS usage_count')->where($today_map)->group('ai_type')->select()->toArray();
        $coefficients = [30, 10, 10, 10];
        $todayTotal_sum = array_reduce($today_use_count, function($sum, $item) use ($coefficients) {
            return $sum + $item["usage_count"] * $coefficients[$item["ai_type"]];
        }, 0);
        $today_goldUsed = $todayTotal_sum;


        $yesterday_use_count = AiUseRecordModel::field('ai_type, COUNT(id) AS usage_count')->where($yesterday_map)->group('ai_type')->select()->toArray();
        $coefficients = [30, 10, 10, 10];
        $yesterdayTotal_sum = array_reduce($yesterday_use_count, function($sum, $item) use ($coefficients) {
            return $sum + $item["usage_count"] * $coefficients[$item["ai_type"]];
        }, 0);
        $yesterday_goldUsed = $yesterdayTotal_sum;


        $month_use_count = AiUseRecordModel::field('ai_type, COUNT(id) AS usage_count')->where($month_map)->group('ai_type')->select()->toArray();
        $coefficients = [30, 10, 10, 10];
        $monthTotal_sum = array_reduce($month_use_count, function($sum, $item) use ($coefficients) {
            return $sum + $item["usage_count"] * $coefficients[$item["ai_type"]];
        }, 0);
        $month_goldUsed = $monthTotal_sum;


        $last_month_use_count = AiUseRecordModel::field('ai_type, COUNT(id) AS usage_count')->where($last_month_map)->group('ai_type')->select()->toArray();
        $coefficients = [30, 10, 10, 10];
        $last_monthTotal_sum = array_reduce($last_month_use_count, function($sum, $item) use ($coefficients) {
            return $sum + $item["usage_count"] * $coefficients[$item["ai_type"]];
        }, 0);
        $last_month_goldUsed = $last_monthTotal_sum;
        /**
         * 金币消耗END
         */

        /**
         * 消耗支出
         */
        $costRate = sysconfig('site', 'ai_points_to_rmb_rate');
        $today_cost = $today_goldUsed / $costRate;
        $yesterday_cost = $yesterday_goldUsed / $costRate;
        $month_cost = $month_goldUsed / $costRate;
        $last_month_cost = $last_month_goldUsed / $costRate;

        /**
         * 消耗支出END
         */


        /**
         * 平台收益
         */
        $today_platformIncome = $today_total_settlement_amount - $today_cost - $today_agent_income;
        $yesterday_platformIncome = $yesterday_total_settlement_amount - $yesterday_cost - $yesterday_agent_income;
        $month_platformIncome = $month_total_settlement_amount - $month_cost - $month_agent_income;
        $last_month_platformIncome = $last_month_total_settlement_amount - $last_month_cost - $last_month_agent_income;

        /**
         * 平台收益END
         */



        $this->assign('today_registeruser', $today_registeruser);
        $this->assign('yesterday_registeruser', $yesterday_registeruser);
        $this->assign('month_registeruser', $month_registeruser);
        $this->assign('last_month_registeruser', $last_month_registeruser);


        $this->assign('todayChargeUserCount', $todayChargeUserCount);
        $this->assign('yesterdayChargeUserCount', $yesterdayChargeUserCount);
        $this->assign('monthChargeUserCount', $monthChargeUserCount);
        $this->assign('last_monthChargeUserCount', $last_monthChargeUserCount);


        $this->assign('todayChargeAmount', $todayChargeAmount);
        $this->assign('yesterdayChargeAmount', $yesterdayChargeAmount);
        $this->assign('monthChargeAmount', $monthChargeAmount);
        $this->assign('last_monthChargeAmount', $last_monthChargeAmount);


        $this->assign('today_total_settlement_amount', $today_total_settlement_amount);
        $this->assign('yesterday_total_settlement_amount', $yesterday_total_settlement_amount);
        $this->assign('month_total_settlement_amount', $month_total_settlement_amount);
        $this->assign('last_month_total_settlement_amount', $last_month_total_settlement_amount);


        $this->assign('today_agent_income', $today_agent_income);
        $this->assign('yesterday_agent_income', $yesterday_agent_income);
        $this->assign('month_agent_income', $month_agent_income);
        $this->assign('last_month_agent_income', $last_month_agent_income);


        $this->assign('today_platformIncome', $today_platformIncome);
        $this->assign('yesterday_platformIncome', $yesterday_platformIncome);
        $this->assign('month_platformIncome', $month_platformIncome);
        $this->assign('last_month_platformIncome', $last_month_platformIncome);


        $this->assign('today_goldUsed', $today_goldUsed);
        $this->assign('yesterday_goldUsed', $yesterday_goldUsed);
        $this->assign('month_goldUsed', $month_goldUsed);
        $this->assign('last_month_goldUsed', $last_month_goldUsed);


        $this->assign('today_cost', $today_cost);
        $this->assign('yesterday_cost', $yesterday_cost);
        $this->assign('month_cost', $month_cost);
        $this->assign('last_month_cost', $last_month_cost);






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
