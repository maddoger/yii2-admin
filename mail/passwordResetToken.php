<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user maddoger\admin\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['admin/site/reset-password', 'token' => $user->password_reset_token]);

echo Yii::t('maddoger/admin', 'Hello {username}', ['username' => Html::encode($user->real_name ?: $user->username)]), "\n\n",

Yii::t('maddoger/admin', "Follow the link below to reset your password:\n{link}", ['link' => Html::a(Html::encode($resetLink), $resetLink)]);
