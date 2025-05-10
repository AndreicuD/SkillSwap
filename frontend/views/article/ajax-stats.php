<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Article $model */

use yii\bootstrap5\Html;
use common\models\Transaction;

use common\models\Category;
$model->category_name = Category::getName($model->category);
?>
<p class="card-text text-center" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Profit: ') ?></b></p>
<p class="custom-value custom-profit text-center"><?= Transaction::calculateProfit($model->id) ?></p>
<p class="card-text text-center" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'How many bought: ') ?></b></p>
<p class="custom-value custom-bought text-center"><?= count(Transaction::findByArticleId($model->id)) ?></p>

<hr>
<p class="card-text" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Last Updated At: ') ?></b> <?= Html::encode($model->updated_at) ?></p>
<p class="card-text" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Created At: ') ?></b> <?= Html::encode($model->created_at) ?></p>