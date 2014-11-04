<?php

use yii\helpers\Html;
use yii\web\HttpException;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception HttpException */

$this->title = $name;

if ($exception instanceof HttpException) {
    $code = $exception->statusCode;
} else {
    $code = $exception->getCode();
}

?>
<div class="error-page">
    <h2 class="headline text-info"> <?= $code ?></h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> <?= Html::encode($this->title) ?></h3>
        <p class="message">
            <?= nl2br(Html::encode($message)) ?>
        </p>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <?= Html::beginForm(['admin/site/search'], 'get', ['class' => 'search-form']) ?>
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="<?= Yii::t('maddoger/admin', 'Search...') ?>"/>
                        <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                    </span>
        </div>
        <?= Html::endForm(); ?>
        <?php endif; ?>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
