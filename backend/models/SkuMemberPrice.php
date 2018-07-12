<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sku_member_price".
 *
 * @property int $id 主键
 * @property int $skuId 库存计量单元id
 * @property int $lv 会员等级
 * @property string $price
 */
class SkuMemberPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sku_member_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['skuId', 'lv'], 'required'],
            [['skuId', 'lv'], 'integer'],
            [['price'], 'number'],
            [['skuId', 'lv'], 'unique', 'targetAttribute' => ['skuId', 'lv']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'skuId' => 'Sku ID',
            'lv' => 'Lv',
            'price' => 'Price',
        ];
    }

    /**
     * 获取sku价格
     *
     * @return array
     */
    public static function getData()
    {
        $data = [];
        $tmp = self::find()->asArray()->all();
        foreach($tmp as $v){
            $data[$v['skuId']][$v['lv']] = $v['price'];
        }
        return $data;
    }
}



