<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use hail812\adminlte3\assets\AdminLteAsset;
use hail812\adminlte3\assets\BaseAsset;
use hail812\adminlte3\assets\FontAwesomeAsset;
use hail812\adminlte3\assets\PluginAsset;
use yii\widgets\Pjax;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    $assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

    $this->registerAssetBundle(AdminLteAsset::class);
    $this->registerAssetBundle(FontAwesomeAsset::class);
    // $this->registerAssetBundle(BaseAsset::class);
    // $this->registerAssetBundle(PluginAsset::class);
    $this->head()
    ?>
    <style>
        .alert-style {
            width: fit-content;
            float: right;
        }
    </style>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

        <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
        <div class="">
            <?= Alert::widget([
                'options' => ['class' => 'ml-auto alert-style'],
            ]) ?>
        </div>
        <div class="content-wrapper">
            <?= $content ?>
        </div>
        <?= $this->render('control-sidebar') ?>

    </div>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>