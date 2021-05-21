<?php

use yii\helpers\Html;

$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/kanban.css'
);
?>

<div class="wrapper">
    <?php foreach ($boards as $board) { ?>
        <?= Html::a(
            '<div class="card board" >
            <div class="card-body">
                <h5 class="card-title">' . $board["name"] . '</h5>
            </div>
        </div>',
            ["kanban/board", 'uuid' => $board["uuid"]]
        ) ?>

    <?php } ?>
</div>