<?php

namespace backend\controllers;
use Yii;
use yii\data\ArrayDataProvider;

class LogController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $list = Yii::$app->db->createCommand("select * from order_buying_record order BY  id DESC ")->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $list,
        ]);
        return $this->render('index', [
          //  'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
