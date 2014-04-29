<?php

$local = require(__DIR__ . '/local.php');

$config = [
	'components' => [
		'db' => [
			'tablePrefix' => 'cmtt_',
			'class' => 'yii\db\Connection',
			'charset' => 'utf8',
		],
	],
];

return \yii\helpers\ArrayHelper::merge($config, $local);