<?php

use frontend\assets\DragulaAsset;
use hail812\adminlte3\assets\AdminLteAsset;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\helpers\ArrayHelper;
use yii\web\View;

// $this->registerAssetBundle(AdminLteAsset::class);
$this->registerAssetBundle(DragulaAsset::class);


// $sendData = function () {
//     $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
//     $channel = $connection->channel();

//     $channel->exchange_declare('logs', 'fanout', false, false, false);

//     // $data = implode(' ', array_slice($argv, 1));
//     if (empty($data)) {
//         $data = "info: Hello World!";
//     }
//     $msg = new AMQPMessage($data);

//     $channel->basic_publish($msg, 'logs');

//     // echo ' [x] Sent ', $data, "\n";

//     $channel->close();
//     $connection->close();
// };

// $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
// $channel = $connection->channel();

// $channel->exchange_declare('logs', 'fanout', false, false, false);

// list($queue_name,,) = $channel->queue_declare("", false, false, true, false);

// $channel->queue_bind($queue_name, 'logs');

// // echo " [*] Waiting for logs. To exit press CTRL+C\n";

// $callback = function ($msg) {
//     echo ' [x] ', $msg->body, "\n";
// };

// $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

// while ($channel->is_open()) {
//     $channel->wait();
// }
// $channel->close();
// $connection->close();

$boardCode = "logs";
$columns = ArrayHelper::getColumn($board['columns'], 'name');
$this->registerJsVar('columns', $columns, View::POS_END);
$this->registerJsVar('channelName', $boardCode, View::POS_END);
// $this->registerJsVar('sendData', $sendData, View::POS_END);

$this->registerJsFile(Yii::$app->request->BaseUrl . '/js/mqttws31.min.js', ['position' => View::POS_END]);
$this->registerJsFile(
    Yii::$app->request->BaseUrl . '/js/dragula-impl.js',
    [
        'depends' => "yii\web\JqueryAsset",
        'position' => View::POS_END
    ]
);



?>

<style>
    .kanban {
        height: 100% !important;
        margin: 0 auto !important;
    }
</style>

<h1>kandan</h1>
<div class="content-wrapper kanban">
    <section class="content pb-3">
        <div class="container-fluid h-100">

            <?php foreach ($board['columns'] as $column) { ?>
                <div class="card card-row card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?= $column['name'] ?>
                        </h3>
                    </div>

                    <div class="card-body" id="<?= $column['name'] ?>" data-column-id="<?= $column['id'] ?>">
                        <?php foreach ($column['tasks'] as $task) { ?>
                            <div class="card card-info card-outline task" id="<?= $task['id'] ?>">
                                <div class="card-header">
                                    <h5 class="card-title"><?= $task['name'] ?></h5>
                                    <div class="card-tools">
                                        <a href="#" class="btn btn-tool btn-link"><?= $task['id'] ?></a>
                                        <a href="#" class="btn btn-tool">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p><?= $task['description'] ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            <?php } ?>

        </div>
    </section>
</div>