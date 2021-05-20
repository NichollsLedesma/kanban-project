<?php

use frontend\assets\DragulaAsset;
use yii\helpers\ArrayHelper;
use yii\web\View;

$this->registerAssetBundle(DragulaAsset::class);


$boardCode = "logs";
$columns = ArrayHelper::getColumn($board['columns'], 'name');
$this->registerJsVar('columns', $columns, View::POS_END);
$this->registerJsVar('channelName', $boardCode, View::POS_END);

$this->registerCssFile(Yii::$app->request->BaseUrl .'css/kanban.css');
$this->registerJsFile(Yii::$app->request->BaseUrl . '/js/mqttws31.min.js', ['position' => View::POS_END]);
$this->registerJsFile(
    Yii::$app->request->BaseUrl . '/js/dragula-impl.js',
    [
        'depends' => "yii\web\JqueryAsset",
        'position' => View::POS_END
    ]
);

?>

<div class="content-wrapper kanban">
    <section class="content pb-3">
        <div class="container-fluid h-100" id="kanban-body">

            <?php foreach ($board['columns'] as $column) { ?>
                <div class="card card-row card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?= $column['name'] ?>
                        </h3>
                    </div>

                    <div class="card-body" id="<?= $column['name'] ?>" data-column-id="<?= $column['id'] ?>">
                        <?php foreach ($column['tasks'] as $task) { ?>
                            <div class="card card-info card-outline task" id="<?= $task['id'] ?>">
                                <div class="card-header">
                                    <h5 class="card-title"><?= $task['name'] ?></h5>
                                    <div class="card-tools">
                                        <a href="#" class="btn btn-tool btn-link"><?= $task['id'] ?></a>
                                        <a href="#" class="btn btn-tool">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p><?= $task['description'] ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            <?php } ?>

            <div id="add-list-column" class="card card-row" style="background:transparent; box-shadow: none">
                <button id="add-list" class="btn btn-primary" style="width:100%">Add another list</button>
            </div>

            <div class="card card-row card-secondary d-none template">
                <div class="card-header d-none">
                    <h3 class="card-title">
                    </h3>
                </div>
                <input type="text" class="form-control list-name-input">
                <button class="btn btn-primary list-creation-add">Add list</button>
                <button class="btn btn-danger list-creation-cancel">Cancel</button>
            </div>
        </div>
    </section>
</div>

<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>

<script type="text/javascript">
$( document ).ready(function() {

    $( "#add-list" ).click(function() {
        $( ".template" ).clone(true).insertBefore( "#add-list-column" ).removeClass('d-none').removeClass('template');
        $( "#add-list" ).parent().hide();
    });

    $( ".list-creation-cancel" ).click(function() {
        $( "#add-list" ).parent().show();
        $( this).parent().remove();
    });

    $( ".list-creation-add" ).click(function() {
        let listName = $(this).parent().children('.list-name-input').val();
        $(this).parent().children('.list-creation-cancel').remove();
        $(this).parent().children('.list-name-input').remove();
        $(this).parent().children('.card-header').removeClass('d-none');
        $(this).parent().children('.card-header').children('.card-title').html(listName);
        $(this).remove();
        $( "#add-list" ).parent().show();
    });

});
</script>