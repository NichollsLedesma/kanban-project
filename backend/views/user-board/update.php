<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserBoard */

$this->title = 'Update User Board: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'User Boards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'user_id' => $model->user_id, 'board_id' => $model->board_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-board-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'boards' => $boards,
    ]) ?>

</div>
