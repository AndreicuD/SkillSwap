<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\User;
use common\models\Course;
use common\models\CourseElement;

/**
 * QuizAttempt model
 *
 * @property integer $id [int(auto increment)]
 * @property integer $user_id [int(11)]
 * @property integer $course_id [int(11)]
 * @property integer $element_id [int(11)]     // course_element.id
 * @property integer $score [int(11)]
 * @property integer $passed [smallint(1)]
 * @property integer $attempted_at [datetime]
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 * @property User $user
 * @property Course $course
 * @property CourseElement $courseElement
 */
class QuizAttempt extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%quiz_attempt}}';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'course_id', 'element_id'], 'required'],
            [['user_id', 'course_id', 'element_id', 'score'], 'integer'],
            [['passed'], 'boolean'],
            [['attempted_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'course_id' => Yii::t('app', 'Course'),
            'element_id' => Yii::t('app', 'Course Element'),
            'score' => Yii::t('app', 'Score'),
            'passed' => Yii::t('app', 'Passed'),
            'attempted_at' => Yii::t('app', 'Attempted At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

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

    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getCourse(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    public function getCourseElement(): \yii\db\ActiveQuery
    {
        return $this->hasOne(CourseElement::class, ['id' => 'element_id']);
    }

    public static function findByUserId($id): ?array
    {
        return static::findAll(['user_id' => $id]);
    }

    public function search(array $params): ActiveDataProvider
    {
        $this->scenario = 'search';

        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'element_id' => $this->element_id,
            'score' => $this->score,
            'passed' => $this->passed,
        ]);

        $query->andFilterWhere(['like', 'attempted_at', $this->attempted_at])
              ->andFilterWhere(['like', 'created_at', $this->created_at])
              ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
