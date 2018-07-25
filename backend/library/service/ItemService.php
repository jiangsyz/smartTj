<?php
namespace backend\library\service;
use app\models\OrderRecord;
use app\models\Refund;
use app\models\Sku;
use app\models\Spu;
use Yii;
use yii\helpers\ArrayHelper;

class ItemService extends Service
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
            $start_time = strtotime($date.' 00:00:00');
            if(empty($start_time)){
                throw new \Exception('date error');
            }
            $end_time = $start_time + 86400;
            $pay_order_ids = OrderRecord::find()->select('id')->where([
                    'payStatus' => OrderRecord::PAY_STATUS_OK,
                ])
                ->andWhere(['cancelStatus' => OrderRecord::CANCEL_STATUS_NON])
                ->andWhere(['closeStatus' => OrderRecord::CLOSE_STATUS_NON])
                ->andWhere(['>=', 'createTime', $start_time])
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
     * 获取待发货数据
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPendingData()
    {
        try {
            $pay_order_ids = OrderRecord::find()
                ->where(['deliverStatus' => 0])
                ->andWhere(['cancelStatus' => 0])
                ->andWhere(['payStatus' => 1])
                ->andWhere(['closeStatus' => 0])
                ->andWhere(['finishStatus' => 0])
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
              FROM order_buying_record where sourceType = 2 AND orderId IN (' . implode(', ', $sub_order_ids) . ') GROUP BY sourceId
            ')->queryAll();
            $pending_data = [];
            foreach ($list as $v) {
                $pending_data[$v['sourceId']] = $v['buy_count'];
            }

            $skus = Sku::find()->asArray()->all();
            $spu = Spu::find()->select('id,title')->asArray()->indexBy('id')->all();
            foreach ($skus as $k => $sku) {
                $skus[$k]['name'] = ArrayHelper::getValue($spu[$sku['spuId']], 'title', '') . $sku['title'];
                $skus[$k]['buy_count'] = ArrayHelper::getValue($pending_data, $sku['id'], 0);
            }
            return $skus;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 获取每日退款总额
     *
     * @param $date
     * @return int|mixed
     */
    public static function getRefundCount($date)
    {
        try
        {
            $start_time = strtotime($date.' 00:00:00');
            if(empty($start_time)){
                throw new \Exception('date error');
            }
            $end_time = $start_time + 86400;
            $data = Refund::find()->select('sum(price) as count')->where([
                'status' => 2,
            ])
                ->andWhere(['>=', 'applyTime', $start_time])
                ->andWhere(['<', 'applyTime', $end_time])
                ->asArray()
                ->one();
            return (empty($data['count'])) ? 0 : $data['count'];
        }
        catch(\Exception $e){

            return 0;
        }
    }

}