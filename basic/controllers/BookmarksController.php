<?php

namespace app\controllers;

use app\models\Bookmarks;
use app\models\Comments;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class BookmarksController extends ActiveController
{
    public $modelClass = 'app\models\Bookmarks';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        // отключить ненужные действия
        unset($actions['delete'], $actions['update'], $actions['options']);

        return $actions;

    }

    public function prepareDataProvider()
    {
        $modelClass = $this->modelClass;
        return $modelClass::find()->orderBy(['id' => SORT_DESC])->limit(\Yii::$app->params['count_bookmarks'])->all();
    }

    public function actionSearch()
    {
        if (!empty($_GET)) {
            try {
                if ($bookmark = Bookmarks::find()->where(['url' => \Yii::$app->request->get('url')])->all() !== null){
                    return Bookmarks::find()->where(['url' => \Yii::$app->request->get('url')])->all();
                } else {
                    throw new \yii\web\HttpException(404, 'No bookmarks found with this url');
                }
            } catch (Exception $ex) {
                throw new \yii\web\HttpException(500, 'Internal server error');
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }
}
