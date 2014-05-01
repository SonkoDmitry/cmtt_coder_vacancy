<?php


namespace app\commands;

use app\models\Links;
use app\models\Sites;

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
		/*$urlFirst = 'http://lifenews.ru/news/131712';
		$url = parse_url($urlFirst);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url['scheme'] . '://' . $url['host']);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.102 YaBrowser/14.2.1700.12506 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.


		$siteContent = curl_exec($ch);
		curl_close($ch);
		$matches = [];
		if (preg_match('/property=[\"\']og:site_name[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sproperty=[\"\']og:site_name[\"\']/i', $siteContent, $matches)) {
			$siteName = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : $url['host']);
			if (mb_convert_encoding($siteName, 'UTF-8', 'UTF-8') !== $siteName) {
				$siteName = mb_convert_encoding($siteName, 'UTF-8', 'WINDOWS-1251');
			}
			echo "Parsed from <og:site_name>\n";
			echo $siteName . "\n\n";
		} elseif (preg_match('/<title>\s*(.*)\s*<\/title>/i', $siteContent, $matches)) {
			$siteName = $matches[1];
			if (mb_convert_encoding($siteName, 'UTF-8', 'UTF-8') !== $siteName) {
				$siteName = mb_convert_encoding($matches[1], 'UTF-8', 'WINDOWS-1251');
			}
			echo "Parsed from <title>\n";
			echo $siteName . "\n\n";
		} else {
			$siteName = $url['host'];
		}
		//echo "Site name: " . $siteName . "\n\n";



		die;*/
		if (($needLinks = (int)$needLinks) < 1) {
			$needLinks = 1;
		}
		for ($i = 1; $i <= $needLinks; $i++) {
			$result = file_get_contents('https://api.tjournal.ru/1/vacancy');
			if (false !== ($result = json_decode($result)) && false !== ($url = parse_url($result->url))) {
				$model = new Links();
				$model->link = $result->url;
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
				if (preg_match('/property=[\"\']og:title[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sproperty=[\"\']og:title[\"\']/i', $newsContent, $matches)) {
					$newsTitle = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsTitle, 'UTF-8', 'UTF-8') !== $newsTitle) {
						$newsTitle = mb_convert_encoding($newsTitle, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News title: " . $newsTitle . "\n";
					echo "Parsed from <og:title>\n\n";*/
				} elseif (preg_match('/property=[\"\']vk:title[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sproperty=[\"\']vk:title[\"\']/i', $newsContent, $matches)) {
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
				if (preg_match('/property=[\"\']og:description[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sproperty=[\"\']og:description[\"\']/i', $newsContent, $matches)) {
					$newsDescription = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsDescription, 'UTF-8', 'UTF-8') !== $newsDescription) {
						$newsDescription = mb_convert_encoding($newsDescription, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News description: " . $newsDescription . "\n";
					echo "Parsed from <og:description>\n\n";*/
				} elseif (preg_match('/property=[\"\']vk:description[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sproperty=[\"\']vk:description[\"\']/i', $newsContent, $matches)) {
					$newsDescription = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsDescription, 'UTF-8', 'UTF-8') !== $newsDescription) {
						$newsDescription = mb_convert_encoding($newsDescription, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News description: " . $newsDescription . "\n";
					echo "Parsed from <vk:description>\n\n";*/
				} elseif (preg_match('/name=[\"\']description[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sname=[\"\']description[\"\']/i', $newsContent, $matches)) {
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
				if (preg_match('/property=[\"\']og:image[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sproperty=[\"\']og:image[\"\']/i', $newsContent, $matches)) {
					$newsPic = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsPic, 'UTF-8', 'UTF-8') !== $newsPic) {
						$newsPic = mb_convert_encoding($newsPic, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News pic: " . $newsPic . "\n";
					echo "Parsed from <og:image>\n\n";*/
				} elseif (preg_match('/name=[\"\']description[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sname=[\"\']description[\"\']/i', $newsContent, $matches)) {
					$newsPic = (isset($matches['content']) && !empty($matches['content'])) ? $matches['content'] : (isset($matches['content2']) && !empty($matches['content2']) ? $matches['content2'] : '');
					if (mb_convert_encoding($newsPic, 'UTF-8', 'UTF-8') !== $newsPic) {
						$newsPic = mb_convert_encoding($newsPic, 'UTF-8', 'WINDOWS-1251');
					}
					/*echo "News pic: " . $newsPic . "\n";
					echo "Parsed from <link image_src>\n\n";*/
				}
				$model->news_pic = $newsPic;

				if ($model->validate()) {
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
						if (preg_match('/property=[\"\']og:site_name[\"\']\scontent\s*=\s*(?P<quote>[\"\'])(?P<content>.*?)\k<quote>|content\s*=\s*(?P<quote2>[\"\'])(?P<content2>.*?)\k<quote2>\sproperty=[\"\']og:site_name[\"\']/i', $siteContent, $matches)) {
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
					$model->save();
				}
			}
		}

		return 0;
	}
} 