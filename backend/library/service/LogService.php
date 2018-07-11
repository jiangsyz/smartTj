<?php
namespace backend\library\service;
use app\models\OrderBuyingRecord;
use app\models\OrderRecord;
use Yii;

class LogService
{
    /**
     *  获取今日数据统计
     *  return array
     */
    public static function getLogByDate()
    {
        try
        {
            $strat_time = strtotime(date('Y-m-d', time()));
            $end_time = $strat_time + 86400;
            $pay_order_ids = OrderRecord::find()->select('id')->where([
                    'payStatus' => OrderRecord::PAY_STATUS_OK,
                    'cancelStatus' => OrderRecord::CANCEL_STATUS_NON,
                    'closeStatus' => OrderRecord::CLOSE_STATUS_NON,
                ])
                ->andWhere(['createTime' => '>=' . $strat_time])
                ->andWhere(['createTime' => '<' . $end_time])
                ->asArray()
                ->column();

            if (empty($pay_order_ids)) {
                throw new \Exception('pay_order is empty');
            }
            $sub_order_ids = OrderRecord::find()->select('id')->where(['parentId' => $pay_order_ids])->asArray()->column();
            if (empty($sub_order_ids)) {
                throw new \Exception('sub_pay_order is empty');
            }

            $list = Yii::$app->db->createCommand('SELECT sourceId,SUM(finalPrice*buyingCount) as price, SUM(buyingCount) as buy_count
              FROM order_buying_record where sourceType = 2 GROUP BY sourceId ORDER BY price DESC
            ')->queryAll();
            if (empty($list)) {
                throw new \Exception('data is null');
            }
            return $list;
        }
        catch(\Exception $e){
            return [];
        }
    }

}