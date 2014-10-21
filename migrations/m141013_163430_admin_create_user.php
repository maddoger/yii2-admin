<?php

use yii\db\Schema;
use yii\db\Migration;
use maddoger\admin\models\User;

class m141013_163430_admin_create_user extends Migration
{
    public function safeUp()
    {
        $validator = new \yii\validators\StringValidator(['min' => 1,]);
        $password = \yii\helpers\Console::prompt('Enter administrator password', [
            'default' => 'password',
            'required' => true,
            'validator' => [$validator, 'validate'],
        ]);
        $validator = new \yii\validators\EmailValidator();
        $email = \yii\helpers\Console::prompt('Enter administrator email', [
            'default' => 'admin@domain.com',
            'required' => true,
            'validator' => [$validator, 'validate'],
        ]);

        $this->insert('{{%admin_user}}', [
            'username' => 'admin',
            'password_hash' => \Yii::$app->security->generatePasswordHash($password),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'auth_key' => \Yii::$app->security->generateRandomString(32),
            'access_token' => \Yii::$app->security->generateRandomString(32),
            'email' => $email,
            'real_name' => 'Administrator',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        \yii\helpers\Console::output("Initial administration panel user was created!");
        \yii\helpers\Console::output("Username: admin\nPassword: {$password}");
    }

    public function safeDown()
    {
        $this->delete('{{%admin_user}}', 'username = :username', [
            ':username' => 'admin',
        ]);
    }
}
