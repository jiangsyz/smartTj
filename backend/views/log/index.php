<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        echo $this->render('_search', []);
    ?>
    <div class="well">售出总数:<span style="font-weight: bolder"><?=$total_buy_count;?></span> 总应收:<span style="font-weight: bolder"><?=$total_income;?></span> 总实收:<span style="font-weight: bolder"><?=$amount;?></span></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'SPu Id',
                'attribute' => 'spu_id',
                'value'     => function ($model) {
                    return $model['spu_id'];
                }
            ],
            [
                'label' => 'Sku Id',
                'attribute' => 'id',
                'value'     => function ($model) {
                    return $model['id'];
                }
            ],
            [
                'label' => '产品名',
                'attribute' => 'name',
                'value'     => function ($model) {
                    return $model['name'];
                }
            ],
            [
                'label' => '库存',
                'attribute' => 'name',
                'value'     => function ($model) {
                    return $model['count'];
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
