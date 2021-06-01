<?php

namespace common\models;

use common\widgets\BoardColumn\BoardColumn;
use Yii;

/**
 * Description of elementCardForm
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class CreateColumnForm extends Column
{

    public function createColumn() {
        $this->owner_id = Yii::$app->getUser()->getId();
        $this->save();
        return true;
    }

    public function columnCreated() {
        $server = 'rabbitmq';
        $clientId = 'server';


        $mqtt = new \PhpMqtt\Client\MqttClient($server, 1883, $clientId);
        $mqtt->connect(null, true);
        $arr = array(
        	'type'=>'New Column',
        	'html'=> BoardColumn::widget([
                'name' => $this->title,
                'id' => $this->uuid,
                'boardUuid' => $this->board->uuid,
            ]));
        $mqtt->publish('board/create', json_encode($arr));
        $mqtt->disconnect();
    }

}
