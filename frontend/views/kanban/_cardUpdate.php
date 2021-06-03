<?php

use alexantr\colorpicker\ColorPicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;

/* @var $this View */
?>

<div class="col-md-12">
    <?php Pjax::begin(['id' => 'board-update-container', 'enablePushState' => false]); ?>
    <?php
    if (Yii::$app->session->hasFlash('updated')):
        $this->registerJs("$.pjax.reload({container: '#board-container', async: false});");
        ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Card updated!</h4>
        </div>
    <?php endif ?>
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => false, 'enableClientValidation' => true, 'options' => ['data-pjax' => true]]) ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'color')->widget(ColorPicker::class, ['options' => ['placeholder' => 'Color chooser']]) ?>
    <?= Html::submitButton('save', ['class' => 'btn btn-primary', 'name' => 'save-card-button']) ?>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
