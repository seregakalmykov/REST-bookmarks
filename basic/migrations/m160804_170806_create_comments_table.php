<?php

use yii\db\Migration;

/**
 * Handles the creation for table `comments`.
 */
class m160804_170806_create_comments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
            'bookmark_id' => $this->integer(),
            'created_at' => $this->datetime(),
            'ip' => $this->string()->notNULL(),
        ]);
        $this->createIndex('FK_bookmark', 'comments', 'bookmark_id');
        $this->addForeignKey(
            'FK_bookmark', 'comments', 'bookmark_id', 'bookmarks', 'id', 'CASCADE', 'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('comments');
    }
}
