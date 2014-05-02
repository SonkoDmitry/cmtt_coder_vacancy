<?php

namespace app\commands;

use app\models\Links;
use app\models\Words;
use app\models\WordsCompare;
use yii\db\Expression;

class CompareWordsController extends \yii\console\Controller
{
	public function actionIndex()
	{
		//2 получаем список слов, используемых в этой записи
		//2.1 отдельно сравниваем слова из тайтла со словами из тайтла
		//2.2 отдельно сравниванием слова из тайтла со словами из деска
		//2.3 отдельно слова из деска со словами из тайтла
		//2.4 отдельно слова из деска со слова из деска


		//1 получили список всех не обработанных ссылок
		/**
		 * @var Links $link
		 */
		foreach (Links::find()->where(['completed' => 0])->all() as $link) {
			//берем все слова из тайтла текущей новости
			foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
					$query->andWhere("link_id=" . $link->id . "");
					$query->andWhere("type='title'");
				}])->all() as $firstWordTitle) {
				/**
				 * @var Words $firstWordTitle
				 */
				//2.1 сравниваем слова из тайтла со словами из тайтла других новостей
				foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
						$query->andWhere("link_id!=" . $link->id . "");
						$query->andWhere("type='title'");
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
					$record->type = 'title_title';
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
					$record->type = 'title_title';
					$record->compare = round($percent, 2);
					$record->added = new Expression('NOW()');
					$record->save();
				}
				//2.1 сравниваем слова из тайтла со словами из деска других новостей
				foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
						$query->andWhere("link_id!=" . $link->id . "");
						$query->andWhere("type='description'");
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
					$record->type = 'title_description';
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
					$record->type = 'description_title';
					$record->compare = round($percent, 2);
					$record->added = new Expression('NOW()');
					$record->save();
				}
			}

			//берем все слова из деска текущей новости
			foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
					$query->andWhere("link_id=" . $link->id . "");
					$query->andWhere("type='description'");
				}])->all() as $firstWordTitle) {
				/**
				 * @var Words $firstWordTitle
				 */
				//2.3 сравниваем слова из деска со словами из тайтла других новостей
				foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
						$query->andWhere("link_id!=" . $link->id . "");
						$query->andWhere("type='title'");
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
					$record->type = 'description_title';
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
					$record->type = 'title_description';
					$record->compare = round($percent, 2);
					$record->added = new Expression('NOW()');
					$record->save();
				}
				//2.4 сравниваем слова из декска со словами из деска других новостей
				foreach (Words::find()->joinWith(['wordsLinks' => function ($query) use ($link) {
						$query->andWhere("link_id!=" . $link->id . "");
						$query->andWhere("type='description'");
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
					$record->type = 'description_description';
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
					$record->type = 'description_description';
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