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
		if ($completed == '') {
			$linksModel = Links::find()->limit($limit)->all();
		} else {
			$linksModel = Links::find()->where(['completed' => $completed])->limit($limit)->all();
		}
		if (($limit = (int)$limit) < 1) {
			$limit = 1;
		}
		/**
		 * @var Links $link
		 */
		foreach ($linksModel as $link) {
			//берем все слова из текущей новости
			foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
					$query->andWhere("link_id=" . $link->id . "");
					//$query->andWhere("type='title'");
				}])->all() as $firstWordTitle) {
				/**
				 * @var Words $firstWordTitle
				 */
				//2 сравниваем слова текущей новости со словами из других новостей
				foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
						$query->andWhere("link_id!=" . $link->id . "");
						//$query->andWhere("type='title'");
					}])->all() as $secondWordTitle) {
					/**
					 * @var Words $secondWordTitle
					 */
					$percent = 0;
					$result = similar_text($firstWordTitle->word, $secondWordTitle->word, $percent);
					$record = new WordsCompare();
					$record->link_first_id = $link->id;
					$record->word_first_id = $firstWordTitle->id;
					$record->link_second_id = $secondWordTitle->wordsLinks->link_id;
					$record->word_second_id = $secondWordTitle->id;
					$record->type = 'custom';
					$record->compare = round($percent, 2);
					$record->added = new Expression('NOW()');
					$record->save();

					$percent = 0;
					$result = similar_text($secondWordTitle->word, $firstWordTitle->word, $percent);
					$record = new WordsCompare();
					$record->link_first_id = $secondWordTitle->wordsLinks->link_id;
					$record->word_first_id = $secondWordTitle->id;
					$record->link_second_id = $link->id;
					$record->word_second_id = $firstWordTitle->id;
					$record->type = 'custom';
					$record->compare = round($percent, 2);
					$record->added = new Expression('NOW()');
					$record->save();
				}
			}
			$link->completed = 1;
			$link->save();
		}
	}
} 