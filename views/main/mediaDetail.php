<?php
/**
 * @var yii\web\View $this
 * @var \app\models\Sites $model
 */

use app\models\Links;

if (!$model) {

	$this->title = Yii::$app->name . ' - Медиаресурсы - Медиаресурс не найден';
	$this->params['breadcrumbs'][] = ['label' => 'Медиаресурсы', 'url' => '/medias'];
	$this->params['breadcrumbs'][] = 'Медиаресурс не найден';
	echo '<h1>К сожалению, медиаресурс не найден</h1>';
} else {
	$this->title = Yii::$app->name . ' - Медиаресурсы - ' . $model->name;
	$this->params['breadcrumbs'][] = ['label' => 'Медиаресурсы', 'url' => '/medias'];
	$this->params['breadcrumbs'][] = $model->name;
	echo '<h1>Самые популярные репортажи с этого медиаресурса:</h1>';
	echo '<ul class="medias">';
	foreach (Links::find()->where(['site_id' => $model->id])->orderBy('news_total_shares DESC')->all() as $media) {
		echo '<li style="display: inline-block">';
		echo '<a href="' . $media->link . '" target="_blank" title="' . $media->news_title . '""><h2>' . $media->news_title . '</h2></a>'.(!empty($media->news_pic) ? '<img style="margin-right: 10px;" src="' . $media->news_pic . '" alt="' . $media->news_title . '" align="left">' : '');
		if (!empty($media->news_description)) {
			echo $media->news_description . '&nbsp;<a href="' . $media->link . '" target="_blank" title="' . $media->news_title . '"">Подробнее</a><br><br>';
		}
		echo $media->news_total_shares . ' упоминаний: ';
		echo '<img src="//s1.static.twijournal.com/main/img/icon-tw-12-gray.png">&nbsp;' . $media->news_tw_shares;
		echo '&nbsp;<img src="//s2.static.twijournal.com/main/img/icon-vk-12-gray.png">&nbsp;' . $media->news_vk_shares;
		echo '&nbsp;<img src="//s3.static.twijournal.com/main/img/icon-fb-12-gray.png">&nbsp;' . $media->news_fb_shares . '&nbsp;';
		echo '</li>';
	}
	echo '</ul>';
}
