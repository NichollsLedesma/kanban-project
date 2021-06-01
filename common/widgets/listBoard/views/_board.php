<?php

use common\models\Board;
use common\widgets\listBoard\CardWidget;
use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;
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
    'class' => '',
    "id" => "board-form"
]); ?>

<div class="form-group">
    <?= Html::textInput('name', "", [
        'class' => "form-control",
        'required' => "on"
    ]); ?>
</div>

<?php if (count($entities) > 0) { ?>
    <div class="form-group">
        <?= Html::dropDownList('entity_id', 0, [null => 'Please select', 'options' => $entities], [
            'class' => "form-control",
            'required' => "on",
        ]) ?>
    </div>
    <?= Html::submitButton('Create', [
        'class' => 'btn btn-primary'
    ]) ?>
<?php } else { ?>
    <p>You don't belong to any entity, please, contact to the admin to create board.</p>
<?php } ?>

<?= Html::endForm(); ?>

<? Modal::end(); ?>