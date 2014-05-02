<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%words_compare}}".
 *
 * @property integer $id
 * @property integer $link_first_id
 * @property integer $word_first_id
 * @property integer $link_second_id
 * @property integer $word_second_id
 * @property string $type
 * @property string $compare
 * @property string $added
 *
 * @property Links $linkSecond
 * @property Links $linkFirst
 * @property Words $wordFirst
 * @property Words $wordSecond
 */
class WordsCompare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%words_compare}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link_first_id', 'word_first_id', 'link_second_id', 'word_second_id', 'type', 'compare', 'added'], 'required'],
            [['link_first_id', 'word_first_id', 'link_second_id', 'word_second_id'], 'integer'],
            [['compare'], 'number'],
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
            'link_first_id' => 'Link First ID',
            'word_first_id' => 'Word First ID',
            'link_second_id' => 'Link Second ID',
            'word_second_id' => 'Word Second ID',
            'type' => 'Type',
            'compare' => 'Compare',
            'added' => 'Added',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinkSecond()
    {
        return $this->hasOne(Links::className(), ['id' => 'link_second_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinkFirst()
    {
        return $this->hasOne(Links::className(), ['id' => 'link_first_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWordFirst()
    {
        return $this->hasOne(Words::className(), ['id' => 'word_first_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWordSecond()
    {
        return $this->hasOne(Words::className(), ['id' => 'word_second_id']);
    }
}
