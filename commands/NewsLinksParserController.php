<?php


namespace app\commands;

use app\models\Links;
use app\models\Words;
use app\models\WordsLinks;
use app\models\Sites;
use yii\db\Expression;

/**
 * Parser links to news from cmtt public API
 *
 * @package app\commands
 */
class NewsLinksParserController extends \yii\console\Controller
{
	/**
	 * Run this command with some periodically on cron. It save requested links to DB
	 *
	 * @param int $needLinks How much links need parse in one start script. Minimal value is 1
	 * @return int
	 */
	public function actionIndex($needLinks = 5)
	{
		if (($needLinks = (int)$needLinks) < 1) {
			$needLinks = 1;
		}
		for ($i = 1; $i <= $needLinks; $i++) {
			$result = file_get_contents('https://api.tjournal.ru/1/vacancy');
			if (false !== ($result = json_decode($result)) && false !== ($url = parse_url($result->url))) {
				$command = \Yii::$app->db->createCommand('SELECT 1 FROM ' . Links::tableName() . ' WHERE link=:link LIMIT 1');
				$command->bindValue(':link', $result->url, \PDO::PARAM_STR);
				$check = $command->queryOne();
				if ($check !== false) {
					/*echo "Link with url " . $result->url . " existed\n";*/
					continue;
				}

				$model = new Links();
				$model->link = $result->url;
				$model->added = new Expression('NOW()');
				$model->updated = new Expression('NOW()');
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $result->url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.102 YaBrowser/14.2.1700.12506 Safari/537.36');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


				$newsContent = curl_exec($ch);
				curl_close($ch);

				$newsTitle = '';
				$matches = [];
				if (preg_match('/property=[\"\']og:title[\"\']\s+content\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\s+property=[\"\']og:title[\"\']/i', $newsContent, $matches)) {
					$newsTitle = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsTitle, 'UTF-8', 'UTF-8') !== $newsTitle) {
						$newsTitle = mb_convert_encoding($newsTitle, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News title: " . $newsTitle . "\n";
					echo "Parsed from <og:title>\n\n";*/
				} elseif (preg_match('/property=[\"\']vk:title[\"\']\s+content\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\s+property=[\"\']vk:title[\"\']/i', $newsContent, $matches)) {
					$newsTitle = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsTitle, 'UTF-8', 'UTF-8') !== $newsTitle) {
						$newsTitle = mb_convert_encoding($newsTitle, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News title: " . $newsTitle . "\n";
					echo "Parsed from <vk:title>\n\n";*/
				} elseif (preg_match('/<title>\s*(.*)\s*<\/title>/i', $newsContent, $matches)) {
					$newsTitle = $matches[1];
					if (mb_convert_encoding($newsTitle, 'UTF-8', 'UTF-8') !== $newsTitle) {
						$newsTitle = mb_convert_encoding($matches[1], 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News title: " . $newsTitle . "\n";
					echo "Parsed from <title>\n\n";*/
				}
				$model->news_title = $newsTitle;

				$newsDescription = '';
				$matches = [];
				if (preg_match('/property=[\"\']og:description[\"\']\s+content\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\s+property=[\"\']og:description[\"\']/i', $newsContent, $matches)) {
					$newsDescription = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsDescription, 'UTF-8', 'UTF-8') !== $newsDescription) {
						$newsDescription = mb_convert_encoding($newsDescription, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News description: " . $newsDescription . "\n";
					echo "Parsed from <og:description>\n\n";*/
				} elseif (preg_match('/property=[\"\']vk:description[\"\']\s+content\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\s+property=[\"\']vk:description[\"\']/i', $newsContent, $matches)) {
					$newsDescription = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsDescription, 'UTF-8', 'UTF-8') !== $newsDescription) {
						$newsDescription = mb_convert_encoding($newsDescription, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News description: " . $newsDescription . "\n";
					echo "Parsed from <vk:description>\n\n";*/
				} elseif (preg_match('/name=[\"\']description[\"\']\s+content\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\s+name=[\"\']description[\"\']/i', $newsContent, $matches)) {
					$newsDescription = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsDescription, 'UTF-8', 'UTF-8') !== $newsDescription) {
						$newsDescription = mb_convert_encoding($newsDescription, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News description: " . $newsDescription . "\n";
					echo "Parsed from <title>\n\n";*/
				}
				$model->news_description = $newsDescription;

				$newsPic = '';
				$matches = [];
				if (preg_match('/property=[\"\']og:image[\"\']\s+content\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\s+property=[\"\']og:image[\"\']/i', $newsContent, $matches)) {
					$newsPic = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsPic, 'UTF-8', 'UTF-8') !== $newsPic) {
						$newsPic = mb_convert_encoding($newsPic, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News pic: " . $newsPic . "\n";
					echo "Parsed from <og:image>\n\n";*/
				} elseif (preg_match('/name=[\"\']description[\"\']\s+content\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sname=[\"\']description[\"\']/i', $newsContent, $matches)) {
					$newsPic = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsPic, 'UTF-8', 'UTF-8') !== $newsPic) {
						$newsPic = mb_convert_encoding($newsPic, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News pic: " . $newsPic . "\n";
					echo "Parsed from <link image_src>\n\n";*/
				}
				$model->news_pic = $newsPic;


				$vkShares = 0;
				$matches = [];
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://vk.com/share.php?act=count&index=1&url=' . $model->link);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.102 YaBrowser/14.2.1700.12506 Safari/537.36');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$content = curl_exec($ch);
				curl_close($ch);
				if (preg_match('/^VK.Share.count\(1, (\d+)\);$/i', $content, $matches)) {
					/*var_dump($matches);*/
					$vkShares = intval($matches[1]);
				}

				$fbShares = 0;
				$matches = [];
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://graph.facebook.com/?ids=' . $model->link);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.102 YaBrowser/14.2.1700.12506 Safari/537.36');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$content = curl_exec($ch);
				curl_close($ch);
				if (false !== ($fbJson = json_decode($content, true))) {
					/*var_dump($fbJson);*/
					$fbShares = intval($fbJson[$model->link]['shares']);
				}

				$twShares = 0;
				$matches = [];
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://urls.api.twitter.com/1/urls/count.json?url=' . $model->link);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.102 YaBrowser/14.2.1700.12506 Safari/537.36');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$content = curl_exec($ch);
				curl_close($ch);
				if (false !== ($twJson = json_decode($content))) {
					/*var_dump($fbJson);*/
					$twShares = intval($twJson->count);
				}

				$model->news_vk_shares = $vkShares;
				$model->news_fb_shares = $fbShares;
				$model->news_tw_shares = $twShares;
				$model->news_total_shares = $vkShares + $fbShares + $twShares;

				if ($site = Sites::findOne(['domain' => $url])) {
					$model->site_id = $site->id;
				} else {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url['scheme'] . '://' . $url['host']);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_VERBOSE, 0);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
					curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.102 YaBrowser/14.2.1700.12506 Safari/537.36');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


					$siteContent = curl_exec($ch);
					curl_close($ch);
					$matches = [];
					if (preg_match('/property=[\"\']og:site_name[\"\']\s+content\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\s+property=[\"\']og:site_name[\"\']/i', $siteContent, $matches)) {
						$siteName = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : $url['host']);
						if (mb_convert_encoding($siteName, 'UTF-8', 'UTF-8') !== $siteName) {
							$siteName = mb_convert_encoding($siteName, 'UTF-8', 'WINDOWS-1251');
						}
						/*echo "Parsed from <og:site_name>\n";
						echo $siteName . "\n\n";*/
					} elseif (preg_match('/<title>\s*(.*)\s*<\/title>/i', $siteContent, $matches)) {
						$siteName = $matches[1];
						if (mb_convert_encoding($siteName, 'UTF-8', 'UTF-8') !== $siteName) {
							$siteName = mb_convert_encoding($matches[1], 'UTF-8', 'WINDOWS-1251');
						}
						/*echo "Parsed from <title>\n";
						echo $siteName . "\n\n";*/
					} else {
						$siteName = $url['host'];
					}
					$siteModel = new Sites();
					$siteModel->name = $siteName;
					$siteModel->domain = $url['host'];
					if ($siteModel->validate() && $siteModel->save()) {
						$model->site_id = $siteModel->id;
					}
				}


				//parse words for link
				if ($model->save()) {
					$link_title = mb_strtolower($model->news_title, 'UTF-8');
					$link_title = preg_replace('/(&[a-z#0-9]+?;)+/u', '', $link_title);
					$link_title = preg_replace('/([^a-zа-я0-9\s-])/u', '', $link_title);
					$titles = explode(' ', mb_strtolower($link_title, 'UTF-8'));
					foreach ($titles as $title) {
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

		return 0;
	}
} 