<?php

namespace common\models;

use Yii;

/**
 * Description of CardRepository
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class CardRepository extends Card
{

    /**
     * 
     * @param int $userId
     * @param string $uuid
     * @return \yii\db\ActiveRecord|null
     */
    static public function getUserBoardCardByUuid(int $userId, string $uuid): ?\yii\db\ActiveRecord
    {
        $card = parent::find()->where(['uuid' => $uuid])->limit(1);
        if ($card->count() == 0) {
            return null;
        }
        $col = Column::find()->select(['board_id'])->where(['id' => $card->select('column_id')->limit(1)])->limit(1);
        if ($col->count() == 0 || BoardRepository::getUserBoardById($userId, $col->asArray()->one()['board_id'])->count() == 0) {
            return null;
        }
        return $card->select([])->one();
    }

}
