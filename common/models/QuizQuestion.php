<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "quiz_question".
 *
 * @property integer $id [int(11)]
 * @property integer $quiz_id [int(11)]
 * @property string $text [text]
 * @property int $choices [int(11)]
 * @property string $created_at [datetime]
 * @property string $updated_at [timestamp]
 *
 * @property Quiz $quiz
 */
class QuizQuestion extends ActiveRecord
{
    public static function tableName()
    {
        return 'quiz_question';
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
            [['quiz_id', 'text', 'choices'], 'required'],
            [['quiz_id', 'choices'], 'integer'],
            [['text'], 'string', 'max' => 1000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'quiz_id' => Yii::t('app', 'Quiz ID'),
            'text' => Yii::t('app', 'Question Text'),
            'choices' => Yii::t('app', 'Number of Choices'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getQuiz()
    {
        return $this->hasOne(Quiz::class, ['id' => 'quiz_id']);
    }

    public static function findByQuizId($quizId)
    {
        return self::find()
            ->where(['quiz_id' => $quizId])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }
}
