<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;

$entities =  Yii::$app->getUser()->getIdentity()->getEntities()->asArray()->all();
$entityItems = [['label' => 'Entities', 'header' => true]];

foreach ($entities as $entity) {
    $url = '/kanban/entity/' . $entity['uuid'];
    $aux = [
        'label' => $entity['name'],
        'url' => [$url],
        'options' => ["id" => 'entity_' . $entity["id"]],
    ];

    if (Url::current() !== $url) {
        $aux["iconStyle"] = 'far';
    }

    $entityItems[] = $aux;
}


?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Kanban App</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= Yii::$app->getUser()->getIdentity()->username ?></a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => ArrayHelper::merge($entityItems, [
                    ['label' => '', 'header' => true],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Logout',
                        'template'=>'<a class="nav-link" href="{url}" data-method="post">{icon}{label}</a>',
                        'url' => ['site/logout'],
                        'icon' => 'sign-out-alt',
                        'visible' => !Yii::$app->user->isGuest,
                    ]
                ])
                
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>