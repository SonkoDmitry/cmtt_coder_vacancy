<?php

namespace app\commands;

use app\models\Links;
use app\models\Words;
use app\models\WordsCompare;
use yii\db\Expression;

class CompareWordsController extends \yii\console\Controller
{
	/**
	 * @param int $limit Count records for compares
	 */
	public function actionIndex($limit = 10, $completed = 0)
	{
		/*var_dump($limit);
		var_dump($completed);
		die;*/
		$linksModel = Links::find();
		if ($completed !== '') {
			$linksModel->where(['completed' => $completed]);
		}
		if ($limit > 0) {
			$linksModel->limit($limit);
		}
		$linksModel = $linksModel->all();
		/**
		 * @var Links $link
		 * @var Words $firstWordTitle
		 * @var Words $secondWordTitle
		 */
		foreach ($linksModel as $link) {
			//берем все слова из текущей новости
			foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
					$query->andWhere("link_id=" . $link->id . "");
					//$query->andWhere("type='title'");
				}])->all() as $firstWordTitle) {
				//echo "Compare word: ".$firstWordTitle->word." (".$firstWordTitle->id."), link_id: ".$firstWordTitle->wordsLinks->link_id."\n";
				/**
				 * @var Words $firstWordTitle
				 */
				//2 сравниваем слова текущей новости со словами из других новостей
				foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link, $firstWordTitle) {
						$query->andWhere("link_id!=" . $link->id . "");
						$query->andWhere("word_id=" . $firstWordTitle->id . "");
						//$query->andWhere("type='title'");
					}])->all() as $secondWordTitle) {
					//echo "Finded in link: ".$secondWordTitle->wordsLinks->link_id."\n";
					$sqlCheckFirst = "SELECT 1
FROM " . WordsCompare::tableName() . "
WHERE
	`link_first_id`={$firstWordTitle->wordsLinks->link_id} AND
	`word_first_id`={$firstWordTitle->id} AND
	`link_second_id`={$secondWordTitle->wordsLinks->link_id} AND
	`word_second_id`={$secondWordTitle->id}";

					$checkFirst = \Yii::$app->db->createCommand($sqlCheckFirst)->queryScalar();
					if ($checkFirst === false) {
						$record = new WordsCompare();
						$record->link_first_id = $firstWordTitle->wordsLinks->link_id;
						$record->word_first_id = $firstWordTitle->id;
						$record->link_second_id = $secondWordTitle->wordsLinks->link_id;
						$record->word_second_id = $secondWordTitle->id;
						$record->type = 'custom';
						$record->compare = 100;
						$record->added = new Expression('NOW()');
						$record->save();
					}

					$sqlCheckSecond = "SELECT 1
FROM " . WordsCompare::tableName() . "
WHERE
	`link_first_id`={$secondWordTitle->wordsLinks->link_id} AND
	`word_first_id`={$secondWordTitle->id} AND
	`link_second_id`={$firstWordTitle->wordsLinks->link_id} AND
	`word_second_id`={$firstWordTitle->id}";

					$checkFirst = \Yii::$app->db->createCommand($sqlCheckSecond)->queryScalar();
					if ($checkFirst === false) {
						$record = new WordsCompare();
						$record->link_first_id = $secondWordTitle->wordsLinks->link_id;
						$record->word_first_id = $secondWordTitle->id;
						$record->link_second_id = $firstWordTitle->wordsLinks->link_id;
						$record->word_second_id = $firstWordTitle->id;
						$record->type = 'custom';
						$record->compare = 100;
						$record->added = new Expression('NOW()');
						$record->save();
					}
				}
			}
			$link->completed = 1;
			$link->save();
		}
	}
} 