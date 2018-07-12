<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'VIP会员卡统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', []); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '产品名',
                'attribute' => 'name',
                'value'     => function ($model) {
                    return $model['name'];
                }
            ],
            [
                'label' => '上架数量',
                'attribute' => 'name',
                'value'     => function ($model) {
                    return $model['count'];
                }
            ],
            [
                'label' => '普通价',
                'attribute' => 'price',
                'value'     => function ($model) {
                    return $model['price'];
                }
            ],
            [
                'label' => '会员价',
                'attribute' => 'price_v1',
                'value'     => function ($model) {
                    return $model['price_v1'];
                }
            ],
            [
                'label' => '待发货数',
                'attribute' => 'pending_count',
                'value'     => function ($model) {
                    return $model['pending_count'];
                }
            ],
            [
                'label' => '售出数量',
                'attribute' => 'buy_count',
                'value'     => function ($model) {
                    return $model['buy_count'];
                }
            ],
            [
                'label' => '收入',
                'attribute' => 'income',
                'value'     => function ($model) {
                    return $model['income'];
                }
            ],

        ],
    ]); ?>
</div>
