<?php

namespace maddoger\admin\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $name
 * @property string $avatar
 * @property integer $role
 * @property integer $status
 * @property integer $last_visit_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['avatar', 'real_name'], 'string'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]],

            [['username'], 'unique', 'message' => Yii::t('admin', 'This username is already registered.')],
            [['email'], 'unique', 'message' => Yii::t('admin', 'This email is already registered.')],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'ID'),
            'username' => Yii::t('admin', 'Username'),
            'auth_key' => Yii::t('admin', 'Auth key'),
            'access_token' => Yii::t('admin', 'Access token'),
            'password_hash' => Yii::t('admin', 'Password hash'),
            'password_reset_token' => Yii::t('admin', 'Password reset token'),
            'email' => Yii::t('admin', 'Email'),
            'real_name' => Yii::t('admin', 'Real name'),
            'avatar' => Yii::t('admin', 'Avatar'),
            'role' => Yii::t('admin', 'Role'),
            'status' => Yii::t('admin', 'Status'),
            'last_visit_at' => Yii::t('admin', 'Last visit at'),
            'created_at' => Yii::t('admin', 'Created at'),
            'updated_at' => Yii::t('admin', 'Updated at'),
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = isset(Yii::$app->params['user.passwordResetTokenExpire']) ?
            Yii::$app->params['user.passwordResetTokenExpire'] : 60*60*24;
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(32);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString(32);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
