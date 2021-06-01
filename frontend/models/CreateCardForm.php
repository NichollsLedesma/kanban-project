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

    public function createCard(string $mqttTopic, string $columnUuid) {
        $this->order = 1;
        $this->owner_id = Yii::$app->getUser()->getId();
        if (!$this->save()) {
            return false;
        }
        $obj = ['type' => 'card', 'action' => 'new', 'params' => ['columnId' => $columnUuid, 'order' => 'last', 'html' => \common\widgets\BoardCard\BoardCard::widget(['id' => $this->uuid, 'title' => $this->title, 'content' => $this->title])]];
        Yii::$app->mqtt->sendMessage($mqttTopic, $obj);
        return true;
    }

}
