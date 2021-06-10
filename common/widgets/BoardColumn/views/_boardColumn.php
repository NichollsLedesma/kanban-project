<div class="card card-row card-secondary column <?php if ($enableColumnCreation) echo 'transparent' ?>">

    <?php if ($withHeader) : ?>
        <div class="card-header">
            <h3 class="card-title">
                 <?php if ($updateForm): ?>
                    <?
                     echo $updateForm;
                    ?>
                <?php else: ?>
                <?= yii\helpers\Html::a($name, \yii\helpers\Url::to(["/kanban/board", 'uuid' => $boardUuid, 'updateColumn' => $id])); ?>
                <?php endif ?>
            </h3>
            <div class="dropdown" style="float: right;">
                <button class="dropbtn">...</button>
                    <div class="dropdown-content">
                        <span
                            class="archive-btn"
                            data-column-archive-url = "<?= \yii\helpers\Url::to(['kanban/archive-column','uuid' => $id]) ?>"
                        >
                            Archive
                        </span>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="card-body column-container-to-card" id="<?= $idPrefix . $id ?>" data-column-id="<?= $id ?>">
        <?php
        if (!empty($cards)) {
            foreach ($cards as $card) {
                echo $card;
            }
        }
        ?>
        <p>
            <?php
            if ($enableColumnCreation && empty($cards)) {
                echo yii\helpers\Html::a('+ add column', \yii\helpers\Url::to(["/kanban/board", 'uuid' => $boardUuid, 'addColumn' => 'addColumn']));
            }
            ?>
        </p>


    </div>
    <?php
    if (!$enableColumnCreation && $formCard === null) {
        echo yii\helpers\Html::a('+ add card', \yii\helpers\Url::to(["/kanban/board", 'uuid' => $boardUuid, 'addCard' => $id]), ['style' => 'display:block;margin: 15px auto;']);
    } elseif (!$enableColumnCreation && $formCard !== null) {
        echo $formCard;
    }
    ?>


</div>