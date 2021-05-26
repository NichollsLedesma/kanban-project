<?php

use common\widgets\listBoard\ListBoardWidget;

$this->registerCssFile(
    Yii::$app->request->getBaseUrl() . '/css/kanban.css'
);
?>

<div class="content-wrapper kanban">
    <section class="content pb-3">
        <div class="container-fluid h-100" id="kanban-body">

            <?php foreach ($boards['columns'] as $column) { ?>
                <div class="card card-row card-secondary">
                    <div class="card-header">
                        <h3 class="card-title edit-title" style="width: 90%">
                            <?= $column['name'] ?>
                        </h3>
                        <input class="form-control d-none title-input" type="text" value="<?= $column['name'] ?>"style="width: 90%; display: inline-block;">
                        <div class="dropdown" style="float: right;">
                            <button class="dropbtn">...</button>
                            <div class="dropdown-content">
                                <a class="archive-btn">Archive</a>
                            </div>
                        </div>
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
                <div class="card-header">
                    <h3 class="card-title edit-title d-none" style="width: 90%">
                    </h3>
                    <input type="text" class="form-control list-name-input title-input" style="width: 90%; display: inline-block;">
                    <div class="dropdown" style="float: right;">
                      <button class="dropbtn">...</button>
                      <div class="dropdown-content">
                        <a class="archive-btn">Archive</a>
                      </div>
                    </div>
                </div>
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
        let listName = $(this).parent().children('.card-header').children('.list-name-input').val();
        $(this).parent().children('.list-creation-cancel').remove();
        $(this).parent().children('.card-header').children('.list-name-input').addClass('d-none');
        $(this).parent().children('.card-header').children('.card-title').removeClass('d-none');
        $(this).parent().children('.card-header').children('.card-title').html(listName);
        $(this).remove();
        $( "#add-list" ).parent().show();
    });


    $( ".edit-title" ).click(function() {
        $(this).parent().children('.title-input').removeClass('d-none');
        $(this).addClass('d-none');
        $(this).parent().children(".title-input").focus();
    });

    $( ".title-input" ).blur(function() {
        $(this).addClass('d-none');
        $(this).parent().children(".card-title" ).removeClass('d-none');
        $(this).parent().children(".card-title").html($( this).val());
    });

    $('.dropbtn').click(function() {
        $(this).next('.dropdown-content').addClass('show');
    });

    $('.archive-btn').click(function() {
        $(this).parent().parent().parent().parent().remove();
    });

    $(window).click(function(e) {
        if (!e.target.matches('.dropbtn')) {
            $('.dropdown-content').removeClass('show');
        }
    });

});


</script>

<style type="text/css">

.dropbtn {
  background-color: transparent;
  color: white;
  border: none;
  cursor: pointer;
  padding: 0px;
}

.dropbtn:hover, .dropbtn:focus {
  background-color: #5c646b;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  color: black;
}

.dropdown-content a {
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  color: black!important;
}

.dropdown-content a:hover {background-color: #ddd}

.show {display:block;}

<?= ListBoardWidget::widget(
    [
        'boards' => $boards,
        "title" => ""
    ]
) ?>
