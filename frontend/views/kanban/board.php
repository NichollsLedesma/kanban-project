<?php

use frontend\assets\dragula\DragulaAsset;
use frontend\assets\pahoMqtt\PahoMqttAsset;
use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;

$this->registerAssetBundle(DragulaAsset::class);
$this->registerAssetBundle(PahoMqttAsset::class);


$boardCode = "channelName";
$columns = ArrayHelper::getColumn($board['columns'], 'name');
$this->registerJsVar('columns', $columns, View::POS_END);
$this->registerJsVar('channelName', $boardCode, View::POS_END);
// $this->registerJsVar('cards', $board['columns'], View::POS_END);

$this->registerJsFile(
    Yii::$app->request->BaseUrl . '/js/dragula-impl.js',
    [
        'depends' => "yii\web\JqueryAsset",
        'position' => View::POS_END
    ]
);

?>

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
                            <div class="card card-info card-outline task" id="card_<?= $task['id'] ?>">
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

<? Modal::begin([
    "id" => "detailModal",
    "title" => "",
    "size" => Modal::SIZE_LARGE,
]); ?>
<h1 class="title"></h1>
<p class="content"></p>
<? Modal::end(); ?>