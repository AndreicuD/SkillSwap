<?php

/** @var yii\web\View $this */
/** @var \common\models\Course $model */

use yii\helpers\Html;
use common\models\User;
use common\models\Transaction;
use common\models\CourseReview;
use kartik\widgets\ActiveForm;
use kartik\widgets\StarRating;
use yii\widgets\ListView;

$userReviewModel->course_id = $model->id;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = $this->title;

$src = $model->checkFileExists() ? $model->getSrc() : '/img/default.png';
?>
<div class="site-index">
    
    <div class="user-select-none">
        <div class="article-title-sm">
            <p class="text-center article-title"><?= Html::encode($this->title) ?></p>
            <p class="text-center article-user">- <?= Html::encode(User::getUsername($model->user_id)) ?> -</p>
        </div>
        <div class="article-image">
            <img src="<?=$src?>" alt="<?= Html::encode($this->title) ?>">
            <div class="article-title-area">
                <p class="text-center article-title"><?= Html::encode($this->title) ?></p>
                <p class="text-center article-user">- <?= Html::encode(User::getUsername($model->user_id)) ?> -</p>
            </div>
        </div>
        <br>
        <div class="article-content">
            <?php 
                if(Transaction::findTransaction(Yii::$app->user->id, $model->id) || $model->user_id == Yii::$app->user->id) {

                    $elements = $model->orderedElements;
                    $first = $elements[0] ?? null;

                    if ($first instanceof \common\models\Article) {
                        echo "<h3>Article</h3>";
                        echo Html::encode($first->title);
                        echo "<div>" . nl2br(Html::encode($first->content)) . "</div>";

                    } elseif ($first instanceof \common\models\Quiz) {
                        echo "<h3>Quiz</h3>";
                        echo Html::encode("Quiz with " . count($first->questions) . " questions");
                        // Optional: show preview of the first question
                        $question = $first->questions[0] ?? null;
                        if ($question) {
                            echo "<p><b>Q:</b> " . Html::encode($question->text) . "</p>";
                        }
                    } else {
                        echo Yii::t('app', 'No elements found for this course.');
                    }
                } else {
                    echo '<div class="text-center lead">';
                    echo Yii::t('app', 'You can not acces this course. Check if you have bought it or if you are logged in.');
                    echo '<br>';
                    echo '<br>';
                    echo '</div>';
                }
            ?>

            <hr>
            <div class="w-100 padd-10">
                <h2><?= Yii::t('app', 'Reviews!'); ?></h2>
                <?php 
                    if(Transaction::findTransaction(Yii::$app->user->id, $model->id) || $model->user_id == Yii::$app->user->id) {
                        
                        if(CourseReview::findRating(Yii::$app->user->id, $model->id)) {
                            $form = ActiveForm::begin([
                                'id' => 'course-form',
                                'type' => ActiveForm::TYPE_FLOATING,
                                'action' => ['review/update-course', 'public_id' => $model->public_id], // Specify the route to the update  action
                                'method' => 'post',
                            ]);
                        } else {
                            $form = ActiveForm::begin([
                                'id' => 'course-form',
                                'type' => ActiveForm::TYPE_FLOATING,
                                'action' => ['review/create-course', 'public_id' => $model->public_id], // Specify the route to the create action
                                'method' => 'post',
                            ]);
                        }
                        echo $form->errorSummary($userReviewModel);
                        echo $form->field($userReviewModel, 'title')->label(Yii::t('app', 'Title'));
                        echo $form->field($userReviewModel, 'body')->textarea(['rows' => 4, 'style' => 'min-height: 160px']);
                    
                        echo $form->field($userReviewModel, 'value')->widget(StarRating::classname(), [
                            'pluginOptions' => ['step' => 0.5]
                        ])->label(false);
                
                        echo Html::activeHiddenInput($userReviewModel, 'course_id');
                
                        echo '<div class="w-100 text-center">';
                            echo Html::button(Yii::t('app', 'Save Review'),['class' => ['btn btn-primary rotate_on_hover scale_on_hover mb-3'], 'type' => 'submit']);
                        echo '</div>';
                    
                        ActiveForm::end();
                    }
                    ?>
                    <br>
                    <br>
                    <?= ListView::widget([
                        'dataProvider' => $reviewDataProvider,
                        'itemView' => '_review',
                        'viewParams' => ['reviewModel' => $reviewModel],
                        'options' => [
                            'tag' => 'div',
                            'class' => ''
                        ],
                        'itemOptions' => [
                            'tag' => 'div',
                            'class' => 'w-100',
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
        </div>
    </div>
</div>