<?php

/** @var yii\web\View $this */

use yii\widgets\ListView;
use yii\bootstrap5\Html;
use yii\helpers\Url;
?>
<h1><?= Yii::t('app', 'Articles') ?></h1>
<hr>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '/templates/article',
    'viewParams' => ['transactionModel' => $transactionModel,
                    'reviewModel' => $reviewModel,
                    'bookmarkModel' => $articleBookmarkModel,
                    'page' => 'article/index',
                ],
    'options' => [
        'tag' => 'div',
        'class' => 'flex-row-even'
    ],
    'itemOptions' => [
        'tag' => 'div',
        'class' => 'card card-33',
    ],
    'layout' => '{items}{pager}',
    'pager' => [
        'pageCssClass' => 'page-item',
        'prevPageCssClass' => 'prev page-item',
        'nextPageCssClass' => 'next page-item',
        'firstPageCssClass' => 'first page-item',
        'lastPageCssClass' => 'last page-item',
        'linkOptions' => ['class' => 'page-link'],
        'disabledListItemSubTagOptions' => ['class' => 'page-link'],
        'options' => ['class' => 'pagination justify-content-center'],
    ],
]); ?>
