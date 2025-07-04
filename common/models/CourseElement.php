<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Course element model
 *
 * @property integer $id [int(auto increment)]
 * @property integer $course_id [int(11)]
 * 
 * @property string $element_type [ENUM('article','quiz')]
 * @property integer $element_id [int(11)]
 * @property integer $sort_index [int(11)]
 * 
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 *
 */
class CourseElement extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%course_element}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['course_id'], 'required', 'on' => 'default'],
            [['course_id', 'element_type', 'element_id'], 'required', 'on' => 'create'],
            
            ['element_type', 'in', 'range' => ['article', 'quiz']],
            ['sort_index', 'default', 'value' => 0],
            ['element_id', 'unique', 'targetAttribute' => ['course_id', 'element_type', 'element_id'], 'message' => 'This element is already added to the course.'],
        
            ['element_id', 'unique', 'on' => 'default'],
            ['element_id', 'unique', 'on' => 'create'],

            [['course_id', 'element_type', 'element_id', 'sort_index', 'id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'element_type' => Yii::t('app', 'Element Type'),
            'element_id' => Yii::t('app', 'Element ID'),
            'sort_index' => Yii::t('app', 'Sort Index'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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

    /**
     * Relation to Course
     */
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    /**
     * Returns the actual element (Article or Quiz)
     *
     * @return Article|Quiz|null
     */
    public function getElement()
    {
        return match ($this->element_type) {
            'article' => Article::findOne($this->element_id),
            'quiz' => Quiz::findOne($this->element_id),
            default => null,
        };
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $this->scenario = 'search';

        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['sort_index'=>SORT_ASC],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'course_id', $this->course_id])
            ->andFilterWhere(['like', 'element_type', $this->element_type])
            ->andFilterWhere(['like', 'element_id', $this->element_id])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
