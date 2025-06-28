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
 * Course model
 *
 * @property integer $id [int(auto increment)]
 * @property string $public_id [varchar(36)]
 * @property integer $user_id [int(11)]
 * @property string $title [varchar(254)]
 * @property string $description [varchar(1024)]
 * @property string $content [mediumtext]
 * 
 * @property integer $category [int(11)]
 * 
 * @property integer $price [int(11)]
 * @property integer $bought [int(11)]
 * 
 * @property integer $likes_count [int(11)]
 * @property integer $is_public [smallint(1)]
 * 
 * @property string $cover_extension [varchar(254)]
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 *
 */
class Course extends ActiveRecord
{
    public $cover;
    public $storage_path = '@frontend/web/files/course';
    public $storage_uri = '/files/course';
    public $category_name;
    const STATUS_PRIVATE = 0;
    const STATUS_PUBLIC = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%course}}';
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
            

            ['price', 'number', 'min' => 500, 'max' => 1200],
            ['price', 'default', 'value' => 1000],
            ['category', 'default', 'value' => 1, 'on' => 'create'],
            
            [['title'], 'string', 'max' => 254],
            [['description'], 'string', 'max' => 1024],
            [['public_id'], 'string', 'max' => 36],

            ['title', 'unique', 'on' => 'default'],
            ['title', 'unique', 'on' => 'create'],

            [['title', 'description', 'content', 'category', 'category_name', 'price', 'likes_count', 'is_public', 'public_id', 'user_id', 'id'], 'safe'],
        ];
    }
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios['search'] = [
            'id',
            'title',
            'description',
            'content',
            'category',
            'category_name',
            'created_at',
            'updated_at',
        ];
        return $scenarios;
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
    public function beforeValidate(): bool
    {   
        if (!empty($this->category_name)) {
            // Normalize input
            $name = trim(preg_replace('/\s+/', ' ', $this->category_name));
            $name = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');

            // Find or create
            $existing = Category::findOne(['name' => $name]);
            if ($existing) {
                $this->category = $existing->id;
            } else {
                $newCategory = new Category();
                $newCategory->name = $name;
                if ($newCategory->save()) {
                    $this->category = $newCategory->id;
                } else {
                    $this->addError('category_name', Yii::t('app', 'Could not create new category.'));
                    return false;
                }
            }
        }

        return parent::beforeValidate(); // ← call parent method
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category']);
    }

    /**
     * Returns the object (with the same id) if found.
     */
    public static function findIdentity($id): Course|IdentityInterface|null
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
        
        $this->category = Category::getId($this->category_name);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }

    /**
    * Creates data provider instance with search query applied
    *
    * @param array $params
    *
    * @return ActiveDataProvider
    */
    public function searchLatest($limit = 3)
    {
        $this->scenario = 'search';

        $query = self::find();

        $query->andFilterWhere(['is_public' => 1]);
        $query->limit($limit);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['id'=>SORT_DESC],
            ]
        ]);
        $dataProvider->setPagination(false);

        return $dataProvider;
    }

    public function searchTopRated($limit = 3)
    {
        $this->scenario = 'search';

        $query = self::find()
            ->alias('a')
            ->select(['a.*', 'AVG(r.value) as avg_rating'])
            ->joinWith(['reviews r'])
            ->where(['a.is_public' => 1])
            ->groupBy('a.id')
            ->orderBy(['avg_rating' => SORT_DESC])
            ->limit($limit);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false,
        ]);

        return $dataProvider;
    }

    public function getReviews()
    {
        return $this->hasMany(CourseReview::class, ['course_id' => 'id']);
    }

   /**
     * Finds all unique categories from the course table.
     *
     * @return array|null
     */
    public static function getCategories(): array
    {
        return ArrayHelper::map(static::find()->select('category')->all(), 'category', 'category');
    }

    /**
     * Finds courses by user id.
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
    public static function profitCourseId($id): int
    {
        $model =  static::findOne(['id' => $id]);
        return 0.1 * $model->price * $model->bought;
    }

        /**
     * @param $path boolean false returns the URI, true returns the path
     * @return string
     */
    public function getFolder(bool $path = false)
    {
        $str = str_pad($this->id, 2, '0', STR_PAD_LEFT);
        return ($path ? Yii::getAlias($this->storage_path) : $this->storage_uri).'/'.$str[0].'/'.$str[1];
    }

    /**
     * @return string the uri to the file
     */
    public function getSrc()
    {
        return $this->getFolder().'/'.$this->id.'.'.$this->cover_extension;
    }

    /**
     * @return string the path to the file
     */
    public function getFilePath()
    {
        return $this->getFolder(true).'/'.$this->id.'.'.$this->cover_extension;
    }

    /**
     * @return bool
     */
    public function checkFileExists(): bool
    {
        return file_exists($this->getFilePath());
    }

    public function getImageDatas()
    {
        $src_array = [];
        if (isset($this->cover_extension) && !empty($this->cover_extension)) {
            $src_array[] = [
                'type' => 'image',
                'fileId' => $this->id,
                'downloadUrl' => $this->getSrc(),
                'url' => yii\helpers\Url::to(['course/file-delete', 'id' => $this->id]),
                'extra' => [
                    'id' => $this->id,
                ],
                'key' => 1,
            ];
        }
        return $src_array;
    }
}
