<?php

use common\widgets\listBoard\ListBoardWidget;

$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/kanban.css'
);
?>

<div class="accordion" id="accordionExample">
    <?php foreach ($boardsByEntity as $key => $boards) { 
        $firstEntity = $boards[0]->entity;
        ?>
        <div class="card">
            <div class="card-header" id="heading<?= $key ?>">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $key ?>" aria-expanded="true" aria-controls="collapse<?= $key ?>">
                        <h3 class="text-capitalize"><?= $firstEntity->name ?></h3>
                    </button>
                </h2>
            </div>
            <div id="collapse<?= $key ?>" class="collapse show" aria-labelledby="heading<?= $key ?>" data-parent="#accordionExample">
                <div class="card-body">
                    <?= ListBoardWidget::widget(
                        [
                            'boards' => $boards,
                            'entity_id' => $firstEntity->id,                            
                            "isEnableToCreate" => true,
                        ]
                    ) ?>
                </div>
            </div>

        </div>
    <?php } ?>
</div>

