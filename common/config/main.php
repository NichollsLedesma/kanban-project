<?php

use yii\queue\amqp_interop\Queue;
use yii\queue\LogBehavior;

return [
    'bootstrap' => [
        'queue', // The component registers its own console commands
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        /* add above db config into your common/main-local */
        /* 'db' => [  
          'class' => 'yii\db\Connection',
          'dsn' => 'pgsql:host=pgsql;dbname=kanban',
          'username' => 'root',
          'password' => 'root',
          'charset' => 'utf8',
          'enableSchemaCache' => true,
          'schemaCacheDuration' => 3600,
          ], */
          
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => 'redis',
            'port' => 6379,
            'retries' => 1,
        ],
        'cache' => [
            'class' => yii\redis\Cache::class,
            'redis' => [
                'hostname' => 'redis',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'session' => [
            'class' => yii\redis\Session::class,
            'redis' => [
                'hostname' => 'redis',
                'port' => 6379,
                'database' => 1,
            ],
        ],
        // 'queue' => [
        //     'class' => \yii\queue\redis\Queue::class,
        //     'redis' => 'redis',
        //     'channel' => 'queue'/* <-- queue name */,
        //     'database' => 2,
        // ],
        'queue' => [
            'class' => Queue::class,
            'host' => 'rabbitmq',
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest',
            'queueName' => 'queue',
            'as log' => LogBehavior::class,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'port' => '1025',
                'host' => 'mail'
            ],
            // send all mails to a file by default. You have to set
// 'useFileTransport' to false and configure a transport
// for the mailer to send real emails.
            'useFileTransport' => false,
        ],
    ],
];
