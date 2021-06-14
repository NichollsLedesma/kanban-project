<?php

use yii\web\UrlRule;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/views'
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'kanban/index' => 'kanban/index',
                'kanban/entity/<uuid>' => 'kanban/show-entity',
                'kanban/<uuid>/get/<query>' => 'kanban/get',
                'kanban/get-one/<id>' => 'kanban/get-one',
                'kanban/<uuid>/column/order' => 'kanban/update-column-order',
                'kanban/<uuid>' => 'kanban/board',
                'board/create' => 'board/create',
                'DELETE kanban/column/<uuid>' => 'kanban/archive-column',
                'board/leave/<uuid>' => 'board/leave',
                'DELETE board/<uuid>/user/<user_id>' => 'board/remove-user',
                'board/update/<uuid>' => 'board/update',
                'board/delete/<uuid>' => 'board/delete',
                'kanban/card-update/<uuid>/<boardUuid>' => 'kanban/card-update'
            ],
        ],

    ],
    'params' => $params,
];
