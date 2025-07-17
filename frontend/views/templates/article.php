<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;
use common\models\Category;
use kartik\widgets\ActiveForm;
use common\models\User;
use common\models\Transaction;
use kartik\widgets\StarRating;
use common\models\ArticleReview;

/* @var $this yii\web\View */
/* @var $widget yii\widgets\ListView this widget instance */
/* @var $key mixed the key value associated with the data item */
/* @var $index integer the zero-based index of the data item in the items array returned by the data provider */
/* @var common\models\Article $model */
$point_svg = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-analyze"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -6.986 -6.918a8.095 8.095 0 0 0 -8.019 3.918" /><path d="M4 13a8.1 8.1 0 0 0 15 3" /><path d="M19 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M5 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>';
$transactionModel->article_id = $model->id;
$transactionModel->value = $model->price;

$reviewModel->value = ArticleReview::calculateRating($model->id);

$bookmark_svg = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-bookmark"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 7v14l-6 -4l-6 4v-14a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4z" /></svg>';
$bookmark = $bookmarkModel->findBookmark(Yii::$app->user->id, $model->id);
$src = $model->checkFileExists() ? $model->getSrc() : '/img/default.png';
?>
<div class="card-image">
    <img src="<?=$src?>" alt="<?= Html::encode($this->title) ?>" width="250" height="250">

    <?php $form = ActiveForm::begin([
        'id' => 'bookmark-form' . $model->public_id,
        'action' => empty($bookmark) ? ['bookmark/create-article', 'id' => $model->id, 'page' => $page] : ['bookmark/delete-article', 'id' => $bookmark->id, 'page' => $page], // Specify the route to the create action
    ]);?>
        <button type="submit" name="bookmark" class="absolute_card_button bottom_right_card_button icon_btn bookmark_button <?= !empty($bookmark) ? 'card_button_pressed' : '' ?>">
            <?=$bookmark_svg?>
        </button>
    <?php ActiveForm::end(); ?>
</div>
<div class="card-body">
    <h5 class="card-text card-title" style="margin-bottom: 0;"><?= Html::encode($model->title) ?></h5>
    <div class="group_together">
        <div class=w-50>
            <?php echo StarRating::widget(['model' => $reviewModel, 'attribute' => 'value',
                'name' => 'stars_' . $model->public_id . '_' . uniqid(),
                'options' => ['id' => 'review-' . $model->public_id . '-' . uniqid()],
                'pluginOptions' => [
                    'step' => 0.01,
                    'showCaption' => false,
                    'size' => 'xs',
                    'readonly' => true,
                    'showClear' => false,
                ]
            ]); ?>
        </div>
        <div class="group_together">
            <b><?= $reviewModel->value ?></b>
            <span class="gray">
                (<?= $reviewModel->countRatings($model->id) ?>)
            </span>
        </div>
    </div>
    <p class="card-text gray"><a href="<?= Url::to(['user/index', 'public_id' => User::getPublicId($model->user_id)])?>" ><?= Html::encode(User::getUsername($model->user_id)) ?> </a> - 
    <a class="text-secondary" href="<?= Url::to(['article/index', 'Article[category_name]' => Category::getName($model->category)])?>"><?= Category::getName($model->category) ?></a></p>
</div>
<?php $form = ActiveForm::begin([
    'id' => 'article-form' . $model->public_id,
    'type' => ActiveForm::TYPE_FLOATING,
    'action' => ['transaction/create', 'page' => 'article/index'], // Specify the route to the create action
    'method' => 'post',
]); ?>
<?= $form->errorSummary($transactionModel);?>

<?= Html::activeHiddenInput($transactionModel, 'article_id'); ?>
<?= Html::activeHiddenInput($transactionModel, 'value', ['value' => $model->price]) ?>
<div class="btn-group w-100">
        <button 
            type = "button"
            id="article_info_<?=$model->public_id?>" 
            class="card-button btn btn-secondary btn-ajax" 
            data-modal_title="<?= $model->title ?>" 
            data-modal_url="<?=Url::to(['article/ajax-info', 'public_id' => $model->public_id]); ?>" >
            <?= Yii::t('app', 'Details') ?>
        </button>
        <?php 
            if(Transaction::findTransaction(Yii::$app->user->id, $model->id) || $model->user_id == Yii::$app->user->id) {
                echo Html::a(Yii::t('app', 'Read'), [Url::to(['article/read', 'public_id' => $model->public_id])],['class' => ['card-button btn btn-primary']]);
            } else {
                echo Html::button(Html::encode($model->price) . $point_svg,['class' => ['card-button btn btn-primary'], 'type' => 'submit']);
            }
        ?>
</div>
<?php ActiveForm::end(); ?>
