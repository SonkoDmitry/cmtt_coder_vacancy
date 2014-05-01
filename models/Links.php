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
 * @property string $added
 *
 * @property Sites $site
 */
class Links extends \yii\db\ActiveRecord
{
	public function init()
	{
		parent::init();
		$this->on(self::EVENT_BEFORE_VALIDATE, function () {
			if (empty($this->added)) {
				$this->added = new Expression('NOW()');
			}
		});
	}

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
			[['link' , 'added'], 'required'],
			[['site_id'], 'integer'],
			[['added'], 'safe'],
			[['link'], 'string', 'max' => 255],
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
			'added' => 'Added',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSite()
	{
		return $this->hasOne(Sites::className(), ['id' => 'site_id']);
	}
}
