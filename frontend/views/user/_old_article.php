<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $widget yii\widgets\ListView this widget instance */
/* @var $key mixed the key value associated with the data item */
/* @var $index integer the zero-based index of the data item in the items array returned by the data provider */
?>
<div class="card" style="width: 100%;">
    <div class="card-header group_together">
        <h5 class="card-title card-title-edit"><?= Yii::t('app', Html::encode($model->title)) ?></h5>

        <div class="group_together">
            <button 
                id="article_modal_<?=$index?>" 
                class="btn btn-secondary btn-ajax rotate_on_hover" 
                data-modal_title="<?=Yii::t('app', 'Settings'); ?>" 
                data-modal_url="<?=Url::to(['article/ajax-edit', 'public_id' => $model->public_id]); ?>"
                data-modal_form="#article-form "
                style="float: right;"
            >
                Settings
            </button>
            <?= Html::a(Yii::t('app', 'Edit Article'),['/article/edit', 'public_id' => $model->public_id],['class' => ['btn btn-primary rotate_on_hover scale_on_hover']]) ?>
        </div>
    </div>
    <div class="card-body">
        <p class="card-text"><?= Yii::t('app', Html::encode($model->description)) ?></p>
    </div>
    <div class="card-footer" style="border-top: 0;">
        <div class="group_together">
            <p class="card-text custom-value custom-profit" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Profit: ') ?></b> <?= Html::encode($model->profitArticleId($model->id)) ?></p>
            <p class="card-text custom-value custom-likes" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Likes: ') ?></b> 13 </p>
            <p class="card-text" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Category: ') ?></b> <?= Category::getName($model->category) ?></p>
            <p class="card-text" style="margin-bottom: 0; align-content: center;"><b><?= Yii::t('app', 'Public: ') ?></b> <?= $model->publicLabel ?></p>
        </div>
    </div>
</div>

