<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%logs}}".
 *
 * @property integer $id
 * @property integer $link_id
 * @property integer $news_vk_shares
 * @property integer $news_fb_shares
 * @property integer $news_tw_shares
 * @property integer $news_total_shares
 * @property string $added
 *
 * @property Links $link
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%logs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['link_id', 'added'], 'required'],
            [['link_id'], 'required'],
            [['link_id', 'news_vk_shares', 'news_fb_shares', 'news_tw_shares', 'news_total_shares'], 'integer'],
            [['added'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link_id' => 'Link ID',
            'news_vk_shares' => 'News Vk Shares',
            'news_fb_shares' => 'News Fb Shares',
            'news_tw_shares' => 'News Tw Shares',
            'news_total_shares' => 'News Total Shares',
            'added' => 'Added',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'link_id']);
    }
}
