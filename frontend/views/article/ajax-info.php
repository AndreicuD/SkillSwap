<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Article $model */

use yii\bootstrap5\Html;
use kartik\widgets\StarRating;

?>
<!--<p class="card-text"><b><?= Yii::t('app', 'Description')?>:</b></p>-->
<p class="card-text" style="margin-bottom: 0; align-content: center;"><?= $model->description ? Html::encode($model->description) : Yii::t('app', 'This article does not have a description.') ?></p>
<br>