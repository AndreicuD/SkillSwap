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
 * Article model
 *
 * @property integer $id [int(auto increment)]
 * @property string $public_id [varchar(36)]
 * @property string $user_id [int(11)]
 * @property string $title [varchar(254)]
 * @property string $description [varchar(1024)]
 * @property string $content [mediumtext]
 * 
 * @property integer $category [varchar(128)]
 * 
 * @property string $price [int(11)]
 * @property string $bought [int(11)]
 *
 * @property string $likes_count [int(11)]
 * @property integer $is_public [smallint(1)]
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 * @property integer $page_size
 *
 */
class Article extends ActiveRecord
{

    const STATUS_PRIVATE = 0;
    const STATUS_PUBLIC = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title'], 'required', 'on' => 'default'],
            [['title', 'content'], 'required', 'on' => 'create'],
            
            ['is_public', 'default', 'value' => self::STATUS_PUBLIC, 'on' => 'default'],
            ['is_public', 'in', 'range' => [self::STATUS_PUBLIC, self::STATUS_PRIVATE]],
            ['price', 'in', 'range' => [500, 1200]],
            
            ['category', 'default', 'value' => 'Uncategorised', 'on' => 'create'],
            
            [['title'], 'string', 'max' => 254],
            [['description'], 'string', 'max' => 1024],
            [['public_id'], 'string', 'max' => 36],

            ['title', 'unique', 'on' => 'default'],
            ['title', 'unique', 'on' => 'create'],

            [['title', 'description', 'content', 'category', 'likes_count', 'is_public', 'public_id', 'user_id', 'id'], 'safe'],
            [['title', 'description', 'content', 'category', 'likes_count', 'is_public', 'public_id', 'user_id', 'id'], 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'public_id' => Yii::t('app', 'Public ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),

            'category' => Yii::t('app', 'Category'),

            'likes_count' => Yii::t('app', 'Like Count'),
            'is_public' => Yii::t('app', 'Public'),
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
     * @return array the possible public statuses
     */
    public static function publicList(): array
    {
        return [
            self::STATUS_PUBLIC => Yii::t('app', 'Public'),
            self::STATUS_PRIVATE => Yii::t('app', 'Private'),
        ];

    }
    public function getPublicLabel(): string
    {
        return self::publicList()[$this->is_public] ?? '';
    }

    /**
     * Creates data provider instance with search query applied
     * used to create lists / grids
     *
     * @param array $params
     * @param bool $full
     * @return ActiveDataProvider
     */
    public function search(array $params, bool $full = false): ActiveDataProvider
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

        if ($full) {
            $dataProvider->setPagination(false);
        } else {
            $dataProvider->pagination->pageSize = ($this->page_size !== NULL) ? $this->page_size : 20;
        }



        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }

   /**
     * Finds all unique categories from the article table.
     *
     * @return array|null
     */
    public static function getCategories(): array
    {
        return ArrayHelper::map(static::find()->select('category')->all(), 'category', 'category');
/*
        return array_filter(
            array_map('trim',
                static::find()
                    ->select(['category'])
                    ->distinct()
                    ->orderBy(['category' => SORT_ASC])
                    ->column()
            ),
            fn($category) => !empty($category)
        );*/
    }

    /**
     * Finds articles by user id.
     *
     * @param string $id
     * @return array|null
     */
    public static function findByUserId($id): null|array
    {
        return static::findAll(['user_id' => $id, 'is_public' => self::STATUS_PUBLIC]);
    }

    /**
     * Finds how much profit user made for one song.
     *
     * @param string $id
     * @return int
     */
    public static function profitArticleId($id): int
    {
        $model =  static::findOne(['id' => $id]);
        return 0.1 * $model->price * $model->bought;
    }
}
