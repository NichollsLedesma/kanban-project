<?php

use alexantr\colorpicker\ColorPicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<?php $formChecklist = ActiveForm::begin(['enableAjaxValidation' => false, 'enableClientValidation' => true]); ?>
            <?=  $formChecklist->field($model, 'title')->textInput(); ?>
            <?= Html::button('Add', ['class' => 'btn btn-primary add-checklist-btn', 'data-checklist-create-url' => Url::to(['/kanban/create-checklist', 'card' => $card]) ]); ?>
            <?= Html::button('Cancel', ['class' => 'btn btn-danger', 'name' => 'cancel-checklist-button', 'data-dismiss'=>'modal' ]); ?>
  <?php ActiveForm::end();?>
