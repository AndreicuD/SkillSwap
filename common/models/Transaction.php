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
 * Transaction model
 *
 * @property integer $id [int(auto increment)]
 * @property integer $user_id [int(11)]
 * @property integer $article_id [int(11)]
 * @property integer $course_id [int(11)]
 * @property integer $value [int(11)]
 * 
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 *
 */
class Transaction extends ActiveRecord
{
    const STATUS_PRIVATE = 0;
    const STATUS_PUBLIC = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id'], 'required', 'on' => 'default'],
            [['user_id', 'value'], 'required', 'on' => 'create'],

            [['user_id', 'article_id', 'course_id', 'value'], 'safe'],
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
            'article_id' => Yii::t('app', 'Article ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'value' => Yii::t('app', 'Value'),
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
    public static function findTransaction($user_id, $id): Transaction|IdentityInterface|null
    {
        $article = static::findOne(['user_id' => $user_id, 'article_id' => $id]);
        if ($article) return $article;
        return static::findOne(['user_id' => $user_id, 'course_id' => $id]);
    }

    /**
     * Returns the profit for an article found by id.
     */
    public static function calculateArticleProfit($article_id): int
    {
        $profit = 0;
        $transactions = static::findAll(['article_id' => $article_id]);
        foreach($transactions as $transaction) {
            $profit += $transaction->value;
        }
        return $profit;
    }
    /**
     * Returns the profit for an course found by id.
     */
    public static function calculateCourseProfit($course_id): int
    {
        $profit = 0;
        $transactions = static::findAll(['course_id' => $course_id]);
        foreach($transactions as $transaction) {
            $profit += $transaction->value;
        }
        return $profit;
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
            ->andFilterWhere(['like', 'article_id', $this->article_id])
            ->andFilterWhere(['like', 'course_id', $this->course_id])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }

    /**
     * Finds transactions by user id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByUserId($id): null|array
    {
        return static::findAll(['user_id' => $id]);
    }

    /**
     * Finds transactions by article id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByArticleId($id): null|array
    {
        return static::findAll(['article_id' => $id]);
    }

    /**
     * Finds transactions by course id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByCourseId($id): null|array
    {
        return static::findAll(['course_id' => $id]);
    }
}
