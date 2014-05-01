<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "{{%sites}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $domain
 * @property string $added
 *
 * @property Links[] $links
 */
class Sites extends \yii\db\ActiveRecord
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
        return '{{%sites}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'domain', 'added'], 'required'],
            [['added'], 'safe'],
            [['name', 'domain'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'domain' => 'Domain',
            'added' => 'Added',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Links::className(), ['site_id' => 'id']);
    }
}
