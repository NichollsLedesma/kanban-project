<?php

namespace common\models;

use common\widgets\BoardColumn\BoardColumn;
use Yii;

/**
 * Description of elementCardForm
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class UpdateColumnForm extends Column
{

    public function updateColumn() {
        $this->save();
        return true;
    }

    public function columnUpdated(string $mqttTopic) {
        $server = 'rabbitmq';
        $clientId = 'server';


        $mqtt = new \PhpMqtt\Client\MqttClient($server, 1883, $clientId);
        $mqtt->connect(null, true);
        $arr = array(
        	'type'=>'Column Updated',
        );
        $mqtt->publish($mqttTopic, json_encode($arr));
        $mqtt->disconnect();
    }

}
