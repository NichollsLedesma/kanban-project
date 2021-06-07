<?php

use common\models\UserBoard;
use common\models\UserEntity;
use common\widgets\details\BoxDetailWidget;
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

function getItems($data, $descr = "title", $key = "id")
{
    $items = [];
    foreach ($data as $item) {
        $items[] = [
            "id" => $item[$key],
            "description" => $item[$descr],
        ];
    }

    return $items;
}

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

        <?= BoxDetailWidget::widget([
            'items' => getItems($model->entities, "name"),
            'title' => "Entities",
            "class_relation" => "UserEntity",
            'key_class' => "entity",
            "user_id" => $model->id,
            "to_load" => $entities
        ]) ?>

        <?= BoxDetailWidget::widget([
            'items' => getItems($model->boards),
            'title' => "Boards",
            "class_relation" => "UserBoard",
            'key_class' => "board",
            "user_id" => $model->id,
            "to_load" => $boards
        ]) ?>
    </div>
</div>