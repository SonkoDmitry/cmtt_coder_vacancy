<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "{{%links}}".
 *
 * @property integer $id
 * @property string $link
 * @property integer $site_id
 * @property string $news_title
 * @property string $news_description
 * @property string $news_pic
 * @property integer $news_vk_shares
 * @property integer $news_fb_shares
 * @property integer $news_tw_shares
 * @property integer $news_total_shares
 * @property string $added
 * @property string $updated
 *
 * @property Sites $site
 * @property Logs[] $logs
 */
class Links extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%links}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['link', 'added', 'updated'], 'required'],
			[['site_id', 'news_vk_shares', 'news_fb_shares', 'news_tw_shares', 'news_total_shares'], 'integer'],
			[['news_description', 'news_pic'], 'string'],
			[['added', 'updated'], 'safe'],
			[['link', 'news_title'], 'string', 'max' => 255],
			[['link'], 'unique'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'link' => 'Link',
			'site_id' => 'Site ID',
			'news_title' => 'News Title',
			'news_description' => 'News Description',
			'news_pic' => 'News Pic',
			'news_vk_shares' => 'News Vk Shares',
			'news_fb_shares' => 'News Fb Shares',
			'news_tw_shares' => 'News Tw Shares',
			'news_total_shares' => 'News Total Shares',
			'added' => 'Added',
			'updated' => 'Updated',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSite()
	{
		return $this->hasOne(Sites::className(), ['id' => 'site_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLogs()
	{
		return $this->hasMany(Logs::className(), ['link_id' => 'id']);
	}
}
