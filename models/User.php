<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin\models;

use maddoger\core\file\FileBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\rbac\Item;
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
 * @property string $password write-only password
 * @property string $email
 * @property string $real_name
 * @property string $avatar
 * @property integer $role
 * @property integer $status
 * @property integer $last_visit_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $name read-only real_name or username
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_BLOCKED = 9;

    const ROLE_USER = 10;
    const ROLE_ADMIN = 1;

    /**
     * @var bool Delete avatar
     */
    public $delete_avatar;

    /**
     * @var array
     */
    private $_rbacRoles;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            //Avatar
            [
                'class' => FileBehavior::className(),
                'attribute' => 'avatar',
                'fileName' => 'id',
                'deleteAttribute' => 'delete_avatar',
                'basePath' => '@static/backend/avatars',
                'baseUrl' => '@staticUrl/backend/avatars',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['real_name', 'password'], 'string'],
            [['rbacRoles'], 'safe'],
            [['username'], 'string', 'min' => 3],
            [['email'], 'email'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_BLOCKED]],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]],
            [['username'], 'unique', 'message' => Yii::t('maddoger/admin', 'This username is already registered.')],
            [['email'], 'unique', 'message' => Yii::t('maddoger/admin', 'This email is already registered.')],
            //Avatar
            ['avatar', 'image', 'maxWidth' => 512, 'maxHeight' => 512],
            [['avatar'], 'default', 'value' => null],
            ['delete_avatar', 'boolean'],
            //Create
            [['username', 'email', 'password_hash'], 'required', 'on' => 'create'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        //Profile editing
        $scenarios['profile'] = ['username', 'password', 'email', 'avatar', 'real_name', 'delete_avatar'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('maddoger/admin', 'ID'),
            'username' => Yii::t('maddoger/admin', 'Username'),
            'auth_key' => Yii::t('maddoger/admin', 'Auth key'),
            'access_token' => Yii::t('maddoger/admin', 'Access token'),
            'password_hash' => Yii::t('maddoger/admin', 'Password hash'),
            'password' => Yii::t('maddoger/admin', 'Password'),
            'password_reset_token' => Yii::t('maddoger/admin', 'Password reset token'),
            'email' => Yii::t('maddoger/admin', 'Email'),
            'real_name' => Yii::t('maddoger/admin', 'Real name'),
            'avatar' => Yii::t('maddoger/admin', 'Avatar'),
            'role' => Yii::t('maddoger/admin', 'Role'),
            'roleDescription' => Yii::t('maddoger/admin', 'Role'),
            'status' => Yii::t('maddoger/admin', 'Status'),
            'statusDescription' => Yii::t('maddoger/admin', 'Status'),
            'last_visit_at' => Yii::t('maddoger/admin', 'Last visit at'),
            'created_at' => Yii::t('maddoger/admin', 'Created at'),
            'updated_at' => Yii::t('maddoger/admin', 'Updated at'),
            'rbacRoles' => Yii::t('maddoger/admin', 'Roles'),
            'delete_avatar' => Yii::t('maddoger/admin', 'Delete avatar'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateAuthKey();
            //$this->generateAccessToken();
        }
        return parent::beforeSave($insert);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(32);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        //RBAC Roles
        if ($this->isAttributeSafe('rbacRoles')) {

            if (!$insert) {
                Yii::$app->authManager->revokeAll($this->id);
            }
            if ($this->_rbacRoles) {

                foreach ($this->_rbacRoles as $roleName) {
                    $role = Yii::$app->authManager->getRole($roleName);
                    if (!$role) {
                        continue;
                    }
                    Yii::$app->authManager->assign($role, $this->id);
                }
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        Yii::$app->authManager->revokeAll($this->id);
        parent::afterDelete();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->real_name ?: $this->username;
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
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
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

    public function getPassword()
    {
        return null;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        if (!empty($password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }
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

    /**
     * @return null
     */
    public function getRbacRoles()
    {
        if ($this->_rbacRoles === null) {
            $roles = Yii::$app->authManager->getRolesByUser($this->id);
            if ($roles) {
                foreach ($roles as $child) {
                    if ($child->type == Item::TYPE_ROLE) {
                        $this->_rbacRoles[] = $child->name;
                    }
                }
            }
        }
        return $this->_rbacRoles;
    }

    /**
     * @param $value
     */
    public function setRbacRoles($value)
    {
        $this->_rbacRoles = $value;
    }

    /**
     * Role sting representation
     * @return string
     */
    public function getRoleDescription()
    {
        static $list = null;
        if ($list === null) {
            $list = static::getRoleList();
        }
        return (isset($list[$this->role])) ? $list[$this->role] : $this->role;
    }

    /**
     * List of all possible roles
     * @return array
     */
    public static function getRoleList()
    {
        return [
            self::ROLE_USER => Yii::t('maddoger/admin', 'User'),
            self::ROLE_ADMIN => Yii::t('maddoger/admin', 'Admin'),
        ];
    }

    /**
     * Status sting representation
     * @return string
     */
    public function getStatusDescription()
    {
        static $list = null;
        if ($list === null) {
            $list = static::getStatusList();
        }
        return (isset($list[$this->status])) ? $list[$this->status] : $this->status;
    }

    /**
     * List of all possible statuses
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('maddoger/admin', 'Active'),
            self::STATUS_BLOCKED => Yii::t('maddoger/admin', 'Blocked'),
            self::STATUS_DELETED => Yii::t('maddoger/admin', 'Deleted'),
        ];
    }


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
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($token === null) {
            return null;
        }
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
            Yii::$app->params['user.passwordResetTokenExpire'] : 60 * 60 * 24;
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Update last visit time event handler
     *
     *
     * in user component:
     * 'on afterLogin'   => ['maddoger\admin\models\User', 'updateLastVisit'],
     * 'on afterLogout'  => ['maddoger\admin\models\User', 'updateLastVisit'],
     * @param $event
     * @return bool
     */
    public static function updateLastVisit($event)
    {
        if ($event->isValid) {
            /**
             * @var User $user
             */
            $user = $event->identity;
            $user->last_visit_at = time();
            $user->updateAttributes(['last_visit_at']);
            return true;
        }
        return false;
    }

}
