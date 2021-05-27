<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

/* @var $form ActiveForm */
$form = ActiveForm::begin(['id' => 'board-element-column-form-' . $model->board_id, 'enableAjaxValidation' => false, 'enableClientValidation' => true]);
echo  $form->field($model, 'title')->textInput()->label(false);
echo Html::submitButton('Add List', ['class' => 'btn btn-primary', 'name' => 'save-column-button']);
echo Html::button('Cancel', ['class' => 'btn btn-danger', 'name' => 'cancel-column-button', 'onclick' => 'cancelColumnElement(this)']);
ActiveForm::end();
?>

