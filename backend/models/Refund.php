<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "refund".
 *
 * @property int $id 主键
 * @property int $oid 订单id
 * @property int $bid 购物行为id
 * @property int $price 退款金额(单位为分)
 * @property int $applyHandlerType 申请者类型
 * @property int $applyHandlerId 申请者id
 * @property int $applyTime 申请时间
 * @property string $applyMemo 申请备注
 * @property int $rejectHandlerType 驳回者类型
 * @property int $rejectHandlerId 驳回者id
 * @property int $rejectTime 驳回时间
 * @property string $rejectMemo 驳回备注
 * @property int $status -1=驳回/0=待办/1=打款中/2=打款成功/3=打款失败
 */
class Refund extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refund';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['oid'], 'required'],
            [['oid', 'bid', 'price', 'applyHandlerType', 'applyHandlerId', 'applyTime', 'rejectHandlerType', 'rejectHandlerId', 'rejectTime', 'status'], 'integer'],
            [['applyMemo', 'rejectMemo'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'oid' => 'Oid',
            'bid' => 'Bid',
            'price' => 'Price',
            'applyHandlerType' => 'Apply Handler Type',
            'applyHandlerId' => 'Apply Handler ID',
            'applyTime' => 'Apply Time',
            'applyMemo' => 'Apply Memo',
            'rejectHandlerType' => 'Reject Handler Type',
            'rejectHandlerId' => 'Reject Handler ID',
            'rejectTime' => 'Reject Time',
            'rejectMemo' => 'Reject Memo',
            'status' => 'Status',
        ];
    }
}
