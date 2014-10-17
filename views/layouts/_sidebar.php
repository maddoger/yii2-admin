<?php

/**
 * @var $this yii\web\View
 * @var string $content
 */
use maddoger\admin\Module;
use yii\helpers\Html;

/**
 * @var \maddoger\admin\Module $adminModule
 */
$adminModule = Module::getInstance();
?>
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <?php
        /**
         * @var \maddoger\admin\models\User $user
         */
        $user = Yii::$app->user->getIdentity()
        ?>
        <div class="pull-left image">
            <?= $user->avatar ? Html::img($user->avatar, ['class' => 'img-circle', 'alt' => Yii::t('maddoger/admin', 'Avatar')]) : '' ?>
        </div>
        <div class="pull-left info">
            <p><?= Yii::t('maddoger/admin', 'Hello {username}', ['username' => Html::encode($user->getName())]) ?></p>

            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>
    <!-- search form -->
<?= Html::beginForm(['admin/site/search'], 'get', ['class' => 'sidebar-form']) ?>
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="<?= Yii::t('maddoger/admin', 'Search...') ?>"/>
        <span class="input-group-btn">
        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i
                                class="fa fa-search"></i></button>
        </span>
    </div>
<?= Html::endForm(); ?>
    <!-- /.search form -->
<?= $this->render($adminModule->menuView) ?>