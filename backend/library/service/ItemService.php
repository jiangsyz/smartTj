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
            throw new \Exception('pay_order is empty');
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

    /**
     * 获取实收
     *
     * @param $date
     * @param int $type
     * @return int
     */
    public static function getTotalPriceByDate($date,$type = 2)
    {
        try {
            $strat_time = strtotime($date . ' 00:00:00');
            if (empty($strat_time)) {
                throw new \Exception('date error');
            }
            $end_time = $strat_time + 86400;
            $tmp = OrderRecord::find()->select('id,finalPrice')->where([
                'payStatus' => OrderRecord::PAY_STATUS_OK,
            ])
                ->andWhere(['cancelStatus' => OrderRecord::CANCEL_STATUS_NON])
                ->andWhere(['closeStatus' => OrderRecord::CLOSE_STATUS_NON])
                ->andWhere(['>=', 'createTime', $strat_time])
                ->andWhere(['<', 'createTime', $end_time])
                ->asArray()
                ->all();
            if (empty($tmp)) {
                throw new \Exception('pay_order is empty');
            }
            $final_price_data_tmp = [];
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
}