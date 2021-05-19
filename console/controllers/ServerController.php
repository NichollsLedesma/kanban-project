<?php

namespace console\controllers;

use console\daemons\EchoServer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
// use Ratchet\Http\HttpServer;
// use Ratchet\Server\IoServer;
// use Ratchet\WebSocket\WsServer;
use yii\console\Controller;
use yii\helpers\VarDumper;

class ServerController extends Controller
{
    public function actionStart($port = 15675)
    {

        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->exchange_declare('logs', 'fanout', false, false, false);

        // list($queue_name,,) = $channel->queue_declare("", false, false, true, false);

        $queue_name = "queue";
        $channel->queue_bind($queue_name, 'logs');

        echo " [*] Waiting for logs. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] ', $msg->body, "\n";
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
