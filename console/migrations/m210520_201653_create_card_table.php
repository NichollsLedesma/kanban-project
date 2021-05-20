<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%card}}`.
 */
class m210520_201653_create_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%card}}', [
            'id' => $this->text(36)->unique(),
            'column_id' => $this->text(36)->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'title' => $this->string(100)->notNull(),
            'description' => $this->string()->notNull(),
            'order' => $this->integer()->notNull()->defaultValue(0),
            'color' => $this->string(6),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%card}}');
    }
}
