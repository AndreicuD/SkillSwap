<?php

/** @var yii\web\View $this */
/** @var \common\models\Course $model */

use yii\helpers\Html;
use common\models\User;
use common\models\Article;
use common\models\Quiz;
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
                if (Transaction::findTransaction(Yii::$app->user->id, $model->id) || $model->user_id == Yii::$app->user->id) {

                    $elements = $model->courseElements;

                    if (!empty($elements)) {
                        echo '<div class="accordion" id="courseAccordion">';

                        foreach ($elements as $index => $element) {
                            $content = null;
                            $elementId = 'element' . $index;

                            if ($element->element_type === 'article') {
                                $content = Article::findOne($element->element_id);
                            } elseif ($element->element_type === 'quiz') {
                                $content = Quiz::findOne($element->element_id);
                            }

                            $title = ($content instanceof Article || $content instanceof Quiz)
                                ? Html::encode($content->title)
                                : Yii::t('app', 'Unknown element');
                            
                            $index += 1;
                            echo <<<HTML
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{$elementId}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{$elementId}" aria-expanded="false" aria-controls="collapse{$elementId}">
                                            <h4>{$index}. {$title}</h4>
                                        </button>
                                    </h2>
                                    <div id="collapse{$elementId}" class="accordion-collapse collapse" aria-labelledby="heading{$elementId}" data-bs-parent="#courseAccordion">
                                        <div class="accordion-body">
                            HTML;

                            if ($content instanceof Article) {
                                echo "<div>" . $content->content . "</div>";

                            } elseif ($content instanceof Quiz) {
                                $form = ActiveForm::begin([
                                    'action' => ['quiz/submit'],
                                    'method' => 'post',
                                    'options' => ['class' => 'quiz-form'],
                                ]);
                                
                                echo Html::hiddenInput('quiz_id', $content->id);
                                
                                foreach ($content->questions as $qIndex => $question) {
                                    echo "<div class='mb-3'>";
                                    echo "<b>Q" . ($qIndex + 1) . ":</b> " . Html::encode($question->text);
                                    echo '<br>';
                                    echo '<br>';


                                    echo "<div class='flex-row-even'>"; // Start row for card layout

                                        foreach ($question->choices as $choice) {
                                            $inputName = "answers[{$question->id}][]"; // checkbox array for multiple correct answers
                                            $choiceId = "choice-{$choice->id}";

                                            echo "<div class='card quiz-question quiz-choice'>";

                                            echo Html::checkbox($inputName, false, [
                                                'value' => $choice->id,
                                                'id' => $choiceId,
                                                'class' => 'btn-check',
                                                'autocomplete' => 'off',
                                            ]);

                                            echo Html::label(
                                                Html::encode($choice->text),
                                                $choiceId,
                                                [
                                                    'class' => 'btn w-100 text-start p-3 shadow-sm',
                                                ]
                                            );

                                            echo "</div>";
                                        }

                                    echo "</div>";

                                    echo "</div><hr>";
                                }

                                echo Html::submitButton(Yii::t('app', 'Submit Quiz'), ['class' => 'btn btn-primary']);
                                ActiveForm::end();

                            } else {
                                echo "<p class='text-muted'>" . Yii::t('app', 'Unknown element type.') . "</p>";
                            }

                            echo <<<HTML
                                        </div>
                                    </div>
                                </div>  
                            HTML;
                        }

                        echo '</div>';
                    } else {
                        echo "<p class='text-muted'>" . Yii::t('app', 'No elements found for this course.') . "</p>";
                    }

                } else {
                    echo '<div class="text-center lead">';
                    echo Yii::t('app', 'You can not access this course. Check if you have bought it or if you are logged in.');
                    echo '<br><br>';
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