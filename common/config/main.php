<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        // '@nineinchnick/nfy' => '@vendor/nineinchnick/yii2-nfy', 
    ],
    'modules' => [
		// 'nfy' => [
		// 	'class' => 'nineinchnick\nfy\Module',
		// ],
	],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        // 'dbmq' => [
		// 	'class' => 'nineinchnick\nfy\components\DbQueue',
		// 	'id' => 'queue',
		// 	'label' => 'Notifications',
		// 	'timeout' => 30,
		// ],
		// 'sysvmq' => [
		// 	'class' => 'nineinchnick\nfy\components\SysVQueue',
		// 	'id' => 'a',
		// 	'label' => 'IPC queue',
		// ],
		// 'redismq' => [
		// 	'class' => 'nineinchnick\nfy\components\RedisQueue',
		// 	'id' => 'mq',
		// 	'label' => 'Redis queue',
		// 	'redis' => 'redis',
		// ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
