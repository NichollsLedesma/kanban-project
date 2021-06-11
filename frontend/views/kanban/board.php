<?php

use common\widgets\BoardCard\BoardCard;
use common\widgets\BoardColumn\BoardColumn;
use frontend\assets\dragula\DragulaAsset;
use frontend\assets\pahoMqtt\PahoMqttAsset;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this View */

$this->registerAssetBundle(DragulaAsset::class);
$this->registerAssetBundle(PahoMqttAsset::class);

$boardCode = \yii\helpers\Url::to(['kanban/board', 'uuid' => $boardUuid]);
$updateColumnOrderUrl = \yii\helpers\Url::to(['kanban/update-column-order', 'uuid' => $boardUuid]);
$boardColumnIdPrefix = "column-id_";
$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/column.css'
);

$this->registerJsVar('channelName', $boardCode, View::POS_END);
$this->registerJsVar('boardName', $boardName, View::POS_END);
$this->registerJsVar('board_id', $boardUuid, View::POS_END);
$this->registerJsVar('updateColumnOrderUrl', $updateColumnOrderUrl, View::POS_END);


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
?>

<div class="content-wrapper kanban m-0">
    <section class="content pb-3">
        <?php
        Pjax::begin(['id' => 'board-container', "options" => [
            "class" => "container-fluid h-100 m-0 mt-3"
        ]]);
        foreach ($boardColumns->all() as $column) {
            $cards = [];
            $formCard = null;
            $this->registerJS('addColumnDragula("' . $boardColumnIdPrefix . $column->uuid . '")', View::POS_END);
            foreach ($column->getCards()->orderBy('order ASC')->all() as $task) {
                $cards[] = BoardCard::widget(['id' => $task->uuid, 'title' => $task->title, 'content' => $task->description, 'color' => $task->color, 'boardUuid' => $boardUuid]);
            }
            if ($newCardModel && $newCardModel->column_id == $column->id) {
                $formCard = $this->render('_newCard', ['model' => $newCardModel, 'columnId' => $column->uuid, 'boardUuid' => $boardUuid]);
            }
            $updateForm = null;
            if ($updateColumnModel && $updateColumnModel->id == $column->id) {
                $updateForm = $this->render('_editColumn', ['model' => $updateColumnModel]);
            }
            echo BoardColumn::widget(['id' => $column->uuid, 'idPrefix' => $boardColumnIdPrefix, 'name' => $column->title, 'boardUuid' => $boardUuid, 'cards' => $cards, 'formCard' => $formCard, 'updateForm' => $updateForm]);
        }

        $cardCreationForm = [];
        if ($newColumnModel) {
            $cardCreationForm[] = $this->render('_newColumn', ['model' => $newColumnModel, 'boardUuid' => $boardUuid]);
        }
        echo BoardColumn::widget(['enableColumnCreation' => true, 'withHeader' => false, 'boardUuid' => $boardUuid, 'cards' => $cardCreationForm]);
        Pjax::end();
        ?>
    </section>
</div>

<?php
Modal::begin([
    "id" => "boardMenu",
    "title" => "Board menu",
    "size" => Modal::SIZE_DEFAULT
]); ?>

<div class="info">
    <h3 class="float-left"><?= $boardName ?></h3>
    <div class="ml-auto">
        <?= Html::button('<i class="fas fa-window-close"></i>', [
            "id" => "remove-board",
            "class" => "btn btn-danger btn-remove ",
        ]); ?>
    </div>
</div>
<hr>

<div class="members">
    <h4>Members</h4>
    <ul class="list-group">
        <?php foreach ($members as $member) { ?>
            <li class="list-group-item">
                <span class="float-left"><?= $member->username ?></span>
                <span class="float-right">
                    <? if (Yii::$app->getUser()->getId() === $member->id) {
                        echo Html::a('<i class="fas fa-sign-out-alt"></i>', Url::to(['/board/leave/' . $boardUuid]), [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to leave this board?',
                                'method' => 'post',
                            ],
                        ]);
                    } ?>
                </span>
            </li>
        <?php } ?>
    </ul>
</div>

<?php Modal::end(); ?>

<?php
Modal::begin([
    "id" => "detailModal",
    "title" => "",
    "size" => Modal::SIZE_DEFAULT,
]);
?>
<?= Html::tag("p", "", [
    "class" => "content",
])
?>

<?php Modal::end(); ?>
<?php
Modal::begin([
    "id" => "cardModal",
    "title" => "",
    "size" => Modal::SIZE_DEFAULT
]);
Modal::end();
?>