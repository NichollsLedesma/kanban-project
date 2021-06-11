<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['enableAjaxValidation' => false, 'enableClientValidation' => true, 'options' => ['data-pjax' => true]]);
echo  $form->field($model, 'title')->textInput()->label(false);
echo Html::submitButton('Add List', ['class' => 'btn btn-primary', 'name' => 'save-column-button']);
echo Html::a('Cancel', yii\helpers\Url::to(['/kanban/board', 'uuid' => $boardUuid]), ['class' => 'btn btn-danger', 'name' => 'cancel-card-button']);
ActiveForm::end();
