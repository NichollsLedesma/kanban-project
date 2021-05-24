<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_board".
 *
 * @property int $user_id
 * @property int $board_id
 *
 * @property Board $board
 * @property User $user
 */
class UserBoard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_board';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'board_id'], 'required'],
            [['user_id', 'board_id'], 'default', 'value' => null],
            [['user_id', 'board_id'], 'integer'],
            [['user_id', 'board_id'], 'unique', 'targetAttribute' => ['user_id', 'board_id']],
            [['board_id'], 'exist', 'skipOnError' => true, 'targetClass' => Board::className(), 'targetAttribute' => ['board_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'board_id' => 'Board ID',
        ];
    }

    /**
     * Gets query for [[Board]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBoard()
    {
        return $this->hasOne(Board::className(), ['id' => 'board_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
