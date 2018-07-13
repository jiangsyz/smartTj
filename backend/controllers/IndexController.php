<?php

namespace backend\controllers;

use backend\library\BaseController as Controller;

class IndexController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
