<?php

namespace common\models;

use common\widgets\BoardColumn\BoardColumn;

/**
 * Description of elementCardForm
 *
 * @author Alejandro Zanlongo <azanlongo at gmail.com>
 */
class ElementCreateColumnForm extends Column
{

    public function columnCreated() {
        $server = 'rabbitmq';
        $clientId = 'server';


        $mqtt = new \PhpMqtt\Client\MqttClient($server, 1883, $clientId);
        $mqtt->connect(null, true);
        $arr = array(
        	'type'=>'New Column',
        	'html'=> BoardColumn::widget([]));
        $mqtt->publish('board/create', json_encode($arr));
        $mqtt->disconnect();
    }

}
