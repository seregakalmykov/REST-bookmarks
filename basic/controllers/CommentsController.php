<?php

namespace app\controllers;

use app\models\Bookmarks;
use app\models\Comments;
use yii\rest\ActiveController;

class CommentsController extends ActiveController
{


    public $modelClass = 'app\models\Comments';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index'], $actions['delete'], $actions['update'], $actions['options'], $actions['create']);

        return $actions;

    }

    public function actionCreate()
    {
        if (!empty($_POST)) {

            $comment = new Comments();
            foreach ($_POST as $key => $value) {
                if (!$comment->hasAttribute($key)) {
                    throw new \yii\web\HttpException(404, 'Invalid attribute:' . $key);
                }
            }
            if (\Yii::$app->request->post('text') && \Yii::$app->request->post('bookmark_id')){
                try {
                    if (Bookmarks::findOne(\Yii::$app->request->post('bookmark_id')) !== null) {
                        $comment->bookmark_id = \Yii::$app->request->post('bookmark_id');
                        $comment->text = \Yii::$app->request->post('text');
                        $comment->save();
                        return $comment;
                    } else {
                        throw new \yii\web\HttpException(404, 'No bookmarks found with this id');
                    }
                } catch (Exception $ex) {
                    throw new \yii\web\HttpException(500, 'Internal server error');
                }
            } else {
                throw new \yii\web\HttpException(404, 'Not found bookmark_id or text in query string');
            }

        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }

    public function actionUpdate(){
        if (!empty($_POST)) {
            $comment = new Comments();
            foreach ($_POST as $key => $value) {
                if (!$comment->hasAttribute($key)) {
                    throw new \yii\web\HttpException(404, 'Invalid attribute:' . $key);
                }
            }
            if (\Yii::$app->request->post('text') && \Yii::$app->request->post('id')){
                try {
                    if ($comment = self::findModel(\Yii::$app->request->post('id')) !== null) {

                        if ($comment->ip === Yii::$app->request->userIP){
                            if (date('U') - $comment->create_at < \Yii::$app->params['time_UD_comments']){
                                $comment->text = Yii::$app->request->post('text');
                                $comment->save();
                            } else {
                                throw new \yii\web\HttpException(403, 'Comment was add from another IP');
                            }
                        } else {
                            throw new \yii\web\HttpException(403, 'Comment was add from another IP');
                        }

                        return $comment;
                    } else {
                        throw new \yii\web\HttpException(404, 'No comments found with this id');
                    }
                } catch (Exception $ex) {
                    throw new \yii\web\HttpException(500, 'Internal server error');
                }
            } else {
                throw new \yii\web\HttpException(404, 'Not found comment_id or text in query string');
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }

    public function actionDelete(){
        if (!empty($_POST)) {
            $comment = new Comments();
            foreach ($_POST as $key => $value) {
                if (!$comment->hasAttribute($key)) {
                    throw new \yii\web\HttpException(404, 'Invalid attribute:' . $key);
                }
            }
            if (\Yii::$app->request->post('id')){
                try {
                    if ($comment = self::findModel(\Yii::$app->request->post('id')) !== null) {

                        if ($comment->ip === Yii::$app->request->userIP){
                            if (date('U') - $comment->create_at < \Yii::$app->params['time_UD_comments']){
                                $comment->text = Yii::$app->request->post('text');
                                $comment->save();
                            } else {
                                throw new \yii\web\HttpException(403, 'Comment added over an '.\Yii::$app->params['time_UD_comments'].' seconds ago');
                            }
                        } else {
                            throw new \yii\web\HttpException(403, 'Comment added from another IP');
                        }

                        return $comment;
                    } else {
                        throw new \yii\web\HttpException(404, 'No comments found with this id');
                    }
                } catch (Exception $ex) {
                    throw new \yii\web\HttpException(500, 'Internal server error');
                }
            } else {
                throw new \yii\web\HttpException(404, 'Not found comment_id in query string');
            }
        } else {
            throw new \yii\web\HttpException(400, 'There are no query string');
        }
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
