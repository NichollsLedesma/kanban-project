<?php

use thamtech\uuid\helpers\UuidHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\jui\AutoComplete;

$uuid = Yii::$app->request->get("uuid");
$isOnBoard = !Yii::$app->getUser()->getIsGuest() &&
    UuidHelper::isValid($uuid) &&
    !strpos(Url::current(), "kanban/entity");
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <?php if (!Yii::$app->getUser()->getIsGuest()) { ?>
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        <?php } ?>

        <?php if ($isOnBoard) { ?>
            <li class="nav-item d-none d-sm-inline-block">
                <?= Html::a('Home', ['board/go-back', "uuid" => $uuid], ['class' => 'nav-link']) ?>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <input type="text" name="boardname" id="boardname" value="" class="form-control" autocomplete="off">
            </li>
        <?php } ?>
    </ul>

    <ul class="navbar-nav ml-auto">
        <?php if (Yii::$app->getUser()->getIsGuest()) { ?>
            <li class="nav-item">
                <?= Html::a('Signup', ['/site/signup'], ['class' => 'nav-link']) ?>
            </li>
            <li class="nav-item">
                <?= Html::a('Login', ['/site/login'], ['class' => 'nav-link']) ?>
            </li>
        <?php } else { ?>
            <?php if ($isOnBoard) { ?>
                <li class="nav-item">
                    <!-- SEARCH FORM -->
                    <div class="form-inline ml-3">
                        <?= AutoComplete::widget([
                            'name' => 'search',
                            'id' => 'search',
                            'options' => [
                                'class' => "form-control form-control-navbar",
                                'type' => "search",
                                'placeholder' => "Search",
                                'aria-label' => "Search",
                            ],
                            'clientOptions' => [
                                'autoFill' => true,
                                'minLength' => '3',
                            ],
                        ]);
                        ?>
                        <?= Html::button('<i class="fas fa-search"></i>', ['class' => 'btn btn-navbar']) ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="board-settings-sidebar" data-widget="control-sidebar" data-slide="true" data-uuid="<?= $uuid ?>" href="#" role="button">
                        <!-- <i class="fas fa-th-large"></i> -->Settings
                    </a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
            </li>
        <?php } ?>
    </ul>
</nav>