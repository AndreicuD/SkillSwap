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
 * Follow model
 *
 * @property integer $id [int(auto increment)]
 * @property integer $from_user_id [int(11)]
 * @property integer $to_user_id [int(11)]
 * 
 * @property integer $created_at [datetime]
 * @property integer $updated_at [timestamp = current_timestamp()]
 *
 *
 */
class Follow extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%follow}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['from_user_id', 'to_user_id'], 'required', 'on' => 'default'],
            [['from_user_id', 'to_user_id'], 'required', 'on' => 'create'],

            [['from_user_id', 'to_user_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from_user_id' => Yii::t('app', 'Who Follows'),
            'to_user_id' => Yii::t('app', 'Who Is Followed'),
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

    // optional relations
    public function getFollower()
    {
        return $this->hasOne(User::class, ['id' => 'from_user_id']);
    }

    public function getFollowed()
    {
        return $this->hasOne(User::class, ['id' => 'to_user_id']);
    }

    /**
     * Returns an array of who the user (with $id) follows
     */
    public static function findFollowing($id): array
    {
        return static::findAll(['from_user_id' => $id]);
    }

    /**
     * Returns an array of who follows the user (with $id)
     */
    public static function findFollowers($id): array
    {
        return static::findAll(['to_user_id' => $id]);
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

        $query->andFilterWhere(['like', 'from_user_id', $this->from_user_id])
            ->andFilterWhere(['like', 'to_user_id', $this->to_user_id])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
