<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Category model
 *
 * @property integer $id [int(auto increment)]
 * @property string $name [varchar(128)]
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 * @property integer $page_size
 *
 */
class Category extends ActiveRecord
{

    const STATUS_PRIVATE = 0;
    const STATUS_PUBLIC = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required', 'on' => 'default'],
            [['name'], 'required', 'on' => 'create'],
            
            [['name'], 'string', 'max' => 128],

            ['name', 'unique', 'on' => 'default'],
            ['name', 'unique', 'on' => 'create'],

            [['name', 'id'], 'safe'],
            [['name', 'id'], 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
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

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            // Trim, normalize spaces, and capitalize each word in the name
            $this->name = trim(preg_replace('/\s+/', ' ', $this->name));
            $this->name = mb_convert_case($this->name, MB_CASE_TITLE, 'UTF-8');
            return true;
        }

        return false;
    }

    /**
     * Finds all unique categories from the article table.
     *
     * @return array|null
     */
    public static function getCategories(): ?array
    {
        return ArrayHelper::map(Category::find()->all(), 'name', 'name');
    }


    /**
     * Returns the object (with the same id) if found.
     */
    public static function findIdentity($id): Category|IdentityInterface|null
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Returns the object's name if found.
     */
    public static function getName($id): ?string
    {
        $object = static::findOne(['id' => $id]);
        return $object ? $object->name : 'Category could not be found.';
    }
    /**
     * Returns the object's id if found.
     */
    public static function getId($name): ?string
    {
        $object = static::findOne(['name' => $name]);
        return $object ? $object->id : '';
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Finds category by name
     *
     * @param string $name
     * @return array|null
     */
    public static function findByName($name): null|array
    {
        return static::findOne(['name' => $name]);
    }
}
