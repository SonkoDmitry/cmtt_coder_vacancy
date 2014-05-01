<?php

namespace app\commands;

use app\models\Links;
use app\models\Logs;
use yii\db\Expression;

/**
 * Parse shares for links and recalculate total values
 *
 * @package app\commands
 */
class SharesParserController extends \yii\console\Controller
{
	/**
	 * @param int $limit Count records for update
	 */
	public function actionIndex($limit = 10)
	{
		if (($limit = (int)$limit) < 1) {
			$limit = 1;
		}
		$total = 0;
		/**
		 * @var Links $link
		 */
		foreach (Links::find()->orderBy('updated')->limit($limit)->all() as $link) {
			$totalShares = 0;

			$vkShares = 0;
			$matches = [];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://vk.com/share.php?act=count&index=1&url=' . $link->link);
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
			curl_setopt($ch, CURLOPT_URL, 'http://graph.facebook.com/?ids=' . $link->link);
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
				$fbShares = intval($fbJson[$link->link]['shares']);
			}

			$twShares = 0;
			$matches = [];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://urls.api.twitter.com/1/urls/count.json?url=' . $link->link);
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

			$link->news_vk_shares = $vkShares;
			$link->news_fb_shares = $fbShares;
			$link->news_tw_shares = $twShares;
			$link->news_total_shares = $vkShares + $fbShares + $twShares;
			$link->updated = new Expression('NOW()');
			$link->update();

			$log = new Logs();
			$log->link_id = $link->id;
			$log->news_vk_shares = $vkShares;
			$log->news_fb_shares = $fbShares;
			$log->news_tw_shares = $twShares;
			$log->news_total_shares = $vkShares + $fbShares + $twShares;
			$log->added = new Expression('NOW()');
			$log->save();
		}
	}
}