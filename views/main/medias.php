<?php
/**
 * @var yii\web\View $this
 * @var \app\models\Sites $model
 * @var \app\models\Sites $site
 */
$this->title = Yii::$app->name . ' - Медиаресурсы';
$this->params['breadcrumbs'][] = 'Медиаресурсы';

echo '<ul class="medias">';
foreach ($model as $site) {
	echo '<li><img src="//favicon.yandex.net/favicon/' . $site->domain . '" / class="list"><a href="' . \Yii::$app->urlManager->createUrl('medias/'.$site->domain) . '" title="' . $site->name . '">' . $site->name . '</a></li>';
}
echo '</ul>';