<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;


?>

<div class="box">
    <div class="row box-header">
        <div class="col d-flex flex-row">
            <div class="box-title"><?= $title ?></div>
        </div>
        <div class="col d-flex flex-row-reverse">
            <?php if ($is_enable_to_create) { ?>
                <button class=" btn" data-toggle="modal" data-target="#newItemModal<?= $key_class ?>">
                    <i class="fa fa-plus"></i>
                </button>
            <?php } ?>
        </div>
    </div>

    <div class="box-content">
        <table class="table table-striped">
            <tbody>
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <th scope="row"><?= $item["id"] ?></th>
                        <td><?= $item["description"] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<? Modal::begin([
    "id" => "newItemModal$key_class",
    "title" => "Create new $key_class",
    "size" => Modal::SIZE_DEFAULT,
]); ?>

<?= Html::beginForm("/user-$key_class/create", 'POST', [
    'class' => '',
    "id" => "$key_class-form"
]); ?>

<?= Html::hiddenInput($class_relation."[user_id]", $user_id); ?>
<?= Html::hiddenInput('is_redirect_admin', true); ?>
<div class="form-group">
    <?= Html::tag('span', "$key_class name", ["for" => "name", 'class' => "text-capitalize"]); ?>
    <?= Html::dropDownList($class_relation . "[" . $key_class . "_id]", 0, [null => "Please select $key_class", 'options' => $to_load], [
        'class' => "form-control",
        'required' => "on",
        'id' => "entity_id",
    ]) ?>
</div>

<?= Html::submitButton('Save', [
    'class' => 'btn btn-primary'
]) ?>

<?= Html::endForm(); ?>

<? Modal::end(); ?>