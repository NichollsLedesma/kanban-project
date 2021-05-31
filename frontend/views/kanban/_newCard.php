<?php

use common\widgets\BoardCard\BoardCard;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['id' => 'board-element-card-form-' . $model->column_id, 'enableAjaxValidation' => false, 'enableClientValidation' => true, 'options' => ['data-pjax' => true]]);
echo BoardCard::widget(['isForm' => true, 'columnId' => $model->column_id, 'title' => $form->field($model, 'title')->textInput(['autofocus' => true]), 'content' => $form->field($model, 'description')->textarea()]);
ActiveForm::end();
