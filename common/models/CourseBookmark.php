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
 * Bookmark model
 *
 * @property integer $id [int(auto increment)]
 * @property integer $user_id [int(11)]
 * @property integer $course_id [int(11)]
 * 
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 *
 */
class CourseBookmark extends ActiveRecord
{
    const STATUS_PRIVATE = 0;
    const STATUS_PUBLIC = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%course_bookmark}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'course_id'], 'required', 'on' => 'default'],
            [['user_id', 'course_id'], 'required', 'on' => 'create'],

            [['user_id', 'course_id'], 'safe'],
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
    public static function findBookmark($user_id, $course_id): CourseBookmark|IdentityInterface|null
    {
        return static::findOne(['user_id' => $user_id, 'course_id' => $course_id]);
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
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }

    /**
     * Finds bookmarks by user id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByUserId($id): null|array
    {
        return static::findAll(['user_id' => $id]);
    }

    /**
     * Finds bookmarks by course id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByCourseId($id): null|array
    {
        return static::findAll(['course_id' => $id]);
    }

    /**
     * Finds bookmarks by id.
     *
     * @param string $id
     * @return CourseBookmark|null
     */
    public static function findModel($id): null|CourseBookmark
    {
        return static::findOne(['id' => $id]);
    }
}
