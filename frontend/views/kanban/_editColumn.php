<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['enableAjaxValidation' => false, 'enableClientValidation' => true, 'options' => ['data-pjax' => true]]);
echo  $form->field($model, 'title')->textInput()->label(false);
ActiveForm::end();
