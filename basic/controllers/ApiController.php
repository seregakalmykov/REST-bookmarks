<?php

namespace app\controllers;

use yii\rest\ActiveController;

class ApiController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
