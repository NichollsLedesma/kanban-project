<?php

use common\widgets\BoardCard\BoardCard;
use common\widgets\BoardColumn\BoardColumn;
use frontend\assets\dragula\DragulaAsset;
use frontend\assets\pahoMqtt\PahoMqttAsset;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this View */

$this->registerAssetBundle(DragulaAsset::class);
$this->registerAssetBundle(PahoMqttAsset::class);

$boardCode = "channelName";
//$columns = ArrayHelper::getColumn($board['columns'], 'name');
$this->registerJsVar('channelName', $boardCode, View::POS_END);
// $this->registerJsVar('cards', $board['columns'], View::POS_END);

$this->registerJsFile(
        Yii::$app->request->BaseUrl . '/js/dragula-impl.js',
        [
            'depends' => "yii\web\JqueryAsset",
            'position' => View::POS_END
        ]
);
$this->registerJsFile(
        Yii::$app->request->BaseUrl . '/js/board-elements.js',
        [
            'depends' => "/js/dragula-impl.js",
            'position' => View::POS_END
        ]
);
?>
<div class="content-wrapper kanban">
    <section class="content pb-3">
        <div class="container-fluid h-100">
            <?php
//            var_dump(get_class_methods($board->all()), 10, true);
//            die;
            foreach ($boardColumns->all() as $column) {
                $cards = [];
                $columnsName[] = $column->title;
//                var_dump(get_class_methods($column->getCards()),$column->getCards()->all());
//                die;
                foreach ($column->getCards()->all() as $task) {
                    $cards[] = BoardCard::widget(['id' => $task->id, 'title' => $task->title, 'content' => $task->description]);
                }
                echo BoardColumn::widget(['id' => $column->id, 'name' => $column->title, 'cards' => $cards]);
            }
            $this->registerJsVar('columns', $columnsName, View::POS_END);
            ?>
        </div>
    </section>
</div>

