<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $text
 * @property integer $bookmark_id
 * @property string $created_at
 * @property string $ip
 *
 * @property Bookmarks $bookmark
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */


    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bookmark_id'], 'integer'],
            [['created_at'], 'safe'],
            [['text', 'ip'], 'string', 'max' => 255],
            [['bookmark_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bookmarks::className(), 'targetAttribute' => ['bookmark_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'bookmark_id' => 'Bookmark ID',
            'created_at' => 'Created At',
            'ip' => 'Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmark()
    {
        return $this->hasOne(Bookmarks::className(), ['id' => 'bookmark_id']);
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                ],
                'value' => function() {
                        return date('U');
                    },
            ],
            'ip' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'ip',
                ],
                'value' => function() {
                        return Yii::$app->request->userIP;
                    },
            ],
        ];
    }

}
