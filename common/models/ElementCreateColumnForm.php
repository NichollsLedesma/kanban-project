<?php

namespace common\models;

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
        $mqtt->publish('board/create', 'new column');
        $mqtt->disconnect();
    }

}
