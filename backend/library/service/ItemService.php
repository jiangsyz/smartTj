<?php
namespace backend\library\service;
use app\models\OrderBuyingRecord;
use app\models\OrderRecord;
use Yii;

class ItemService
{
    /**
     * 获取商品统计
     *
     * @param $date
     * @return array
     */
    public static function getLogByDate($date)
    {
        try
        {
            $strat_time = strtotime($date.' 00:00:00');
            if(empty($strat_time)){
                throw new \Exception('date error');
            }
            $end_time = $strat_time + 86400;
            $pay_order_ids = OrderRecord::find()->select('id')->where([
                    'payStatus' => OrderRecord::PAY_STATUS_OK,
                ])
                ->andWhere(['cancelStatus' => OrderRecord::CANCEL_STATUS_NON])
                ->andWhere(['closeStatus' => OrderRecord::CLOSE_STATUS_NON])
                ->andWhere(['>=', 'createTime', $strat_time])
                ->andWhere(['<', 'createTime', $end_time])
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
              FROM order_buying_record where sourceType = 2 AND orderId IN ('.implode(', ', $sub_order_ids).') GROUP BY sourceId
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


    public static function getPendingData()
    {
        try
        {
            $pay_order_ids = OrderRecord::find()->select('id')->where([
                'payStatus' => OrderRecord::PAY_STATUS_OK,
            ])
                ->andWhere(['cancelStatus' => OrderRecord::CANCEL_STATUS_NON])
                ->andWhere(['closeStatus' => OrderRecord::CLOSE_STATUS_NON])
                ->andWhere(['deliverStatus' => OrderRecord::DELIVER_STATUS_PENDING])
                ->asArray()
                ->column();

            if (empty($pay_order_ids)) {
                throw new \Exception('pay_order is empty');
            }
            $sub_order_ids = OrderRecord::find()->select('id')->where(['parentId' => $pay_order_ids])->asArray()->column();
            if (empty($sub_order_ids)) {
                throw new \Exception('sub_pay_order is empty');
            }
            $list = Yii::$app->db->createCommand('SELECT sourceId, SUM(buyingCount) as buy_count
              FROM order_buying_record where sourceType = 2 AND orderId IN ('.implode(', ', $sub_order_ids).') GROUP BY sourceId
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