<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Review $model */

use yii\bootstrap5\Html;
use kartik\widgets\StarRating;
use common\models\User;

?>
<div class="group_together">
    <p><b><?= Html::encode(User::getUsername($model->user_id)); ?></b> - <span class="gray"><?= Html::encode($model->updated_at) ?></span></p>
    <div>
        <?php echo StarRating::widget(['model' => $model, 'attribute' => 'value',
            'options' => ['id' => 'review-' . $model->user_id . $model->article_id . $model->id],
            'pluginOptions' => [
                'step' => 0.01,
                'showCaption' => false,
                'size' => 'xs',
                'readonly' => true,
                'showClear' => false,
                ]
            ]); ?>
    </div>
</div>
<div class="w-100">
    <p style="margin: none; padding: none;"><b><?= Html::encode($model->title) ?></b></p>
</div>
<div>
    <p><?= Html::encode($model->body) ?></p>
</div>
<hr>