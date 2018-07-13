<?php

namespace backend\controllers;

use backend\library\BaseController as Controller;
use backend\library\service\OrderService;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d',strtotime("-1 day"));
        $today_hour_income = OrderService::getHourIncomeByDate($today);
        $yesterday_hour_income = OrderService::getHourIncomeByDate($yesterday);
        return $this->render('index',
            [
                'today_hour_income' => $today_hour_income,
                'yesterday_hour_income' => $yesterday_hour_income,
            ]
        );
    }

}
