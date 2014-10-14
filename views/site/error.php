<?php

use yii\helpers\Html;
use yii\web\HttpException;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

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
        <form class='search-form'>
            <div class='input-group'>
                <input type="text" name="search" class='form-control' placeholder="<?= Yii::t('maddoger/admin', 'Search') ?>"/>
                <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div><!-- /.input-group -->
        </form>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
