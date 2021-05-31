
<div class="card card-row card-secondary">
    <div class="card-header">
        <h3 class="card-title">
            <?= $name ?>
        </h3>
    </div>
    <div class="card-body" id="<?= $idPrefix . $id ?>" data-column-id="<?= $id ?>">
        <?php
        if (!empty($cards)) {
            foreach ($cards as $card) {
                echo $card;
            }
        }
        ?>
        <p>
            <?=
            yii\helpers\Html::a('+ add card', \yii\helpers\Url::to(["/kanban/board", 'uuid' => $boardUuid, 'addCard' => $id]));
            ?>
        </p>
            <!--<p class="add-card">+ add card</p>-->
    </div>
</div>