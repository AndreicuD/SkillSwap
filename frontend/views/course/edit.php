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

    echo "<p style='text-align:center'>" . Yii::t('app', 'Add new element?') . "</p>"; ?>
    <div class="w-100 flex-row-even">
        <div class="scale_on_hover rotate_on_hover">
            <?= $this->render('/course/add_element_card', [
                'type' => 'article',
                'title' => 'Add an article',
                'url' => ['/article/create-in-course', 'course_id' => $model->id],
            ]); ?>
        </div>
        <div class="scale_on_hover rotate_on_hover">
            <?= $this->render('/course/add_element_card', [
                'type' => 'quiz',
                'title' => 'Add a quiz',
                'url' => ['/quiz/create', 'course_id' => $model->id],
            ]); ?>
        </div>
    </div> 
    
    <hr>
    
    <?php
    echo "<div class='flex-row-start'>";

    foreach ($elements as $entry) {
        $element = $entry['model'];
        $sortIndex = $entry['sort_index'];

        if ($element instanceof Article) {
            echo '<div class="card">';
            echo $this->render('/course/_article', [
                'model' => $element,
                'spot' => $sortIndex,
                'page' => ['user/courses'],
            ]);
            echo '</div>';
        } elseif ($element instanceof Quiz) {
            echo $this->render('/course/element_card', [
                'type' => 'quiz',
                'title' => Yii::t('app', 'Quiz') . ' #' . $element->id,
            ]);
        }
    }

    echo "</div>";
    ?>

    <table class="table table-bordered sortable-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 60px;">Type</th>
                <th>Title</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody id="course-elements-list">
            <?php foreach ($elements as $element): ?>
                <tr class="element-row" data-element-id="<?= $element['model']->id ?>">
                    <td><?= $element['sort_index'] ?></td>
                    <td>
                        <?= $element['type'] === 'quiz' ? '🧠' : '📄' ?>
                    </td>
                    <td><?= Html::encode($element['model']->title ?? 'Untitled') ?></td>
                    <td>
                        <?php if ($element['type'] === 'article'): ?>
                            <?= Html::a('Edit', ['/article/course-edit', 'public_id' => $element['model']->public_id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        <?php elseif ($element['type'] === 'quiz'): ?>
                            <?= Html::a('Edit', ['/quiz/course-edit', 'public_id' => $element['model']->public_id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>