<?php
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=pgsql;dbname=yii2_test',
            'username' => 'docker',
            'password' => 'aa11aa',
            'charset' => 'utf8',
        ],
    ],
];
