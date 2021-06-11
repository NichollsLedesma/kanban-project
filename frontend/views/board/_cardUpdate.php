<?php

use alexantr\colorpicker\ColorPicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;

/* @var $this View */
$isDeleted = $model->is_deleted;
?>
<?php Pjax::begin(['id' => 'board-update-container', 'enablePushState' => false]); ?>
<div class="row">
    <div class="col-md-8">
        <?php
        if (Yii::$app->session->hasFlash('updated')):
            $this->registerJs("$.pjax.reload({container: '#board-container', async: false});");
            ?>
            <div class="alert alert-success alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Card updated!</h4>
            </div>
        <?php endif ?>
        <?php
        if (Yii::$app->session->hasFlash('deleted')):
            $this->registerJs("$.pjax.reload({container: '#board-container', async: false});");
            ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Card was deleted!</h4>
            </div>
        <?php endif ?>
        <?php $form = ActiveForm::begin(['id' => 'update-card-form-' . $model->uuid, 'enableAjaxValidation' => false, 'enableClientValidation' => true, 'options' => ['data-pjax' => true]]) ?>
        <?= $form->field($model, 'title')->textInput(['disabled' => $isDeleted]) ?>
        <?= $form->field($model, 'description')->textarea(['disabled' => $isDeleted]) ?>
        <?= $form->field($model, 'color')->widget(ColorPicker::class, ['options' => ['placeholder' => 'Color chooser', 'disabled' => $isDeleted]]) ?>
        <?= Html::submitButton('save', ['class' => 'btn btn-primary', 'name' => 'save-card-button', 'disabled' => $isDeleted]) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-4" style="padding-top: 15px; text-align: right">
        <p>
            <?php
            $formDelete = ActiveForm::begin(['id' => 'delete-card-form-' . $model->uuid, 'options' => ['data-pjax' => true]]);
            echo $formDelete->field($deleteModel, 'cardId')->hiddenInput(['value' => $model->uuid, 'readOnly' => true])->label(false);
            echo Html::submitButton('delete', ['class' => 'btn btn-danger', 'disabled' => $isDeleted, 'data' => [
                    'confirm' => 'Are you sure you want to delete this card?']]);
            ActiveForm::end();
            ?> 
        </p>
    </div>
</div>
<?php Pjax::end(); ?>