<?php
namespace backend\library\service;

use app\models\OrderBuyingRecord;
use app\models\OrderRecord;
use yii\helpers\ArrayHelper;

class OrderService extends Service
{
    /**
     * 获取实收
     *
     * @param $date
     * @param int $type
     * @return int
     */
    public static function getTotalPriceByDate($date, $type = 2)
    {
        try {
            $start_time = strtotime($date . ' 00:00:00');
            if (empty($start_time)) {
                throw new \Exception('date error');
            }
            $end_time = $start_time + 86400;
            $tmp = OrderRecord::find()->select('id,finalPrice')->where([
                'payStatus' => OrderRecord::PAY_STATUS_OK,
            ])
                ->andWhere(['cancelStatus' => OrderRecord::CANCEL_STATUS_NON])
                ->andWhere(['closeStatus' => OrderRecord::CLOSE_STATUS_NON])
                ->andWhere(['>=', 'createTime', $start_time])
                ->andWhere(['<', 'createTime', $end_time])
                ->asArray()
                ->all();
            if (empty($tmp)) {
                throw new \Exception('pay_order is empty');
            }
            $final_price_data_tmp = $pay_order_ids = [];
            foreach ($tmp as $v) {
                $pay_order_ids[] = $v['id'];
                $final_price_data_tmp[$v['id']] = $v['finalPrice'];
            }

            $tmp = OrderRecord::find()->select('id,parentId')->where(['parentId' => $pay_order_ids])->asArray()->all();
            foreach ($tmp as $v) {
                if (!empty($final_price_data_tmp[$v['parentId']])) {
                    $sub_order_ids[] = $v['id'];
                    # 子订单号和主订单的实付绑定
                    $final_price_data[$v['id']] = $final_price_data_tmp[$v['parentId']];
                }
            }

            if (empty($sub_order_ids)) {
                throw new \Exception('sub_pay_order is empty');
            }
            $tmpIds = OrderBuyingRecord::find()->select('orderId')->where(['orderId' => $sub_order_ids])->andWhere(['sourceType' => $type])->groupBy('orderId')->column();
            $return = 0;
            foreach ($tmpIds as $v) {
                if (!empty($final_price_data[$v])) {
                    $return += $final_price_data[$v];
                }
            }

            return $return;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 获取某日付款订单总数
     *
     * @param $date
     * @return int|string
     * @throws \Exception
     */
    public static function getOrderCountBy($date)
    {
        $start_time = strtotime($date . ' 00:00:00');
        if (empty($start_time)) {
            throw new \Exception('date error');
        }
        $end_time = $start_time + 86400;
        $count = OrderRecord::find()->where([
            'payStatus' => OrderRecord::PAY_STATUS_OK,
        ])
            ->andWhere(['cancelStatus' => OrderRecord::CANCEL_STATUS_NON])
            ->andWhere(['closeStatus' => OrderRecord::CLOSE_STATUS_NON])
            ->andWhere(['>=', 'createTime', $start_time])
            ->andWhere(['<', 'createTime', $end_time])
            ->count();
        return $count;
    }

    /**
     * 获取付款人数
     *
     * @param $date
     * @return int|string
     * @throws \Exception
     */
    public static function getMemberCountByDate($date)
    {
        $start_time = strtotime($date . ' 00:00:00');
        if (empty($start_time)) {
            throw new \Exception('date error');
        }
        $end_time = $start_time + 86400;
        $count = OrderRecord::find()->select('memberId')->distinct()->where([
            'payStatus' => OrderRecord::PAY_STATUS_OK,
        ])
            ->andWhere(['cancelStatus' => OrderRecord::CANCEL_STATUS_NON])
            ->andWhere(['closeStatus' => OrderRecord::CLOSE_STATUS_NON])
            ->andWhere(['>=', 'createTime', $start_time])
            ->andWhere(['<', 'createTime', $end_time])
            ->count();
        return $count;
    }


    /**
     * 获取某天小时数据
     *
     * @param $date
     * @return array
     * @throws \Exception
     */
    public static function getHourIncomeByDate($date)
    {
        $return = [];
        $tmp_income = 0;
        $data = self::getHourPayOrderByDate($date);
        for ($i = 0; $i < 24; $i++) {
            $hour_income = ArrayHelper::getValue($data, $i, 0);
            $return[$i] = $hour_income + $tmp_income;
            $tmp_income += $hour_income;
        }
        return $return;
    }

    public static function getDateIncomeByRange($start_date, $end_date)
    {
        $return = [];
        $start_time = strtotime($start_date);
        $end_time = strtotime($end_date);
        if ($end_time < $start_time) $end_time = $start_time;
        $data = self::getDayIncome($start_time, $end_time + 86400);
        while ($start_time <= $end_time) {
            $date = date('Y-m-d', $start_time);
            $return[$date] = ArrayHelper::getValue($data, $date, 0);
            $start_time = $start_time + 86400;
        }
        return $return;
    }

    public static function getDayIncome($start_time, $end_time)
    {
        $data = [];
        $r = \Yii::$app->db->createCommand('SELECT SUM(PAY) as income, date_format(FROM_UNIXTIME(createTime, :createTime), \'%Y-%m-%d\') AS day
            FROM order_record
            WHERE (payStatus= :payStatus)
            AND (cancelStatus = :cancelStatus)
            AND (closeStatus= :closeStatus)
            AND (createTime >= :start_time)
            AND (createTime < :end_time) GROUP BY day',
            [
                ':createTime' => '%Y-%m-%d %H:%i:%s',
                ':payStatus' => OrderRecord::PAY_STATUS_OK,
                ':cancelStatus' => OrderRecord::CANCEL_STATUS_NON,
                ':closeStatus' => OrderRecord::CLOSE_STATUS_NON,
                ':start_time' => $start_time,
                ':end_time' => $end_time,
            ]
        )->queryAll();
        foreach ($r as $k => $v) {
            $data[$v['day']] = $v['income'] / 100;
        }
        return $data;
    }

    /**
     * 获取付款金额
     *
     * @param $date
     * @return mixed
     * @throws \Exception
     */
    public static function getPayTotalByDate($date)
    {
        $start_time = strtotime($date . ' 00:00:00');
        if (empty($start_time)) {
            throw new \Exception('date error');
        }
        $end_time = $start_time + 86400;
        $data = OrderRecord::find()->select('SUM(pay) as pay')->where(['payStatus' => OrderRecord::PAY_STATUS_OK,])
            ->andWhere(['cancelStatus' => OrderRecord::CANCEL_STATUS_NON])
            ->andWhere(['closeStatus' => OrderRecord::CLOSE_STATUS_NON])
            ->andWhere(['>=', 'createTime', $start_time])
            ->andWhere(['<', 'createTime', $end_time])
            ->asArray()
            ->one();
        return !empty($data) ? $data['pay'] / 100 : 0;
    }

    public static function getHourPayOrderByDate($date)
    {
        $start_time = strtotime($date . ' 00:00:00');
        if (empty($start_time)) {
            throw new \Exception('date error');
        }
        $end_time = $start_time + 86400;

        $data = [];
        $r = \Yii::$app->db->createCommand('SELECT SUM(pay) as income,date_format(FROM_UNIXTIME(createTime, :createTime), \'%H\') AS hour
            FROM order_record
            WHERE (payStatus= :payStatus)
            AND (cancelStatus = :cancelStatus)
            AND (closeStatus= :closeStatus)
            AND (createTime >= :start_time)
            AND (createTime < :end_time) GROUP BY hour',
            [
                ':createTime' => '%Y-%m-%d %H:%i:%s',
                ':payStatus' => OrderRecord::PAY_STATUS_OK,
                ':cancelStatus' => OrderRecord::CANCEL_STATUS_NON,
                ':closeStatus' => OrderRecord::CLOSE_STATUS_NON,
                ':start_time' => $start_time,
                ':end_time' => $end_time,
            ]
        )->queryAll();
        foreach ($r as $k => $v) {
            $data[(int)$v['hour']] = $v['income'] / 100;
        }

        return $data;
    }

}