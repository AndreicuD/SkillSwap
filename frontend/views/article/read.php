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
    
    <div class="article-content user-select-none">
        <div class="article-image">
            <img src="../frontend/web/img/placeholder.jpg" alt="HTML5 Icon">
            <div class="article-title">
                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
                <h4 class="text-center"><?= Html::encode(User::getUsername($model->user_id)) ?></h4>
            </div>
        </div>
        <br>
        <?= $model->content ?>
    </div>
</div>