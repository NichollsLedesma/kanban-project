<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserEntity */

$this->title = 'Create User Entity';
$this->params['breadcrumbs'][] = ['label' => 'User Entities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-entity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'entities' => $entities,
    ]) ?>

</div>
