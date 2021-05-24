<?php

use common\widgets\listBoard\CardWidget;
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
</div>