<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '待发货订单号列表';
$this->params['breadcrumbs'][] = ['label' => '库存', 'url' => ['goods/stock']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($sku->spu->title.$sku->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '订单Id',
                'value'     => function ($model) {
                    return $model['parentId'];
                }
            ],
            [
                'label' => '数量',
                'value'     => function ($model) {
                    return $model['buyingCount'];
                }
            ],
        ],
    ]); ?>
</div>
