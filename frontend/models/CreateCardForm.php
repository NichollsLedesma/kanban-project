<?php

namespace frontend\models;

use common\models\Card;
use Yii;

/**
 * Description of elementCardForm
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class CreateCardForm extends Card
{

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function createCard()
    {
        $orderMax = Card::find()->select('MAX("order")')->where(['column_id' => $this->column_id])->limit(1)->scalar();
        $this->order = $orderMax === null ? 0 : ($orderMax+1);
        $this->owner_id = Yii::$app->getUser()->getId();
        return $this->save();
    }

}
