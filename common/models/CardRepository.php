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

    /**
     * re-arrage cards by columns
     * return true always
     * @param int $cardId
     * @param int $position new position(order) 
     * @param int $columnId target column id
     * @return bool
     */
    static public function reArrageByCardId(int $cardId, int $position, int $columnId): bool
    {
        $columnCardOrdered = parent::find()->where(['column_id' => $columnId])->orderBy('order ASC')->all();
        $updateCard = true;
        $addition = 0;
        if (!empty($columnCardOrdered)) {
            foreach ($columnCardOrdered as $k => $v) {
                if ($v->id == $cardId) {
                    $updateCard = false;
                    $v->order = $position;
                    $addition = -1;
                } else {
                    if ($k < $position) {
                        $v->order = ($k + ($addition));
                    }
                    if ($k == $position) {
                        $addition = ($addition == 0 ? 1 : $addition);
                        $v->order = ($k + ($addition));
                        if (!$updateCard) {
                            $addition = 0;
                        }
                    }
                    if ($k > $position) {
                        $v->order = ($k + ($addition));
                    }
                }
                $v->save(false);
            }
        }
        if ($updateCard === true) {
            $card = parent::findOne(['id' => $cardId]);
            $card->order = $position;
            $card->column_id = $columnId;
            $card->save(false);
        }
        return true;
    }

}
