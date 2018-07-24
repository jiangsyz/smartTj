<?php
namespace backend\library\service;
use app\models\OrderRecord;
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
        $pay_order_ids = OrderRecord::find()
            ->where(['<>','deliverStatus',3])
            ->andWhere(['cancelStatus' => 0])
            ->andWhere(['payStatus' => 1])
            ->andWhere(['closeStatus' => 0])
            ->andWhere(['<>' ,'finishStatus', 1])
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
        $pending_data = [];
        foreach($list as $v)
        {
            $pending_data[$v['sourceId']] = $v['buy_count'];
        }

        $skus = Sku::find()->asArray()->all();
        $spu = Spu::find()->select('id,title')->asArray()->indexBy('id')->all();
        foreach($skus as $k => $sku){
            $skus[$k]['name'] = ArrayHelper::getValue($spu[$sku['spuId']],'title','').$sku['title'];
            $skus[$k]['buy_count'] = ArrayHelper::getValue($pending_data,$sku['id'],0);
        }
        return $skus;
    }

}