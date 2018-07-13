<?php

namespace backend\controllers;

use app\models\OrderRecord;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
