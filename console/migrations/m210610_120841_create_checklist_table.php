<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%checklist}}`.
 */
class m210610_120841_create_checklist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%checklist}}', [
            'id' => $this->primaryKey(),
            'uuid' => $this->string(36)->unique(),
            'card_id' => $this->integer()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'title' => $this->string(100)->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'is_deleted' => $this->boolean()->notNull()->defaultValue(false),
            'deleted_at' => $this->integer()->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%checklist}}');
    }
}
