<?php

use common\models\Board;
use common\widgets\listBoard\CardWidget;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h1><?= $title ?? '' ?></h1>
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
            "id" => "btn-create-board"
        ]);
    }
    ?>

</div>

<? Modal::begin([
    "id" => "newBoardModal",
    "title" => "Create new board",
    "size" => Modal::SIZE_DEFAULT,
]); ?>

<?= Html::beginForm('/board/create', 'POST', [
    'class' => 'form-inline',
    "id" => "board-form"
]); ?>

<?= Html::textInput('name', "", [
    'class' => "form-control",
    'required' => "on"
]); ?>
<?= Html::submitButton('Create', [
    'class' => 'btn btn-primary'
]) ?>

<?= Html::endForm(); ?>

<? Modal::end(); ?>