<?php

namespace common\models;

use yii\db\ActiveQueryInterface;

/**
 * Description of BoardRepository
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class BoardRepository extends Board
{

    /**
     * get user board by uuid
     * @param int $userId
     * @param string $boardUuid
     * @return ActiveQueryInterface
     */
    static public function getUserBoardByUuid(int $userId, string $boardUuid): ActiveQueryInterface
    {
        return parent::find()->where(['id' => UserBoard::find()->select(['board_id'])->where(['user_id' => $userId, 'board_id' => Board::find()->select(['id'])->where(['uuid' => $boardUuid])->limit(1)])->limit(1)])->limit(1);
    }

    /**
     * get user board by id
     * @param int $userId
     * @param string $id board id
     * @return ActiveQueryInterface
     */
    static public function getUserBoardById(int $userId, string $id): \yii\db\ActiveQuery
    {
        return parent::find()->where(['id' => UserBoard::find()->select(['board_id'])->where(['user_id' => $userId, 'board_id' => $id])->limit(1)])->limit(1);
    }

}
