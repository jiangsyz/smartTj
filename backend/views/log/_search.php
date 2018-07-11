<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="form-group">
    <?php
    echo DatePicker::widget([
        'name' => 'dp_1',
        'type' => DatePicker::TYPE_INPUT,
        'value' => date('Y-m-d'),
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

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
