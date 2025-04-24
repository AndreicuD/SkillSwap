<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\web\View;
use common\models\Category;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Articles');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('/templates/search', [
        'model' => $model,
        'url' => '/article/index'
    ]) ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_article',
        'viewParams' => ['transactionModel' => $transactionModel],
        'options' => [
            'tag' => 'div',
            'class' => 'flex-row-start'
        ],
        'itemOptions' => [
            'tag' => 'div',
            'class' => 'card',
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
</div>