<?php
namespace backend\library;

use \yii\web\Controller;
use \Yii;

class BaseController  extends Controller{

    public function getGet($key, $default = null)
    {
        return Yii::$app->request->get($key, $default);
    }

    public function getPost($key, $default = null)
    {
        return Yii::$app->request->post($key, $default);
    }
}