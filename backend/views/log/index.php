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
    <div class="row">
        <div class="col-sm-3">
            <div  id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3> 售出总数 : <?=$total_buy_count;?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3> 总应收 : <?=$total_income;?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3> 总实收 : <?=$amount;?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3> 退款总额 : <?=$total_refund;?></h3>
                </div>
            </div>
        </div>
    </div>
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
