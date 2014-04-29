<?php

$main = require(__DIR__ . '/main.php');

$config = [
	'id' => 'console-app',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'app\commands',
	'enableCoreCommands' => true,
	'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'log' => [
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => [
						'error',
						'warning',
					],
				],
			],
		],
	],
	'controllerMap' => [
		'migrate' => [
			'class' => 'yii\console\controllers\MigrateController',
			'migrationTable' => '{{%migrations}}',
		],
	],
];

$config = \yii\helpers\ArrayHelper::merge($config, $main);

return $config;