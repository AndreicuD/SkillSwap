<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Article $model */

use kartik\widgets\ActiveForm;
use yii\bootstrap5\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use common\models\Category;
$model->category_name = Category::getName($model->category);
?>
<p class="card-text text-center" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Profit: ') ?></b></p>
<p class="custom-value custom-profit text-center"><?= Html::encode($model->price) ?></p>
<p class="card-text text-center" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'How many bought: ') ?></b></p>
<p class="custom-value custom-bought text-center"><?= Html::encode($model->bought) ?></p>

<hr>
<p class="card-text" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Last Updated At: ') ?></b> <?= Html::encode($model->updated_at) ?></p>
<p class="card-text" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Created At: ') ?></b> <?= Html::encode($model->created_at) ?></p>