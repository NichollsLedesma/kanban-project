<?php

use common\widgets\listBoard\CardWidget;
use yii\bootstrap4\Modal;
use yii\helpers\Html;

?>

<div class="wrapper ">
    <?php foreach ($boards as $board) { ?>
        <?= Html::a(
            CardWidget::widget(["board" => $board]),
            ["kanban/board", 'uuid' => $board["uuid"]],
            [
                "class" => "link-board"
            ]
        ) ?>
    <?php } ?>
    <?
    if ($isEnableToCreate) {
        echo Html::tag('button', "create new board", [
            'class' => 'btn btn-default card-size-board',
            "id" => "btn-create-board",
            "data-toggle" => "modal",
            "data-target" => "#newBoardModal$entity_id",
        ]);
    }
    ?>

</div>

<? Modal::begin([
    "id" => "newBoardModal$entity_id",
    "title" => "Create new board",
    "size" => Modal::SIZE_DEFAULT,
]); ?>

<?= Html::beginForm('/board/create', 'POST', [
    'class' => '',
    "id" => "board-form-$entity_id"
]); ?>

<?= Html::tag('span', "Board name", ["for" => "name"]); ?>
<?= Html::textInput('name', "", [
    'class' => "form-control",
    'required' => "on",
    "id" => "name",
    "placeholder" => "Board name",
]); ?>
<?= Html::hiddenInput('entity_id', $entity_id); ?>

<?= Html::submitButton('Create', [
    'class' => 'btn btn-primary'
]) ?>

<?= Html::endForm(); ?>

<? Modal::end(); ?>