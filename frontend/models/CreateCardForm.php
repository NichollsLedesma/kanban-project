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
        $this->color = "fff";
        parent::__construct($config);
    }

    public function createCard() {
        $this->order = 1;
        $this->owner_id = Yii::$app->getUser()->getId();
        $card = $this->save();
        return true;
    }

    public function cardCreated() {
        $server = 'rabbitmq';
        $clientId = 'server';

        $mqtt = new MqttClient($server, 1883, $clientId);
        $mqtt->connect(null, true);
        $mqtt->publish('board/create', 'new card123');
        $mqtt->disconnect();
    }

}
