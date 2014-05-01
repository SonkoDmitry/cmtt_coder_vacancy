<?php
/**
 * @var yii\web\View $this
 * @var \app\models\Links $model
 */
$this->title = Yii::$app->name;
echo '<h1>Самые популярные репортажи:</h1>';
echo '<ul class="medias">';
foreach ($model as $media){
	echo '<li>';
	echo (!empty($media->news_pic) ? '<img src="' . $media->news_pic . '" alt="' . $media->news_title . '" align="">' : '') . '<a href="' . $media->link . '" target="_blank" title="' . $media->news_title . '""><h2>' . $media->news_title . '</h2></a>';
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