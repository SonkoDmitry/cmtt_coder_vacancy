<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%words}}".
 *
 * @property integer $id
 * @property string $word
 * @property string $added
 *
 * @property WordsCompare[] $wordsCompares
 * @property WordsLinks $wordsLinks
 */
class Words extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%words}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['word', 'added'], 'required'],
            [['added'], 'safe'],
            [['word'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word' => 'Word',
            'added' => 'Added',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWordsCompares()
    {
        return $this->hasMany(WordsCompare::className(), ['word_second_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWordsLinks()
    {
        return $this->hasOne(WordsLinks::className(), ['word_id' => 'id']);
    }
}
