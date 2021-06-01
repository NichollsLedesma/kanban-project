<?php

use common\widgets\BoardCard\BoardCard;
use common\widgets\BoardColumn\BoardColumn;
use frontend\assets\dragula\DragulaAsset;
use frontend\assets\pahoMqtt\PahoMqttAsset;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this View */

$this->registerAssetBundle(DragulaAsset::class);
$this->registerAssetBundle(PahoMqttAsset::class);

$boardCode = \yii\helpers\Url::to(['kanban/board', 'uuid' => $boardUuid]);
$boardColumnIdPrefix = "column-id_";
$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/column.css'
);
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
        Yii::$app->request->BaseUrl . '/js/columns.js',
        [
            'depends' => "/js/dragula-impl.js",
            'position' => View::POS_END
        ]
);
//$this->registerJsFile(
//        Yii::$app->request->BaseUrl . '/js/board-elements.js',
//        [
//            'depends' => "/js/dragula-impl.js",
//            'position' => View::POS_END
//        ]
//);

$this->registerJsFile(
        Yii::$app->request->BaseUrl . '/js/board-pjax.js',
        [
            'depends' => "/js/dragula-impl.js",
            'position' => View::POS_END
        ]
);
?>
<div class="content-wrapper kanban">
    <section class="content pb-3">
            <?php
            Pjax::begin(['id' => 'board-container']);
            echo '<div class="container-fluid h-100">';
            foreach ($boardColumns->all() as $column) {
                $cards = [];
                $columnsId[] = $boardColumnIdPrefix . $column->uuid;
                foreach ($column->getCards()->all() as $task) {
                    $cards[] = BoardCard::widget(['id' => $task->uuid, 'title' => $task->title, 'content' => $task->description]);
                }
                if ($newCardModel && $newCardModel->column_id == $column->id) {
                    $cards[] = $this->render('_newCard', ['model' => $newCardModel, 'columnId' => $column->uuid, 'boardUuid' => $boardUuid]);
                }
                echo BoardColumn::widget(['id' => $column->uuid, 'idPrefix' => $boardColumnIdPrefix, 'name' => $column->title, 'boardUuid' => $boardUuid, 'cards' => $cards]);
            }

            $cardCreationForm = [];
            if ($newColumnModel) {
                $cardCreationForm[] = $this->render('_newColumn', ['model' => $newColumnModel, 'boardUuid' => $boardUuid]);
            }
            echo BoardColumn::widget([ 'enableColumnCreation' => true, 'withHeader' => false, 'boardUuid' => $boardUuid, 'cards' => $cardCreationForm]);
            echo '</div>';
            $this->registerJsVar('columns', $columnsId, View::POS_END);
            Pjax::end();
            ?>
    </section>
</div>
