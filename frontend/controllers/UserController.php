<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use frontend\components\BaseController;
use yii\db\Query;

use yii\web\Response;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ChangePasswordForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\web\UploadedFile;
use common\models\Article;
use common\models\Course;
use common\models\Category;
use common\models\User;
use common\models\Transaction;
use common\models\ArticleBookmark;
use common\models\ArticleReview;
use common\models\CourseBookmark;
use common\models\CourseReview;

/**
 * Site controller
 */
class UserController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'reset-password', 'request-password-reset', 'verify-email', 'resend-verification-email'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['profile', 'articles', 'courses', 'articles-bought', 'courses-bought', 'logout', 'settings', 'change-password', 'file-upload', 'file-delete', 'delete-avatar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Searches for users based on the term sent via AJAX.
     *
     * @param string $term The search term from the user input.
     * @return array JSON response with matching usernames.
     */

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($public_id)
    {   
        if($public_id) {
            $model = User::findOne(['public_id' => $public_id]);
        } else {
            $model = User::findOne(['id' => Yii::$app->user->id]);
        }

        return $this->render('index', [
            'user' => $model,
        ]);
    }

    /**
     *  Page to show the articles of a user.
     *
     * @return mixed
     */
    public function actionArticles() 
    {
        $searchModel = new Article();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Subquery: all article IDs already used in courses
        $subQuery = (new Query())
            ->select('element_id')
            ->from('course_element')
            ->where(['element_type' => 'article']);

        $dataProvider->query
            ->andWhere(['user_id' => Yii::$app->user->id])           // only user's articles
            ->andWhere(['not in', 'id', $subQuery]);                 // exclude those in courses

        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        return $this->render('articles', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     *  Page to show the courses of a user.
     *
     * @return mixed
     */
    public function actionCourses() 
    {
        $searchModel = new Course();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['user_id' => Yii::$app->user->id]);
        
        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        return $this->render('courses', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     *  Page to show the articles bought of a user.
     *
     * @return mixed
     */
    public function actionArticlesBought() 
    {
        $searchModel = new Article();
        $articleDataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $articleDataProvider->query->andWhere(['is_public' => 1]);

        // Get bought articles by current user
        $articleTransactionModel = new Transaction();
        $articleTransactions = $articleTransactionModel->findByUserId(Yii::$app->user->id);

        // Extract article IDs from transactions
        $boughtArticleIds = array_map(function ($transaction) {
            return $transaction->article_id;
        }, $articleTransactions);

        // Filter articles to those bookmarked
        $articleDataProvider->query->andWhere(['id' => $boughtArticleIds]);

        
        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $transactionModel = new Transaction();
        $reviewModel = new ArticleReview();
        $bookmarkModel = new ArticleBookmark();

        return $this->render('articles-bought', [
            'model' => $searchModel,
            'dataProvider' => $articleDataProvider,
            'transactionModel' => $transactionModel,
            'reviewModel' => $reviewModel,
            'articleBookmarkModel' => $bookmarkModel,
        ]);
    }

    /**
     *  Page to show the courses bought of a user.
     *
     * @return mixed
     */
    public function actionCoursesBought() 
    {
        $searchModel = new Course();
        $courseDataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $courseDataProvider->query->andWhere(['is_public' => 1]);

        // Get bought courses by current user
        $courseTransactionModel = new Transaction();
        $courseTransactions = $courseTransactionModel->findByUserId(Yii::$app->user->id);

        // Extract course IDs from transactions
        $boughtCourseIds = array_map(function ($transaction) {
            return $transaction->course_id;
        }, $courseTransactions);

        // Filter courses to those bookmarked
        $courseDataProvider->query->andWhere(['id' => $boughtCourseIds]);

        
        if ($searchModel->category && !$searchModel->category_name) {
            $searchModel->category_name = Category::getName($searchModel->category);
        }

        $transactionModel = new Transaction();
        $reviewModel = new CourseReview();
        $bookmarkModel = new CourseBookmark();

        return $this->render('courses-bought', [
            'model' => $searchModel,
            'dataProvider' => $courseDataProvider,
            'transactionModel' => $transactionModel,
            'reviewModel' => $reviewModel,
            'courseBookmarkModel' => $bookmarkModel,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $this->layout = 'signup';
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->signup()) {
            //Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            Yii::$app->session->setFlash('success', 'Thank you for registration. You can now login.');
            //return $this->goHome();
            return $this->redirect(['user/login']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSettings()
    {
        $model = User::findOne(['id' => Yii::$app->user->id]);
        $changePasswordModel = new ChangePasswordForm();
    
        //$model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = UploadedFile::getInstanceByName('User[avatar]');
            if ($file) {
                $model->avatar_extension = $file->getExtension();
                if ($model->save()) {
                    if (!file_exists($model->getFolder(true))) {
                        @mkdir($model->getFolder(true), 0777, true);
                    }
                    if (file_exists($model->getFolder(true)) && $file->saveAs($model->getFilePath())) {
                        Yii::$app->session->setFlash('success', Yii::t('app', 'The changes were saved succesfully.'));
                    }
                }
            }
        }

        return $this->render('settings', [
            'userModel' => $model,
            'changePasswordModel' => $changePasswordModel,
        ]);
    }

    /**
     * change the password
     * @return Response
     */
    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Parola a fost schimbată cu succes.'));
            return $this->redirect(['user/settings']);
        }

        Yii::$app->session->setFlash('error', Yii::t('app', 'Parola nu a fost schimbată. Verifică datele introduse.'));
        return $this->redirect(['user/settings']);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', "Check your email for further instructions. If you don't find the email at first, <b>please also check the spam folder</b>.");

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);

    }

    /**
    * @param integer $id
    * @return array|false
    */
    public function actionFileUpload(int $id): array|false
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $file = UploadedFile::getInstanceByName('User[avatar]');
        if ($file) {
            $model->cover_extension = $file->getExtension();
            if ($model->save()) {
                if (!file_exists($model->getFolder(true))) {
                    @mkdir($model->getFolder(true), 0777, true);
                }
                if (file_exists($model->getFolder(true)) && $file->saveAs($model->getFilePath())) {
                    return [
                        'initialPreview' => $model->getSrc(),
                        'initialPreviewConfig' => [
                            [
                                'url' => Url::to(['user/file-delete', 'id' => $model->id]),
                                'type' => 'image',
                                'fileId' => $model->id,
                            ]
                        ],
                        'append' => true
                    ];
                }
            }
        }
        return false;
    }

    /**
     * @param integer $id
     * @return bool
     */
    public function actionFileDelete(int $id): bool
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        return unlink($model->getFilePath());
    }

    public function actionDeleteAvatar()
    {
        $user = Yii::$app->user->identity;

        // Delete file from disk
        if ($user->checkFileExists()) {
            @unlink($user->getFilePath());
            $user->avatar_extension =   '';
            $user->save(false);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Avatar has been deleted.'));
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'No avatar found.'));
        }

        return $this->redirect(['user/settings']);
    }

}
