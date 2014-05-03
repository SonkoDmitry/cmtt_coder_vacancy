<?php

namespace app\controllers;

use app\models\Links;
use app\models\Sites;
use app\models\WordsCompare;

class MainController extends \yii\web\Controller
{
	public function actionIndex()
	{
		//$model = Links::find()->orderBy('news_total_shares DESC')->all();
		$news = [];
		/**
		 * @var Links $link
		 * @var WordsCompare $result
		 */
		foreach (Links::find()->all() as $link) {
			$results = WordsCompare::find()->where(['link_first_id' => $link->id])->groupBy('link_second_id')->orderBy('link_first_id')->all();
			$input = [];
			foreach ($results as $result) {
				$count = WordsCompare::find()->where([
					'link_first_id' => $result->link_first_id,
					'link_second_id' => $result->link_second_id
				])->count();
				if ($count < 4) {
					continue;
				}
				$input[] = $result->link_second_id;
			}
			if (count($input)) {
				$input[] = $link->id;
				$news[] = $input;
			}
		}
		//echo '<pre>';
		$renderNews = [];
		/*$test=[
			5=>56,
			8=>13,
			1=>22,
		];
		arsort($test,SORT_NUMERIC);
		var_dump($test);
		die;*/
		//var_dump($news);
		$renderNews = [];
		$topIds = [];
		foreach ($news as $vals) {
			$topId = 0;
			$maxShares = 0;
			$news = [];
			$result = [
				'topId' => 0,
				'total' => 0,
				'news' => [],
			];
			foreach ($vals as $val) {
				$cnt = Links::findOne(['id' => $val])->news_total_shares;
				$result['total'] += $cnt;
				$news[$val] = $cnt;
				if ($cnt >= $maxShares) {
					$maxShares = $cnt;
					$topId = $val;
				}
			}
			if (!in_array($topId, $topIds)) {
				unset($news[$topId]);
				$result['topId'] = $topId;
				arsort($news, SORT_NUMERIC);
				$result['news'] = $news;
				$topIds[] = $topId;
				$renderNews[] = $result;
			}
		}
		//var_dump($renderNews);
		usort($renderNews, [$this, 'compareTotal']);
		//var_dump($renderNews);
		echo $this->render('index', ['renderNews' => $renderNews]);
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

	public function actionAllNews()
	{
		$model = Links::find()->orderBy('news_total_shares DESC')->all();
		echo $this->render('all_news', ['model' => $model]);
	}

	protected function compareTotal($a, $b)
	{
		if ($a['total'] == $b['total']) {
			return 0;
		}
		return ($a['total'] > $b['total']) ? -1 : 1;
	}
}