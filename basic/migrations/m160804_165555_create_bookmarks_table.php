<?php

use yii\db\Migration;

/**
 * Handles the creation for table `bookmarks`.
 */
class m160804_165555_create_bookmarks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('bookmarks', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull()->unique(),
            'created_at' => $this->integer(),
        ], 'ENGINE InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('bookmarks');
    }
}
