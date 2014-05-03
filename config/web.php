<?php

$main = require(__DIR__ . '/main.php');

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'name' => 'Cmtt coder vacancy',
	'language' => 'ru',
	'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
	'defaultRoute' => 'main',
	'layout' => 'main',
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		/*'user' => [
			'identityClass' => 'app\models\Users',
			'enableAutoLogin' => true,
		],*/
		'errorHandler' => [
			//'errorAction' => 'site/error',
		],
		'mail' => [
			'class' => 'yii\swiftmailer\Mailer',
			'useFileTransport' => true,
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			//'enableStrictParsing'=>true,
			//'suffix' => '.html',
			'rules' => [
				'gii' => 'gii',
				'index/<factor:\d*>' => 'main/index',
				'medias/<media:.*>' => 'main/medias',
				'<action:\w+>' => 'main/<action>',
				'all-news' => 'main/all-news',
			],
		],
		'db' => [
			'enableQueryCache' => true,
			'queryCacheDuration' => 3000,
		],
	],
];

$config = \yii\helpers\ArrayHelper::merge($config, $main);

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		'allowedIPs' => [
			'*'
		],
	];
	//$config['modules']['gii'] = 'yii\gii\Module';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		'allowedIPs' => [
			'*'
		],
		'generators' => [
			'model' => [
				'class' => 'app\extended\yiisoft\yii2gii\generators\model\Generator',
				'templates' => [
					'default' => '@vendor/yiisoft/yii2-gii/generators/model/default',
				],
			],
		],
	];
}

return $config;