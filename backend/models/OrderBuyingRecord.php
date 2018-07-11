<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_buying_record".
 *
 * @property int $id 主键
 * @property int $orderId 订单id
 * @property int $sourceType 资源类型
 * @property int $sourceId 资源id
 * @property int $buyingCount 购买数量
 * @property string $price
 * @property string $finalPrice
 * @property string $dataPhoto
 * @property string $logisticsCode 物流单号
 */
class OrderBuyingRecord extends \yii\db\ActiveRecord
{
    const SOURCE_TYPE_GOODS = 2;
    const SOURCE_TYPE_VIRTUAL = 7;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_buying_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderId', 'sourceType', 'sourceId', 'buyingCount'], 'required'],
            [['orderId', 'sourceType', 'sourceId', 'buyingCount'], 'integer'],
            [['price', 'finalPrice'], 'number'],
            [['dataPhoto'], 'string'],
            [['logisticsCode'], 'string', 'max' => 200],
            [['orderId', 'sourceType', 'sourceId'], 'unique', 'targetAttribute' => ['orderId', 'sourceType', 'sourceId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderId' => 'Order ID',
            'sourceType' => 'Source Type',
            'sourceId' => 'Source ID',
            'buyingCount' => 'Buying Count',
            'price' => 'Price',
            'finalPrice' => 'Final Price',
            'dataPhoto' => 'Data Photo',
            'logisticsCode' => 'Logistics Code',
        ];
    }
}
