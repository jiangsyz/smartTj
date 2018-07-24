<?php
namespace backend\library;

use yii\filters\AccessControl;
use \yii\web\Controller;
use \Yii;

class BaseController  extends Controller{

    public $limit = 10000;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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