<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $firstname;
    public $lastname;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [['email','firstname', 'lastname'], 'string', 'max' => 254],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            [['firstname', 'lastname'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'firstname' => Yii::t('app', 'First name'),
            'lastname' => Yii::t('app', 'Last name'),
            'status' => Yii::t('app', 'Status'),
            'password' => Yii::t('app', 'Password'),
            'password_confirmation' => Yii::t('app', 'Password confirmation'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool|null whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        /* 9 = needs validation, 10 = activ */
        $user->status = 10;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        //$user->generateEmailVerificationToken();

        return $user->save() /*&& $this->sendEmail($user)*/;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
