<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Course $model */

use yii\bootstrap5\Html;
use yii\helpers\Url;
?>
<div class="text-center">
    <p class="card-text" style="margin-bottom: 0; align-content: center;"><?= Yii::t('app', 'Are you sure you want to delete this course?') ?></p>
    <p class="card-text" style="margin-bottom: 0; align-content: center;"><?= Yii::t('app', 'This action is <b class="text-danger">irreversible</b> and cannot be undone.') ?></p>
    <br>
    <?=Html::a(Yii::t('app', 'Yes'), Url::to(['course/delete', 'id' => $model->id]), ['class' => ['btn btn-danger']]); ?>
</div>