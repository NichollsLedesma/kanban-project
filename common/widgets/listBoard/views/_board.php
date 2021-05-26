<?php

use common\widgets\listBoard\CardWidget;
use yii\helpers\Html;
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
</div>