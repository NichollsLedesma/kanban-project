<?php

use alexantr\colorpicker\ColorPicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

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
        <p>
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'save-card-button', 'disabled' => $isDeleted]) ?>
        </p>
        <?php ActiveForm::end(); ?>
        <?php if (count($model->checklists)>0): ?>
            <h5>Checklist <?=$model->checklists[0]->title?></h1>
            <?php
            foreach ($model->checklists[0]->checklistOptions as $option) {
                echo Html::checkbox($option->title, $option->is_checked,['label' => $option->title, 'labelOptions'=>['class' => ($option->is_checked)?'checked':''], 'data-update-option-status-url' => Url::to(["/kanban/update-checklist-option-status", 'uuid' => $option->uuid])]);
                echo '<br>';
            }
            ?>
            <?php
                $formCheckboxOption = ActiveForm::begin(['options' => ['data-pjax' => true]]);
                echo $formCheckboxOption->field($checklistOptionModel, 'title')->textInput()->label(false);
                echo $formCheckboxOption->field($checklistOptionModel, 'checklist_id')->hiddenInput(['value' => $model->checklists[0]->id, 'readOnly' => true])->label(false);
                echo Html::submitButton('Add Task', ['class' => 'btn btn-primary']);
            ActiveForm::end();
            ?>
        <?php endif ?>
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
        <p>
            <?php if (count($model->checklists)<=0): ?>
                <a href="<?= Url::toRoute(['/kanban/create-checklist', 'card' => $model->uuid]) ?>" data-pjax="0" class="btn btn-tool" data-toggle="modal" data-target="#checklistModal" onclick="boardCardLoadContent(this)">
                    Add Checklist
                </a>
            <?php endif ?>
        </p>
    </div>
</div>
<?php Pjax::end(); ?>