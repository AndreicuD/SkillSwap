<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use common\models\Article;
use common\models\Quiz;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1 style="text-align: center;" class="page_title"><?= Html::encode($this->title) ?></h1>

    <br>

    <?php
    $elements = $model->orderedElements;

    if (empty($elements)) {
        echo "<p style='text-align:center'>" . Yii::t('app', 'This course has no content yet. What would you like to add?') . "</p>";
    ?>
        <div class="w-100 flex-row-even">
            <div>
                <?= $this->render('/course/add_element_card', [
                    'type' => 'article',
                    'title' => 'Add an article',
                    'url' => ['/article/create', 'course_id' => $model->id],
                ]); ?>
            </div>
            <div>
                <?= $this->render('/course/add_element_card', [
                    'type' => 'quiz',
                    'title' => 'Add a quiz',
                    'url' => ['/quiz/create', 'course_id' => $model->id],
                ]); ?>
            </div>
        </div>
    <?php
    } else {        
        echo "<div style='display: flex; flex-wrap: wrap; gap: 16px; justify-content: center;'>";

        foreach ($elements as $element) {
            if ($element instanceof Article) {
                echo $this->render('/course/element_card', [
                    'type' => 'article',
                    'title' => $element->title,
                ]);
            } elseif ($element instanceof Quiz) {
                echo $this->render('/course/element_card', [
                    'type' => 'quiz',
                    'title' => Yii::t('app', 'Quiz') . ' #' . $element->id,
                ]);
            }
        }

        echo "</div>";
    }
    ?>
</div>