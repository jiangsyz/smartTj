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
        'action' => ['index'],
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

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div style="float: left; margin-right: 10px;" class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="form-group">
        <?= Html::a('导出Excel', ['log/index', 'format' => 'excel', 'date' => Yii::$app->request->get('date', date('Y-m-d')) ], ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
