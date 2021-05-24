<?php

use common\widgets\listBoard\ListBoardWidget;

$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/kanban.css'
);
?>

<?= ListBoardWidget::widget(
    [
        'boards' => $boards,
        "title" => ""
    ]
) ?>
