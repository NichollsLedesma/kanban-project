<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserEntity */

$this->title = 'Update User Entity: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'User Entities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'user_id' => $model->user_id, 'entity_id' => $model->entity_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-entity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'entities' => $entities,
    ]) ?>

</div>
