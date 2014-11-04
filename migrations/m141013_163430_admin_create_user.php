<?php

use maddoger\admin\models\User;
use yii\db\Migration;

class m141013_163430_admin_create_user extends Migration
{
    public function safeUp()
    {
        $validator = new \yii\validators\StringValidator(['min' => 1,]);
        $username = \yii\helpers\Console::prompt('Enter username', [
            'default' => 'admin',
            'required' => true,
            'validator' => [$validator, 'validate'],
        ]);

        $validator = new \yii\validators\StringValidator(['min' => 0,]);
        $real_name = \yii\helpers\Console::prompt('Enter a real name', [
            'default' => 'Administrator',
            'validator' => [$validator, 'validate'],
        ]);

        $validator = new \yii\validators\EmailValidator();
        $email = \yii\helpers\Console::prompt('Enter email', [
            'default' => 'admin@domain.com',
            'required' => true,
            'validator' => [$validator, 'validate'],
        ]);

        $validator = new \yii\validators\StringValidator(['min' => 1,]);
        $password = \yii\helpers\Console::prompt('Enter password', [
            'default' => 'password',
            'required' => true,
            'validator' => [$validator, 'validate'],
        ]);

        $this->insert('{{%admin_user}}', [
            'password_hash' => \Yii::$app->security->generatePasswordHash($password),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'auth_key' => \Yii::$app->security->generateRandomString(32),
            'access_token' => \Yii::$app->security->generateRandomString(32),

            'username' => $username,
            'email' => $email,
            'real_name' => $real_name,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        \yii\helpers\Console::output("Initial administration panel user was created!");
        \yii\helpers\Console::output("Username: {$username}\nPassword: {$password}");
    }

    public function safeDown()
    {
    }
}
