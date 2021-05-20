<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_board}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%board}}`
 */
class m210520_201054_create_junction_table_for_user_and_board_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_board}}', [
            'user_id' => $this->integer(),
            'board_id' => $this->text(36),
            'PRIMARY KEY(user_id, board_id)',
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_board-user_id}}',
            '{{%user_board}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_board-user_id}}',
            '{{%user_board}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `board_id`
        $this->createIndex(
            '{{%idx-user_board-board_id}}',
            '{{%user_board}}',
            'board_id'
        );

        // add foreign key for table `{{%board}}`
        $this->addForeignKey(
            '{{%fk-user_board-board_id}}',
            '{{%user_board}}',
            'board_id',
            '{{%board}}',
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
            '{{%fk-user_board-user_id}}',
            '{{%user_board}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_board-user_id}}',
            '{{%user_board}}'
        );

        // drops foreign key for table `{{%board}}`
        $this->dropForeignKey(
            '{{%fk-user_board-board_id}}',
            '{{%user_board}}'
        );

        // drops index for column `board_id`
        $this->dropIndex(
            '{{%idx-user_board-board_id}}',
            '{{%user_board}}'
        );

        $this->dropTable('{{%user_board}}');
    }
}
