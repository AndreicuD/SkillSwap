<?php

use yii\bootstrap5\Html;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $widget yii\widgets\ListView this widget instance */
/* @var $key mixed the key value associated with the data item */
/* @var $index integer the zero-based index of the data item in the items array returned by the data provider */
$point_svg = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-analyze"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -6.986 -6.918a8.095 8.095 0 0 0 -8.019 3.918" /><path d="M4 13a8.1 8.1 0 0 0 15 3" /><path d="M19 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M5 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>';
?>
<div class="card-image">
    <img src="../frontend/web/img/placeholder.jpg" alt="HTML5 Icon" width="250" height="250">
</div>
<div class="card-body">
    <p class="card-text small gray" style="margin-bottom: 0; align-content: center;"><?= Category::getName($model->category) ?></p>
    <h5 class="card-text"><?= Html::encode($model->title) ?></h5>
    <p class="card-text"><?= Html::encode($model->description) ?></p>
</div>
<a href="#" class="card-button btn btn-primary"><?= Html::encode($model->price) ?>
<?= $point_svg ?>
</a>
