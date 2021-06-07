<?php

use common\widgets\listBoard\ListBoardWidget;

$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/kanban.css'
);


?>

<?php if (count($entities) === 0) { ?>
    <p>You don't belong to any entity, please, contact to the admin to create board.</p>
<?php } ?>

<div class="accordion" id="accordionExample">
    <?php foreach ($entities as $key => $entity) { ?>
        <div class="card">
            <div class="card-header" id="heading<?= $key ?>">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $key ?>" aria-expanded="true" aria-controls="collapse<?= $key ?>">
                        <h3 class="text-capitalize"><?= $entity->name ?></h3>
                    </button>
                </h2>
            </div>
            <div id="collapse<?= $key ?>" class="collapse show" aria-labelledby="heading<?= $key ?>" data-parent="#accordionExample">
                <div class="card-body">
                    <?= ListBoardWidget::widget(
                        [
                            'boards' => $entity->boards,
                            'entity_id' => $entity->id,
                            "isEnableToCreate" => true,
                        ]
                    ) ?>
                </div>
            </div>

        </div>
    <?php } ?>
</div>