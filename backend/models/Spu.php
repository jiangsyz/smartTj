<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spu".
 *
 * @property int $id 主键
 * @property string $uniqueId spu唯一编号
 * @property string $title 标题
 * @property string $desc 描述
 * @property string $cover 封面图
 * @property double $freight 运费
 * @property int $distributeType 配送方式(1=冷链/2=非冷链)
 * @property int $logisticsId 物流渠道id
 * @property string $detail 商品详情
 * @property string $uri 详情uri
 * @property int $memberId 推荐人id
 * @property int $closed 是否下架(0=上架/1=下架)
 * @property int $locked 锁定(0=正常/1=锁定)
 * @property int $createTime 创建时间
 */
class Spu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uniqueId', 'title', 'freight', 'distributeType', 'logisticsId', 'memberId', 'createTime'], 'required'],
            [['freight'], 'number'],
            [['distributeType', 'logisticsId', 'memberId', 'closed', 'locked', 'createTime'], 'integer'],
            [['detail'], 'string'],
            [['uniqueId', 'title', 'desc', 'cover', 'uri'], 'string', 'max' => 200],
            [['title'], 'unique'],
            [['uniqueId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uniqueId' => 'Unique ID',
            'title' => 'Title',
            'desc' => 'Desc',
            'cover' => 'Cover',
            'freight' => 'Freight',
            'distributeType' => 'Distribute Type',
            'logisticsId' => 'Logistics ID',
            'detail' => 'Detail',
            'uri' => 'Uri',
            'memberId' => 'Member ID',
            'closed' => 'Closed',
            'locked' => 'Locked',
            'createTime' => 'Create Time',
        ];
    }
}
