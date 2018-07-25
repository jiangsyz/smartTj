<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    #w1{
        width: 100px;
    }
</style>
<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['vip'],
        'method' => 'get',
    ]); ?>
    <div style="float: left; margin-right: 5px" class="form-group">
       <!-- <label class="control-label" for="w1">选择日期:</label>-->
    <?php
    echo DatePicker::widget([
        'name' => 'date',
        'type' => DatePicker::TYPE_INPUT,
        'value' => Yii::$app->request->get('date', date('Y-m-d')),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>
    </div>

    <div style="float: left; margin-right: 5px;" class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="form-group">
        <?= Html::a('导出', ['log/vip', 'format' => 'excel', 'date' => Yii::$app->request->get('date', date('Y-m-d')) ], ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
