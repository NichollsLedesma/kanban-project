
<div class="card card-row card-secondary">
    <div class="card-header">
        <h3 class="card-title">
            <?= $name ?>
        </h3>
    </div>
    <div class="card-body" id="column-id_<?= $id ?>" data-column-id="<?= $id ?>">
        <?php
        if (!empty($cards)) {
            foreach ($cards as $card) {
                echo $card;
            }
        }
        ?>
        <p class="add-card">+ add card</p>
    </div>
</div>
