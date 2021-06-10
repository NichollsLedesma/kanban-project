<?php

use thamtech\uuid\helpers\UuidHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;

$hasUuid = !Yii::$app->getUser()->getIsGuest() && UuidHelper::isValid(explode("kanban/", Url::current())[1]);

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= \yii\helpers\Url::home() ?>" class="nav-link">Home</a>
        </li>

            <?php if ($hasUuid) { ?>
                <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#boardMenu">Menu</a>
                </li> -->
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
            <?php if ($hasUuid) { ?>
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
                                'minLength' => '2',
                            ],
                        ]);
                        ?>
                        <?= Html::button('<i class="fas fa-search"></i>', ['class' => 'btn btn-navbar']) ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <!-- <i class="fas fa-th-large"></i> -->Show Menu
                    </a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
            </li>
        <?php } ?>
    </ul>
</nav>