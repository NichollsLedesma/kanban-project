<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_entity}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%entity}}`
 */
class m210520_200520_create_junction_table_for_user_and_entity_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_entity}}', [
            'user_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'PRIMARY KEY(user_id, entity_id)',
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_entity-user_id}}',
            '{{%user_entity}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_entity-user_id}}',
            '{{%user_entity}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-user_entity-entity_id}}',
            '{{%user_entity}}',
            'entity_id'
        );

        // add foreign key for table `{{%entity}}`
        $this->addForeignKey(
            '{{%fk-user_entity-entity_id}}',
            '{{%user_entity}}',
            'entity_id',
            '{{%entity}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_entity-user_id}}',
            '{{%user_entity}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_entity-user_id}}',
            '{{%user_entity}}'
        );

        // drops foreign key for table `{{%entity}}`
        $this->dropForeignKey(
            '{{%fk-user_entity-entity_id}}',
            '{{%user_entity}}'
        );

        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-user_entity-entity_id}}',
            '{{%user_entity}}'
        );

        $this->dropTable('{{%user_entity}}');
    }
}
