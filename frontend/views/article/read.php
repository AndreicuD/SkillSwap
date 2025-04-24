<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Category;
use common\models\User;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    
    <div class="article-content">
        <div class="article-image">
            <img src="../frontend/web/img/placeholder.jpg" alt="HTML5 Icon">
            <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>
        </div>
        <br>
        <?= $model->content ?>
    </div>
</div>