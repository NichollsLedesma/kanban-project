<?php

use common\models\UserBoard;
use common\models\UserEntity;
use common\widgets\details\BoxDetailWidget;
use yii\bootstrap4\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="user-view">

    <div class="card">
        <div class="card-header">
            Personal info
        </div>
        <div class="card-body">
            <h4 class="card-title"><?= $model->username ?></h4>
            <p class="card-text p-2">
                <span class="key">email: </span>
                <span class="value"><?= $model->email ?></span>
            </p>
            <p>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <h5 class="card-header">
                    <span>Entities</span>
                    <button class="float-right btn" data-toggle="modal" data-target="#modalAddNewEntity">
                        <i class="fa fa-plus"></i>
                    </button>
                </h5>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($model->entities as $entity) { ?>
                                <tr>
                                    <th scope="row"><?= $entity->id ?></th>
                                    <td><?= $entity->name ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <h5 class="card-header">
                    <span>Boards</span>
                    <button class="float-right btn" data-toggle="modal" data-target="#modalAddNewBoard">
                        <i class="fa fa-plus"></i>
                    </button>
                </h5>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($model->boards as $board) { ?>
                                <tr>
                                    <th scope="row"><?= $board->id ?></th>
                                    <td><?= $board->title ?></td>
                                    <td><?= $board->getEntity()->one()->name ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<? Modal::begin([
    "id" => "modalAddNewEntity",
    "title" => "Link with entity",
    "size" => Modal::SIZE_DEFAULT,
]); ?>
<?= Html::beginForm("/user-entity/create", 'POST', [
    'class' => '',
    "id" => "entity-form"
]); ?>
<?= Html::hiddenInput("UserEntity[user_id]", $model->id); ?>
<?= Html::hiddenInput('is_redirect_admin', true); ?>
<div class="form-group">
    <?= Html::tag('span', "entity name", ["for" => "name", 'class' => "text-capitalize"]); ?>
    <?= Html::dropDownList("UserEntity[entity_id]", 0, [null => "Please select entity", 'options' => $entities], [
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


<? Modal::begin([
    "id" => "modalAddNewBoard",
    "title" => "Create new board",
    "size" => Modal::SIZE_DEFAULT,
]); ?>
<?= Html::beginForm("/user-board/create", 'POST', [
    'class' => '',
    "id" => "board-form"
]); ?>
<?= Html::hiddenInput("UserBoard[user_id]", $model->id); ?>
<?= Html::hiddenInput('is_redirect_admin', true); ?>
<div class="form-group">
    <?= Html::tag('span', "board name", ["for" => "name", 'class' => "text-capitalize"]); ?>
    <?= Html::dropDownList("UserBoard[board_id]", 0, [null => "Please select board", 'options' => $boards], [
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