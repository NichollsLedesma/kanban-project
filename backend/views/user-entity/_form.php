<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserEntity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-entity-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'user_id')->textInput() ?> -->

    <?= $form->field($model, 'user_id')->dropDownList($users, ['prompt' => 'Users']); ?>

    <!-- <?= $form->field($model, 'entity_id')->textInput() ?> -->

    <?= $form->field($model, 'entity_id')->dropDownList($entities, ['prompt' => 'Entities']); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
