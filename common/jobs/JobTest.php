<?php

namespace common\jobs;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\helpers\VarDumper;

/**
 * Class JobTest.
 */
class JobTest extends \yii\base\BaseObject implements \yii\queue\JobInterface
{

    public $message;
    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
    
        $channel->exchange_declare('logs', 'fanout', false, false, false);
        // $channel->queue_bind("queue","logs");
    
        if (empty($data)) {
            $data = "info: sarasa";
        }
        $msg = new AMQPMessage($this->message);
    
        $channel->basic_publish($msg, 'logs');
    
        // echo ' [x] Sent ', $data, "\n";
    
        $channel->close();
        $connection->close();
    }
}
