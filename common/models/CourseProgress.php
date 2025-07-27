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

use common\models\Category;
/**
 * Course Progress model
 *
 * @property integer $id [int(auto increment)]
 * @property integer $user_id [int(11)]
 * @property integer $course_id [int(11)]
 * @property integer $last_try [datetime]
 * @property integer $completed_at [datetime]
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 *
 */
class CourseProgress extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%course_progress}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['updated_at', 'created_at', 'completed_at', 'last_try', 'course_id', 'user_id', 'id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'last_try' => Yii::t('app', 'Last Try'),
            'completed_at' => Yii::t('app', 'Completed At'),
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
     * Returns the object (with the same id) if found.
     */
    public static function findIdentity($id): Article|IdentityInterface|null
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Creates data provider instance with search query applied
     * used to create lists / grids
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
                'defaultOrder' => ['id'=>SORT_DESC],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        

        $query->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'course_id', $this->course_id])
            ->andFilterWhere(['like', 'last_try', $this->last_try])
            ->andFilterWhere(['like', 'completed_at', $this->completed_at])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }

    /**
     * Finds course progress by user id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByUserId($id): null|array
    {
        return static::findAll(['user_id' => $id]);
    }

    public function getElement()
{
    return $this->hasOne(CourseElement::class, ['id' => 'element_id']);
}
}
