<?php
/**
 * @var yii\web\View $this
 * @var \app\models\Links $model
 * @var \app\models\Links $media
 */
$this->title = Yii::$app->name;
echo '<h1>Самые популярные репортажи:</h1>';
echo '<ul class="medias">';
foreach ($model as $media) {
	echo '<li style="display: inline-block">';
	echo '<a href="' . $media->link . '" target="_blank" title="' . $media->news_title . '""><h2>' . $media->news_title . '</h2></a>' . (!empty($media->news_pic) ? '<img style="margin-right: 10px;" src="' . $media->news_pic . '" alt="' . $media->news_title . '" align="left">' : '');
	if (!empty($media->news_description)) {
		echo $media->news_description . '&nbsp;<a href="' . $media->link . '" target="_blank" title="' . $media->news_title . '"">Подробнее</a><br><br>';
	}
	echo 'Источник: <a href="' . $media->link . '" target="_blank">' . $media->site->name . '&nbsp;<img src="//favicon.yandex.net/favicon/' . $media->site->domain . '" / class="list"></a><br>';
	echo $media->news_total_shares . ' упоминаний: ';
	echo '<img src="//s1.static.twijournal.com/main/img/icon-tw-12-gray.png">&nbsp;' . $media->news_tw_shares;
	echo '&nbsp;<img src="//s2.static.twijournal.com/main/img/icon-vk-12-gray.png">&nbsp;' . $media->news_vk_shares;
	echo '&nbsp;<img src="//s3.static.twijournal.com/main/img/icon-fb-12-gray.png">&nbsp;' . $media->news_fb_shares . '&nbsp;';
	echo '</li>';
}
echo '</ul>';