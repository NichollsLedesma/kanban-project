<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserBoard */

$this->title = 'Create User Board';
$this->params['breadcrumbs'][] = ['label' => 'User Boards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-board-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'boards' => $boards,
    ]) ?>

</div>
