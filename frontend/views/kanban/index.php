<?php

use common\widgets\listBoard\ListBoardWidget;

$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/kanban.css'
);

?>
<?php if ( isset($hasEntity) ) { ?>
    <p>You don't belong to any entity, please, contact to the admin to create board.</p>
<?php } ?>

<?php if ($entity !== null) { ?>
    <div class="accordion" id="accordion<?= $entity->id ?>">
        <div class="card">
            <div class="card-header" id="heading<?= $entity->id ?>">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $entity->id ?>" aria-expanded="true" aria-controls="collapse<?= $entity->id ?>">
                        <h3 class="text-capitalize"><?= $entity->name ?></h3>
                    </button>
                </h2>
            </div>
            <div id="collapse<?= $entity->id ?>" class="collapse show" aria-labelledby="heading<?= $entity->id ?>" data-parent="#accordion<?= $entity->id ?>">
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
    </div>
<?php } ?>