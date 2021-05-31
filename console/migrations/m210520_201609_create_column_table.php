<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%column}}`.
 */
class m210520_201609_create_column_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%column}}', [
            'id' => $this->primaryKey(),
            'uuid' => $this->string(36)->unique(),
            'board_id' => $this->integer()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'title' => $this->string(100)->notNull(),
            'order' => $this->integer()->notNull()->defaultValue(0),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
<<<<<<< HEAD
=======
            'is_deleted' => $this->boolean()->notNull()->defaultValue(false),
            'deleted_at' => $this->integer()->defaultValue(null),
>>>>>>> develop
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%column}}');
    }
}
