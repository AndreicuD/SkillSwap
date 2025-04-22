<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $widget yii\widgets\ListView this widget instance */
/* @var $key mixed the key value associated with the data item */
/* @var $index integer the zero-based index of the data item in the items array returned by the data provider */
?>
<div class="card-image">
    <img src="../frontend/web/img/placeholder.jpg" alt="HTML5 Icon" width="250" height="250">
</div>
<div class="card-body">
    <p class="card-text small gray" style="margin-bottom: 0; align-content: center;"><?= Category::getName($model->category) ?>
     - <span class="text-secondary"><?= Html::encode($model->getPublicLabel()) ?></span></p>

    <h5 class="card-text card-title"><?= Html::encode($model->title) ?></h5>
    <p class="card-text"><?= Html::encode($model->description) ?></p>
</div>
<div class="card-footer group_together" style="border: none;">
    <button 
        id="article_modal_<?=$index?>" 
        class="btn btn-secondary btn-ajax rotate_on_hover" 
        data-modal_title="<?=Yii::t('app', 'Stats'); ?>" 
        data-modal_url="<?=Url::to(['article/ajax-stats', 'public_id' => $model->public_id]); ?>" >
        Stats
    </button>
    <?= Html::a(Yii::t('app', 'Edit Article'),['/article/edit', 'public_id' => $model->public_id],['class' => ['btn btn-primary rotate_on_hover scale_on_hover']]) ?>
</div>
