<?php

use yii\grid\GridView;
use yii\helpers\Html;
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
                    <button class=" btn">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="box-content">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Name of the company</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Name of the company 2</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Name of the company 3</td>
                        </tr>
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
                    <button class=" btn">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="box-content">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Name of the board</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Name of the board 2</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Name of the board 3</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>