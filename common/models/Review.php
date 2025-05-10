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
 * Review model
 *
 * @property integer $id [int(auto increment)]
 * @property integer $user_id [int(11)]
 * @property integer $article_id [int(11)]
 * @property float $value [float(11)]
 * @property integer $title [varchar(256)]
 * @property integer $body [varchar(2048)]
 * 
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 *
 */
class Review extends ActiveRecord
{
    const STATUS_PRIVATE = 0;
    const STATUS_PUBLIC = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%review}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'article_id'], 'required', 'on' => 'default'],
            [['user_id', 'article_id', 'value'], 'required', 'on' => 'create'],
            [['title'], 'string', 'max' => 256],
            [['body'], 'string', 'max' => 2048],

            [['user_id', 'article_id', 'value', 'body', 'title'], 'safe'],
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
            'value' => Yii::t('app', 'Value'),
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
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
    public static function findRating($user_id, $article_id): Review|IdentityInterface|null
    {
        return static::findOne(['user_id' => $user_id, 'article_id' => $article_id]);
    }
    /**
     * Returns the rating for an article found by id.
     */
    public static function calculateRating($article_id): float
    {
        $rating_median = 0;
        $ratings = self::findAll(['article_id' => $article_id]);
        foreach($ratings as $rating) {
            $rating_median += $rating->value;
        }
        if(count($ratings) > 0) {
            $rating_median /= count($ratings);
            return round($rating_median, 2);
        } else {
            return 0;
        }
    }
    /**
     * Returns the number of ratings for an article found by id.
     */
    public static function countRatings($article_id): int
    {
        $ratings = self::findAll(['article_id' => $article_id]);
        return count($ratings);  
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
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }

    /**
     * Finds reviews by user id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByUserId($id): null|array
    {
        return static::findAll(['user_id' => $id]);
    }

    /**
     * Finds reviews by article id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByArticleId($id): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => static::find()->where(['article_id' => $id]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
    }
}
