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

    <h1><?= Html::encode($this->title) ?></h1>

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

    <div class="container mt-5 box-main">
        <div class="box">
            <div class="row box-header">
                <div class="col d-flex flex-row">
                    <div class="box-title">Details</div>
                </div>
                <div class="col d-flex flex-row-reverse">
                    <!-- <button class=" btn">
                        <i class="fa fa-plus"></i>
                    </button> -->
                </div>
            </div>

            <div class="box-content">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="key">username: </span>
                        <span class="value"><?= $model->username ?></span>
                    </li>
                    <li class="list-group-item">
                        <span class="key">email: </span>
                        <span class="value"><?= $model->email ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="box">
            <div class="row box-header">
                <div class="col d-flex flex-row">
                    <div class="box-title">Entities</div>
                </div>
                <div class="col d-flex flex-row-reverse">
                    <button class=" btn" data-toggle="modal" data-target="#modalAddNewEntity">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="box-content">
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

        <div class="box">
            <div class="row box-header">
                <div class="col d-flex flex-row">
                    <div class="box-title">Boards</div>
                </div>
                <div class="col d-flex flex-row-reverse">
                    <button class=" btn" data-toggle="modal" data-target="#modalAddNewBoard">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="box-content">
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