<?php

use common\widgets\listBoard\ListBoardWidget;
use yii\helpers\VarDumper;

$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/kanban.css'
);
?>

<?= ListBoardWidget::widget(
    [
        'boards' => $boards,
        'entities' => $entities,
        "title" => "",
        "isEnableToCreate" => true,
    ]
) ?>