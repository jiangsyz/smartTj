<?php

namespace backend\controllers;

use backend\library\BaseController as Controller;
use backend\library\service\LogService;
use backend\library\service\OrderService;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $chart_type = $this->getGet('chart_type','line');
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 day"));
        # 获取pv
      //  $pv = LogService::getPvByDate($today);
        $pv = 0;
        # 获取uv
        $uv = LogService::getTodayUv();
        # 今日每销售收入 昨日每销售收入
        $fun = ($chart_type == 'line') ? 'getHourIncomeByDate' : 'getHourIncomeByDateV2';
        $today_hour_income = call_user_func_array(array('backend\library\service\OrderService', $fun), array($today));
        $yesterday_hour_income = call_user_func_array(array('backend\library\service\OrderService', $fun), array($yesterday));
        # 获取订单总数
        $order_count = OrderService::getOrderCountBy($today);
        # 获取付款人数
        $member_count = OrderService::getMemberCountByDate($today);
        # 付款金额
        $pay_count_return = OrderService::getPayTotalByDate($today);
        $pay_count = sprintf("%.2f",$pay_count_return);
        # 客单价
        $single_price = !empty($pay_count) && !empty($member_count) ? sprintf("%.2f",round($pay_count/$member_count,2)) : 0;
        # 转化率
        $tran_rate =  !empty($member_count) && !empty($uv) ? sprintf("%.2f",$member_count/$uv * 100).'%' : '0';
        $start_date = $this->getGet('start_date',date('Y-m-d',strtotime("-7 day")));
        $end_date = $this->getGet('end_date',date('Y-m-d'));
        if (strtotime($end_date) > time()) {
            $end_date = date('Y-m-d');
        }
        # 每日收入
        $day_income = OrderService::getDateIncomeByRange($start_date,$end_date);

        return $this->render('index', compact(
                'chart_type',
                'tran_rate',
                'uv',
                'pv',
                'today_hour_income',
                'yesterday_hour_income',
                'order_count',
                'member_count',
                'pay_count',
                'single_price',
                'day_income',
                'start_date',
                'end_date'
            )
        );
    }

}
