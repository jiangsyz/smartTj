<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '产品名',
                'value'     => function ($model) {
                }
            ],
            [
                'label' => '上架数量',
                'attribute' => 'tickets_id',
                'value'     => function ($model) {

                }
            ],
            [
                'label' => '价格',
                'attribute' => 'tickets_id',
                'value'     => function ($model) {

                }
            ],
            [
                'label' => '价格',
                'attribute' => 'tickets_id',
                'value'     => function ($model) {

                }
            ],
            [
                'label' => '售出',
                'attribute' => 'tickets_id',
                'value'     => function ($model) {

                }
            ],
            [
                'label' => '收入',
                'attribute' => 'tickets_id',
                'value'     => function ($model) {

                }
            ],
            [
                'label' => '剩余数量',
                'attribute' => 'tickets_id',
                'value'     => function ($model) {

                }
            ],
        ],
    ]); ?>
</div>
