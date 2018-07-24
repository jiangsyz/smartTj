<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '待发货商品';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="form-group">
        <?= Html::a('导出', ['goods/pending-order', 'format' => 'excel', 'date' => Yii::$app->request->get('date', date('Y-m-d')) ], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'buy_count',
                'value'     => function ($model) {
                    return $model['buy_count'];
                }
            ],
        ],
    ]); ?>
</div>
