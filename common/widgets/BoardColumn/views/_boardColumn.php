
<div class="card card-row card-secondary column <?php if($enableColumnCreation) echo 'transparent' ?>" >
    <?php if($withHeader) : ?>
    <div class="card-header">
            <h3 class="card-title">
                <?= $name ?>
            </h3>
    </div>
    <?php endif; ?>
    <div class="card-body" id="<?= $idPrefix . $id ?>" data-column-id="<?= $id ?>">
        <?php
        if (!empty($cards)) {
            foreach ($cards as $card) {
                echo $card;
            }
        }
        ?>
        <p>
            <?php
            if ($enableColumnCreation) {
                echo yii\helpers\Html::a('+ add column', \yii\helpers\Url::to(["/kanban/board", 'uuid' => $boardUuid, 'addColumn' => 'addColumn']));
            }
            else{
                echo yii\helpers\Html::a('+ add card', \yii\helpers\Url::to(["/kanban/board", 'uuid' => $boardUuid, 'addCard' => $id]));
            }
            ?>
        </p>
            <!--<p class="add-card">+ add card</p>-->
    </div>
</div>