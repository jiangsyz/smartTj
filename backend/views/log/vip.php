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
    <?php  echo $this->render('_vip_search', []); ?>
    <div class="row">
        <div class="col-sm-4">
            <div  id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3> 售出总数 : <?=$total_buy_count;?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3> 总应收 : <?=$total_income;?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3> 总实收 : <?=$amount;?></h3>
                </div>
            </div>
        </div>

    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '产品名',
                'attribute' => 'title',
                'value'     => function ($model) {
                    return $model['title'];
                }
            ],
            [
                'label' => '价格',
                'attribute' => 'price',
                'value'     => function ($model) {
                    return $model['price'];
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
