<?php

/**
 * @var $this yii\web\View
 * @var string $content
 */
use maddoger\admin\Module;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \maddoger\admin\Module $adminModule
 */
$adminModule = Module::getInstance();

/**
 * @var \maddoger\admin\models\User $user
 */
$user = Yii::$app->user->getIdentity()
?>
<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="glyphicon glyphicon-user"></i>
        <span><?= Html::encode($user->getName()) ?> <i class="caret"></i></span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header bg-light-blue">
            <?= $user->avatar ? Html::img($user->avatar, ['class' => 'img-circle', 'alt' => Yii::t('maddoger/admin', 'Avatar')]) : '' ?>
            <p>
                <?= Html::encode($user->getName()) ?>
                <small><?= Yii::t('maddoger/admin', 'Member since {0, date, MMM. yyyy}', $user->created_at) ?></small>
            </p>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <a href="<?= Url::to(['/'.$adminModule->id.'/user/profile']) ?>" class="btn btn-default btn-flat"><?= Yii::t('maddoger/admin', 'Profile') ?></a>
            </div>
            <div class="pull-right">
                <a href="<?= Url::to(['/'.$adminModule->id.'/site/logout']) ?>" class="btn btn-default btn-flat"><?= Yii::t('maddoger/admin', 'Sign out') ?></a>
            </div>
        </li>
    </ul>
</li>