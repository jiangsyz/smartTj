<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sku".
 *
 * @property int $id 主键
 * @property string $uniqueId sku唯一编号
 * @property int $spuId SpuId
 * @property string $title 标题
 * @property int $count 库存数量
 * @property int $closed 是否下架(0=上架/1=下架)
 * @property int $locked 锁定(0=正常/1=锁定)
 */
class Sku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uniqueId', 'spuId', 'title'], 'required'],
            [['spuId', 'count', 'closed', 'locked'], 'integer'],
            [['uniqueId', 'title'], 'string', 'max' => 200],
            [['spuId', 'title'], 'unique', 'targetAttribute' => ['spuId', 'title']],
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
            'spuId' => 'Spu ID',
            'title' => 'Title',
            'count' => 'Count',
            'closed' => 'Closed',
            'locked' => 'Locked',
        ];
    }
}
