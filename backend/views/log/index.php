<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \kartik\datecontrol\DateControl;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '今日统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!--<div class="user-search">

        <?php /*$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); */?>

        <?php
/*            echo DateControl::widget([
                'name'=>'kartik-date-3',
                'value'=>time(),
                'type'=>DateControl::FORMAT_DATETIME,
                'displayTimezone'=>'Pacific/Chatham',
                'saveTimezone'=>'UTC'
            ]);
        */?>

        <div class="form-group">
            <?/*= Html::submitButton('Search', ['class' => 'btn btn-primary']) */?>
            <?/*= Html::resetButton('Reset', ['class' => 'btn btn-default']) */?>
        </div>

        <?php /*ActiveForm::end(); */?>

    </div>-->

    <?php



    ?>

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
                'label' => '售出数量',
                'attribute' => 'buy_count',
                'value'     => function ($model) {
                    return $model['buy_count'];
                }
            ],
            [
                'label' => '收入',
                'attribute' => 'price',
                'value'     => function ($model) {
                    return $model['price'];
                }
            ],

        ],
    ]); ?>
</div>
