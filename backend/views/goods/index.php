<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '库存';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="form-group">
        <?= Html::a('导出', ['goods/stock', 'format' => 'excel', 'date' => Yii::$app->request->get('date', date('Y-m-d')) ], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '商品名称',
                'value'     => function ($model) {
                    return $model['name'];
                }
            ],
            [
                'label' => '商品编码',
                'value'     => function ($model) {
                    return $model['uniqueId'];
                }
            ],
            [
                'label' => '库存',
                'value'     => function ($model) {
                    return $model['count'];
                }
            ],
            [
                'label' => '待发货数量',
                'format' => 'raw',
                'attribute' => 'buy_count',
                'value'     => function ($model) {
                    return $model['buy_count'] ? Html::a($model['buy_count'],\yii\helpers\Url::toRoute(['goods/pending-detail','sku_id'=>$model['id']])) : 0;
                }
            ],
            [
                'label' => '配货中数量',
                'format' => 'raw',
                'attribute' => 'prepare_count',
                'value'     => function ($model) {
                    return $model['prepare_count'] ? Html::a($model['prepare_count'],\yii\helpers\Url::toRoute(['goods/prepare-detail','sku_id'=>$model['id']])) : 0;
                }
            ],
        ],
    ]); ?>
</div>
