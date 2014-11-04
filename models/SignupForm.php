<?php
namespace maddoger\admin\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $real_name;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\maddoger\admin\models\User', 'message' => Yii::t('maddoger/admin', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\maddoger\admin\models\User', 'message' => Yii::t('maddoger/admin', 'This email address has already been taken.')],

            ['real_name', 'string'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('maddoger/admin', 'Username'),
            'email' => Yii::t('maddoger/admin', 'Email'),
            'real_name' => Yii::t('maddoger/admin', 'Real name'),
            'password' => Yii::t('maddoger/admin', 'Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->real_name = $this->real_name;
            $user->setPassword($this->password);
            $user->save();
            return $user;
        }

        return null;
    }
}
