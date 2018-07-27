<?php
namespace backend\library;

use yii2mod\rbac\filters\AccessControl;
use \yii\web\Controller;
use \Yii;

class BaseController  extends Controller{

    public $limit = 10000;

    public function getUserId()
    {
        return Yii::$app->user->id;
    }

    public function getGet($key, $default = null)
    {
        return Yii::$app->request->get($key, $default);
    }

    public function getPost($key, $default = null)
    {
        return Yii::$app->request->post($key, $default);
    }
}