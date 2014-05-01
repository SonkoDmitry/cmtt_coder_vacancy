<?php

namespace app\controllers;

use app\models\Links;
use app\models\Sites;

class MainController extends \yii\web\Controller
{
	public function actionIndex()
	{
		$model = Links::find()->orderBy('news_total_shares DESC')->all();
		echo $this->render('index', ['model' => $model]);
	}

	public function actionMedias($media = '')
	{
		if (empty($media)) {
			$model = Sites::find()->orderBy('name')->all();
			echo $this->render('medias', ['model' => $model]);
		} else {
			$model = Sites::findOne(['domain' => $media]);
			echo $this->render('mediaDetail', ['model' => $model]);
		}
	}
} 