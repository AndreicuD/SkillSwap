<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Article $model */

use kartik\widgets\ActiveForm;
use yii\bootstrap5\Html;
use kartik\widgets\StarRating;

use common\models\Rating;
?>
<p class="card-text"><b><?= Yii::t('app', 'Description')?>:</b></p>
<p class="card-text" style="margin-bottom: 0; align-content: center;"><?= $model->description ? Html::encode($model->description) : Yii::t('app', 'This article does not have a description.') ?></p>

<hr>
<p class="card-text"><b><?= Yii::t('app', 'Rating')?>:</b></p>
<?php echo StarRating::widget([
    'name' => 'rating',
    'value' => Rating::calculateRating($model->id),
    'pluginOptions' => [
        'readonly' => true,
        'showClear' => false,
        'showCaption' => false,
    ],
]); ?>