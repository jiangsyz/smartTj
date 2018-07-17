<?php

namespace backend\controllers;

use backend\library\BaseController as Controller;
use backend\library\service\OrderService;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 day"));
        # 今日每销售收入
        $today_hour_income = OrderService::getHourIncomeByDate($today);
        # 昨日每销售收入
        $yesterday_hour_income = OrderService::getHourIncomeByDate($yesterday);
        # 获取订单总数
        $order_count = OrderService::getOrderCountBy($today);
        # 获取付款人数
        $member_count = OrderService::getMemberCountByDate($today);
        # 付款金额
        $pay_count = OrderService::getPayTotalByDate($today);
        # 客单价
        $single_price = !empty($pay_count) && !empty($member_count) ? round($pay_count/$member_count,2) : 0;

        $start_date = $this->getGet('start_date',date('Y-m-d'));
        $end_date = $this->getGet('end_date',date('Y-m-d'));
        if (strtotime($end_date) > time()) {
            $end_date = date('Y-m-d');
        }
        # 每日收入
        $day_income = OrderService::getDateIncomeByRange($start_date,$end_date);

        return $this->render('index', compact(
                'today_hour_income',
                'yesterday_hour_income',
                'order_count',
                'member_count',
                'pay_count',
                'single_price',
                'day_income'
            )
        );
    }

}
