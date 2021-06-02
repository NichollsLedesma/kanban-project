<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserBoard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-board-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'user_id')->textInput() ?> -->

    <?= $form->field($model, 'user_id')->dropDownList($users, ['prompt' => 'Users']); ?>

    <!-- <?= $form->field($model, 'board_id')->textInput() ?> -->

    <?= $form->field($model, 'board_id')->dropDownList($boards, ['prompt' => 'Boards']); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
