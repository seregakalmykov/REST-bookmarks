<?php

namespace app\controllers;

use app\models\Bookmarks;
use app\models\Comments;
use yii\rest\ActiveController;

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
                if (Bookmarks::find()->where(['url' => \Yii::$app->request->get('url')])->one() !== null){
                    $Bookmark = Bookmarks::find()->where(['url' => \Yii::$app->request->get('url')])->one();
                    $comments = Comments::find()->where(['bookmark_id' => $Bookmark->id])->all();
                    $response = [
                        'id' => $Bookmark->id,
                        'created_at' => $Bookmark->created_at,
                        'url' => $Bookmark->url,
                        'comments' => $comments,
                    ];
                    return $response;
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
