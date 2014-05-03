<?php
/**
 * @var yii\web\View $this
 * @var \app\models\Links $head
 * @var \app\models\Links $source
 */
use \app\models\Links;

$this->title = Yii::$app->name;
echo '<h1>Самые популярные репортажи:</h1>';
echo '<ul class="medias">';
foreach ($renderNews as $element) {
	echo '<li style="display: inline-block">';
	$head = Links::findOne(['id' => $element['topId']]);
	echo '<a href="' . $head->link . '" target="_blank" title="' . $head->news_title . '""><h2>' . $head->news_title . '</h2></a>' . (!empty($head->news_pic) ? '<img style="margin-right: 10px;" src="' . $head->news_pic . '" alt="' . $head->news_title . '" align="left">' : '');
	echo 'Источник: <a href="' . $head->link . '" target="_blank">' . $head->site->name . '&nbsp;<img src="//favicon.yandex.net/favicon/' . $head->site->domain . '" / class="list"></a><br>Всего ' . $element['total'] . ' упоминаний в ' . (count($element['news']) + 1) . ' источниках включая этот<br>';
	echo '<img src="//s1.static.twijournal.com/main/img/icon-tw-12-gray.png">&nbsp;' . $head->news_tw_shares;
	echo '&nbsp;<img src="//s2.static.twijournal.com/main/img/icon-vk-12-gray.png">&nbsp;' . $head->news_vk_shares;
	echo '&nbsp;<img src="//s3.static.twijournal.com/main/img/icon-fb-12-gray.png">&nbsp;' . $head->news_fb_shares . '&nbsp;<br><br>';
	if (!empty($head->news_description)) {
		echo $head->news_description . '&nbsp;<a href="' . $head->link . '" target="_blank" title="' . $head->news_title . '"">Подробнее</a><br><br>';
	}
	if (count($element['news'])) {
		echo '<h3>Похожее:</h3>';
		foreach ($element['news'] as $sourceId => $sourceCnt) {
			$source = Links::findOne(['id' => $sourceId]);
			echo '<img src="//favicon.yandex.net/favicon/' . $source->site->domain . '" / class="list"><a href="' . $source->link . '" title="' . $source->news_title . '">' . $source->news_title . '</a><br>';
			echo '<img src="//s1.static.twijournal.com/main/img/icon-tw-12-gray.png">&nbsp;' . $source->news_tw_shares;
			echo '&nbsp;<img src="//s2.static.twijournal.com/main/img/icon-vk-12-gray.png">&nbsp;' . $source->news_vk_shares;
			echo '&nbsp;<img src="//s3.static.twijournal.com/main/img/icon-fb-12-gray.png">&nbsp;' . $source->news_fb_shares . '&nbsp;<br><br>';
			//$source
		}
	}
	echo '</li>';
}
echo '</ul>';