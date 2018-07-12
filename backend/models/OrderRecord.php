<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_record".
 *
 * @property int $id 主键
 * @property string $code 订单编号
 * @property int $memberId 会员id
 * @property string $index 索引,一棵订单树中唯一
 * @property string $factoryType 工厂类型
 * @property string $title 标题
 * @property string $price
 * @property string $memberPrice
 * @property string $finalPrice
 * @property string $reduction
 * @property string $freight
 * @property int $pay 支付费用(分单位)
 * @property int $isNeedAddress 是否需要送货地址(0=不需要/1=需要)
 * @property int $parentId 父订单id
 * @property int $createTime 创建时间
 * @property int $payStatus 支付状态(0=待支付/1=已支付/-1=支付超时)
 * @property int $cancelStatus 取消状态(0=未取消/1=已取消)
 * @property int $closeStatus 关闭状态(0=未关闭/1=已关闭)
 * @property int $deliverStatus 配送状态(0=未配货/1=已配货/2=已发货/3=已签收)
 * @property int $refundingStatus 退费状态(0=不在退费中/1=退费中)
 * @property int $finishStatus 订单完成状态(0=未完成/1=已完成)
 * @property int $backKeepCountStatus 是否返过库存(0=没返/1=已返)
 * @property int $locked 锁定(0=正常/1=锁定)
 */
class OrderRecord extends \yii\db\ActiveRecord
{
    const PAY_STATUS_OK = 1;
    const PAY_STATUS_PENDING = 0;
    const PAY_STATUS_TIMEOUT = -1;

    const CANCEL_STATUS_OK = 1;
    const CANCEL_STATUS_NON = 0;

    const CLOSE_STATUS_OK = 1;
    const CLOSE_STATUS_NON = 0;

    const DELIVER_STATUS_PENDING = 0;
    const DELIVER_STATUS_STANDING = 1;
    const DELIVER_STATUS_OK = 2;
    const DELIVER_STATUS_FINISHED = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['memberId', 'index', 'factoryType', 'pay', 'isNeedAddress', 'createTime', 'payStatus', 'cancelStatus', 'closeStatus', 'deliverStatus', 'refundingStatus', 'finishStatus', 'backKeepCountStatus'], 'required'],
            [['memberId', 'pay', 'isNeedAddress', 'parentId', 'createTime', 'payStatus', 'cancelStatus', 'closeStatus', 'deliverStatus', 'refundingStatus', 'finishStatus', 'backKeepCountStatus', 'locked'], 'integer'],
            [['price', 'memberPrice', 'finalPrice', 'reduction', 'freight'], 'number'],
            [['code', 'title'], 'string', 'max' => 200],
            [['index'], 'string', 'max' => 300],
            [['factoryType'], 'string', 'max' => 100],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'memberId' => 'Member ID',
            'index' => 'Index',
            'factoryType' => 'Factory Type',
            'title' => 'Title',
            'price' => 'Price',
            'memberPrice' => 'Member Price',
            'finalPrice' => 'Final Price',
            'reduction' => 'Reduction',
            'freight' => 'Freight',
            'pay' => 'Pay',
            'isNeedAddress' => 'Is Need Address',
            'parentId' => 'Parent ID',
            'createTime' => 'Create Time',
            'payStatus' => 'Pay Status',
            'cancelStatus' => 'Cancel Status',
            'closeStatus' => 'Close Status',
            'deliverStatus' => 'Deliver Status',
            'refundingStatus' => 'Refunding Status',
            'finishStatus' => 'Finish Status',
            'backKeepCountStatus' => 'Back Keep Count Status',
            'locked' => 'Locked',
        ];
    }
}
