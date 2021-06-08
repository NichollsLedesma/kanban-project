<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Board */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="board-form">

    <?php $form = ActiveForm::begin([
        'id' => 'board-form',
    ]); ?>

    <!-- <?= $form->field($model, 'uuid')->textInput(['maxlength' => true]) ?> -->

    <!-- <?= $form->field($model, 'entity_id')->textInput() ?> -->

    <?= $form->field($model, 'entity_id')->dropDownList($entities, ['prompt' => 'Entities']); ?>

    <!-- <?= $form->field($model, 'owner_id')->textInput() ?> -->

    <?= $form->field($model, 'owner_id')->dropDownList($users, ['prompt' => 'Owner']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
