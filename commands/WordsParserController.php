<?php

namespace app\commands;

use app\models\Links;
use app\models\Words;
use app\models\WordsLinks;
use yii\db\Expression;

class WordsParserController extends \yii\console\Controller
{
	public function actionIndex()
	{
		$okonch = [
			'ий',
			'ов',
			'ей',
			'ев',
			'иев',
			'ам',
			'ям',
			'иям',
			'ом',
			'ем',
			'ами',
			'ями',
			'ах',
			'ях',
			'ый',
			'ая',
			'ое',
			'ые',
			'ого',
			'ой',
			'ых',
			'ому',
			'ым',
			'ыми',
			'ий',
			'яя',
			'ее',
			'ие',
			'его',
			'ей',
			'их',
			'ему',
			'им',
			'ешь',
			'ет',
			'ем',
			'ете',
			'ут',
			'ют',
			'ишь',
			'ит',
			'им',
			'ите',
			'ат',
			'ят',
		];
		$regex = '/([' . implode('|', array_unique($okonch)) . ']|[уеаоэяию])*$/i';
		/**
		 * @var $model Links
		 */
		foreach (Links::find()->all() as $model) {
			$link_title = mb_strtolower($model->news_title, 'UTF-8');
			$link_title = preg_replace('/(&[a-z#0-9]+?;)+/u', '', $link_title);
			$link_title = preg_replace('/([^a-zа-я0-9\s-])/u', '', $link_title);
			$titles = explode(' ', mb_strtolower($link_title, 'UTF-8'));
			foreach ($titles as $title) {
				$title = preg_replace($regex, '', $title);
				if (mb_strlen($title, 'UTF-8') < 3) {
					continue;
				}
				$searchedWord = \Yii::$app->db->createCommand("SELECT `id` FROM " . Words::tableName() . " WHERE `word`='" . $title . "'")->queryScalar();
				if ($searchedWord !== false) {
					$searchedWordId = $searchedWord;
				} else {
					$wordsModel = new Words();
					$wordsModel->word = $title;
					$wordsModel->added = new Expression('NOW()');
					if ($wordsModel->save()) {
						$searchedWordId = $wordsModel->id;
					} else {
						continue;
					}
				}

				$wordsLinksModel = new WordsLinks();
				$wordsLinksModel->word_id = $searchedWordId;
				$wordsLinksModel->link_id = $model->id;
				$wordsLinksModel->type = 'title';
				$wordsLinksModel->added = new Expression('NOW()');
				$wordsLinksModel->save();
			}

			$link_desc = mb_strtolower($model->news_description, 'UTF-8');
			$link_desc = preg_replace('/(&[a-z#0-9]+?;)+/u', '', $link_desc);
			$link_desc = preg_replace('/([^a-zа-я0-9\s-])/u', '', $link_desc);
			$descs = explode(' ', mb_strtolower($link_desc, 'UTF-8'));
			foreach ($descs as $desc) {
				$desc = preg_replace($regex, '', $desc);
				if (mb_strlen($desc, 'UTF-8') < 3) {
					continue;
				}
				$searchedWord = \Yii::$app->db->createCommand("SELECT `id` FROM " . Words::tableName() . " WHERE `word`='" . $desc . "'")->queryScalar();
				if ($searchedWord !== false) {
					$searchedWordId = $searchedWord;
				} else {
					$wordsModel = new Words();
					$wordsModel->word = $desc;
					$wordsModel->added = new Expression('NOW()');
					if ($wordsModel->save()) {
						$searchedWordId = $wordsModel->id;
					} else {
						continue;
					}
				}

				$wordsLinksModel = new WordsLinks();
				$wordsLinksModel->word_id = $searchedWordId;
				$wordsLinksModel->link_id = $model->id;
				$wordsLinksModel->type = 'description';
				$wordsLinksModel->added = new Expression('NOW()');
				$wordsLinksModel->save();
			}
		}
	}
} 