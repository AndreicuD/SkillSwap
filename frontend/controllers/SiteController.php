<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\BaseController;

use frontend\models\ContactForm;

use common\models\Article;
use common\models\Category;
use common\models\Transaction;
use common\models\ArticleReview;
use common\models\ArticleBookmark;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Article();
        $searchModel2 = new Article();
        $latestDataProvider = $searchModel->searchLatest();
        $topRatedDataProvider = $searchModel2->searchTopRated();

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $transactionModel = new Transaction();
        $reviewModel = new ArticleReview();
        $bookmarkModel = new ArticleBookmark();

        return $this->render('index', [
            'model' => $searchModel,
            'latestDataProvider' => $latestDataProvider,
            'topRatedDataProvider' => $topRatedDataProvider,
            'transactionModel' => $transactionModel,
            'reviewModel' => $reviewModel,
            'bookmarkModel' => $bookmarkModel,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays faq page.
     *
     * @return mixed
     */
    public function actionFaq()
    {
        return $this->render('faq');
    }

    /**
     * Displays terms and conditions page.
     *
     * @return mixed
     */
    public function actionTerms()
    {
        return $this->render('terms');
    }
    /**
     * Displays privacy policy page.
     *
     * @return mixed
     */
    public function actionPrivacy()
    {
        return $this->render('privacy');
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }
}
