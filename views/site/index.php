<?php

/* @var $this yii\web\View */

$this->title = Yii::t('maddoger/admin', 'Dashboard');

/**
 * @var maddoger\admin\models\User $user
 */
$user = Yii::$app->user->identity;

?>
Wellcome to Dashboard!