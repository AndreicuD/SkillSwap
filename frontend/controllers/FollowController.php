<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\BaseController;

use yii\web\NotFoundHttpException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Follow;
use yii\web\Response;

/**
 * Follow controller
 */
class FollowController extends BaseController
{
    public $enableCsrfValidation = false;
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
                        'actions' => ['followers', 'following'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['toggle'],
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
    *   $id of who you want to follow;
    
    *   return array;
    */
    public function actionToggle($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::error('Follow toggle hit');
        Yii::error($_POST);

        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'You must be logged in.'];
        }

        $currentUserId = Yii::$app->user->id;
        $targetUserId = (int)$id;

        if ($currentUserId === $targetUserId) {
            return ['success' => false, 'message' => 'You cannot follow yourself.'];
        }

        $existingFollow = Follow::findOne(['from_user_id' => $currentUserId, 'to_user_id' => $targetUserId]);

        if ($existingFollow) {
            $existingFollow->delete();
            return ['success' => true, 'following' => false];
        } else {
            $follow = new Follow([
                'from_user_id' => $currentUserId,
                'to_user_id' => $targetUserId
            ]);
            if ($follow->save()) {
                return ['success' => true, 'following' => true];
            } else {
                return ['success' => false, 'message' => Json::encode($follow->errors)];
            }
        }
    }

    public function actionFollowers($id)
    {
        $user = User::findOne($id);
        if (!$user) throw new NotFoundHttpException();

        $followers = $user->followers;

        return $this->renderAjax('_follow_list', [
            'users' => $followers,
            'title' => 'Followers',
        ]);
    }

    public function actionFollowing($id)
    {
        $user = User::findOne($id);
        if (!$user) throw new NotFoundHttpException();

        $following = $user->following;

        return $this->renderAjax('_follow_list', [
            'users' => $following,
            'title' => 'Following',
        ]);
    }

}