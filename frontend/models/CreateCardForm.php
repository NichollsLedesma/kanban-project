<?php

namespace frontend\models;

use common\models\Card;
use PhpMqtt\Client\MqttClient;
use Yii;

/**
 * Description of elementCardForm
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class CreateCardForm extends Card
{

    public function __construct($config = []) {
        parent::__construct($config);
    }

    public function createCard() {
        $this->order = 1;
        $this->owner_id = Yii::$app->getUser()->getId();
        return $this->save();
    }

}
