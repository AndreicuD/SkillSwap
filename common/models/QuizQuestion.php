<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "quiz_question".
 *
 * @property integer $id [int(11)]
 * @property integer $quiz_id [int(11)]
 * @property string $text [text]
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

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['quiz_id', 'text'], 'required'],
            [['quiz_id'], 'integer'],
            [['text'], 'string', 'max' => 1000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'quiz_id' => Yii::t('app', 'Quiz ID'),
            'text' => Yii::t('app', 'Question Text'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getQuiz()
    {
        return $this->hasOne(Quiz::class, ['id' => 'quiz_id']);
    }

    /**
     * Gets the choices for this question.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChoices()
    {
        return $this->hasMany(QuizChoice::class, ['question_id' => 'id'])->orderBy(['id' => SORT_ASC]);
    }

    public static function findByQuizId($quizId)
    {
        return self::find()
            ->where(['quiz_id' => $quizId])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }
}
