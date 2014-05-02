<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%words_links}}".
 *
 * @property integer $id
 * @property integer $word_id
 * @property integer $link_id
 * @property string $type
 * @property string $added
 *
 * @property Links $link
 * @property Words $word
 */
class WordsLinks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%words_links}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['word_id', 'link_id', 'type', 'added'], 'required'],
            [['word_id', 'link_id'], 'integer'],
            [['added'], 'safe'],
            [['type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word_id' => 'Word ID',
            'link_id' => 'Link ID',
            'type' => 'Type',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWord()
    {
        return $this->hasOne(Words::className(), ['id' => 'word_id']);
    }
}
