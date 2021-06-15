<?php

use thamtech\uuid\helpers\UuidHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <!-- <?php if (!Yii::$app->getUser()->getIsGuest()) { ?> -->
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        <!-- <?php } ?> -->

        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= \yii\helpers\Url::home() ?>" class="nav-link">Home</a>
        </li>
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
            <!-- <li class="nav-item">
                <?= Html::a('Users', ['/user/index'], ['class' => 'nav-link']) ?>
            </li>
            <li class="nav-item">
                <?= Html::a('Entities', ['/entity/index'], ['class' => 'nav-link']) ?>
            </li> -->
            <li class="nav-item">
                <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
            </li>
        <?php } ?>
    </ul>
</nav>