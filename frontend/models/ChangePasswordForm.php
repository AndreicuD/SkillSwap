<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Change Password Form
 */
class ChangePasswordForm extends Model
{
    public $current_password;
    public $new_password;
    public $confirm_password;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['current_password', 'new_password', 'confirm_password'], 'required'],
            ['current_password', 'validateCurrentPassword'],
            ['new_password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Parolele nu se potrivesc.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'current_password' => Yii::t('app', 'Your current password'),
            'new_password' => Yii::t('app', 'The new password'),
            'confirm_password' => Yii::t('app', 'Confirm the new password'),
        ];
    }

    /**
     * Validates the current password.
     */
    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !Yii::$app->security->validatePassword($this->current_password, $user->password_hash)) {
                $this->addError($attribute, Yii::t('app', 'The current password is wrong'));
            }
        }
    }

    /**
     * Changes the password of the user
     *
     * @return bool whether the password was successfully changed
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->setPassword($this->new_password);
            $user->generateAuthKey(); // Update auth key for security

            return $user->save(false);
        }

        return false;
    }

    /**
     * Finds the logged-in user
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(Yii::$app->user->id);
        }

        return $this->_user;
    }
}