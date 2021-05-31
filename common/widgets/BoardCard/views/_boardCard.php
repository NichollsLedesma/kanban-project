<?php

use yii\bootstrap4\Html;
?>
<div class="card card-info card-outline task" id="card_<?= $id ?>">

    <div class="card-header">
        <h5 class="card-title"><?= $title ?></h5>
        <div class="card-tools">
            <a href="#" class="btn btn-tool btn-link"></a>
            <?php if (!$isForm): ?>
                <a href="#" class="btn btn-tool">
                    <i class="fas fa-pen"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <p><?= $content ?></p>
    </div>
    <?php if ($isForm): ?>

        <div class="card-footer"><?= Html::submitButton('save', ['class' => 'btn btn-primary', 'name' => 'save-card-button']) ?> <?= Html::a('Cancel', yii\helpers\Url::to(['/kanban/board', 'uuid' => $boardUuid]), ['class' => 'btn btn-danger', 'name' => 'cancel-card-button']) ?></div>
    <?php endif; ?>
</div>