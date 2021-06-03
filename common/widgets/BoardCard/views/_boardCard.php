<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="card card-info card-outline task" id="card_<?= $id ?>" style="border-top-color: #<?= $color ?>">

    <div class="card-header">
        <h5 class="card-title"><?= $title ?></h5>
        <div class="card-tools">
            <a href="#" class="btn btn-tool btn-link"></a>
            <?php if (!$isForm): ?>
                <a href="<?= Url::to(['/kanban/card-update', 'uuid' => $id]) ?>" data-pjax="0" class="btn btn-tool" data-toggle="modal" data-target="#cardModal" onclick="boardCardLoadContent(this)">
                    <i class="fas fa-pen"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <p><?= $content ?></p>
    </div>
    <?php if ($isForm): ?>

        <div class="card-footer"><?= Html::submitButton('save', ['class' => 'btn btn-primary', 'name' => 'save-card-button']) ?> <?= Html::a('Cancel', Url::to(['/kanban/board', 'uuid' => $boardUuid]), ['class' => 'btn btn-danger', 'name' => 'cancel-card-button']) ?></div>
    <?php endif; ?>
</div>