<div class="card card-row card-secondary <?php if($enableColumnCreation) echo 'transparent' ?>" >
    <?php if($withHeader) : ?>
        <div class="card-header">
            <h3 class="card-title">
                <?= $name ?>
            </h3>
        </div>
    <?php endif; ?>
    <div class="card-body" id="<?= $name ?>" data-column-id="<?= $id ?>">
        <?php
        if (!empty($cards)) {
            foreach ($cards as $card) {
                echo $card;
            }
        }
        ?>
        <?php if($enableCardCreation) : ?>
            <p class="add-card">+ add card</p>
        <?php endif; ?>
        <?php if($enableColumnCreation) : ?>
            <button id="add-list" class="btn btn-primary w-100">Add another list</button>
        <?php endif; ?>
    </div>
</div>