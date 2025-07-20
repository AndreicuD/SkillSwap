<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "quiz_choice".
 *
 * @property integer $id [int(11)]
 * @property integer $question_id [int(11)]
 * @property string $text [text]
 * @property integer $correct [tinyint(1)]
 * @property string $created_at [datetime]
 * @property string $updated_at [timestamp]
 *
 * @property QuizQuestion $question
 */
class QuizChoice extends ActiveRecord
{
    public static function tableName()
    {
        return 'quiz_choice';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['question_id', 'text', 'correct'], 'required'],
            [['question_id', 'correct'], 'integer'],
            [['text'], 'string', 'max' => 1000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'text' => Yii::t('app', 'Choice Text'),
            'correct' => Yii::t('app', 'Is Correct'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getQuestion()
    {
        return $this->hasOne(QuizQuestion::class, ['id' => 'question_id']);
    }

    public static function findByQuestionId($questionId)
    {
        return self::find()
            ->where(['question_id' => $questionId])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }
}
