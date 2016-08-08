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
        if (\Yii::$app->request->post('text') && \Yii::$app->request->post('id')) {

            try {
                if (Comments::find(\Yii::$app->request->post('id'))->exists()) {
                    $comment = $this->findModel(\Yii::$app->request->post('id'));
                    if ($comment->ip === \Yii::$app->request->userIP){
                        if (date('U') - $comment->created_at < \Yii::$app->params['time_UD_comments']){
                            $comment->text = \Yii::$app->request->post('text');
                            $comment->save();
                            return $comment;
                        } else {
                            throw new \yii\web\HttpException(403, 'Comment added over an '.\Yii::$app->params['time_UD_comments'].' seconds ago');
                        }
                    } else {
                        throw new \yii\web\HttpException(403, 'Comment was add from another IP');
                    }
                } else {
                    throw new \yii\web\HttpException(404, 'No comments found with this id');
                }
            } catch (Exception $ex) {
                throw new \yii\web\HttpException(500, 'Internal server error');
            }
        } else {
            throw new \yii\web\HttpException(400, 'Bad query string');
        }
    }

    public function actionDelete(){
        if (\Yii::$app->request->get('id')){
            try {
                $comment = Comments::findOne(\Yii::$app->request->get('id'));
                if ($comment != null) {
                    if ($comment->ip === \Yii::$app->request->userIP){
                        if (date('U') - $comment->created_at < \Yii::$app->params['time_UD_comments']){
                            $comment->delete();
                            throw new \yii\web\HttpException(0,'Comments deleted successfully');
                        } else {
                            throw new \yii\web\HttpException(403, 'Comment added over an '.\Yii::$app->params['time_UD_comments'].' seconds ago');
                        }
                    } else {
                        throw new \yii\web\HttpException(403, 'Comment added from another IP');
                    }
                } else {
                    throw new \yii\web\HttpException(404, 'No comments found with this id');
                }
            } catch (Exception $ex) {
                throw new \yii\web\HttpException(500, 'Internal server error');
            }
        } else {
            throw new \yii\web\HttpException(404, 'Not found comment_id in query string');
        }
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\HttpException('The requested page does not exist.');
        }
    }


}
