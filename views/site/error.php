<?php

use maddoger\admin\widgets\SearchForm;
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
    $code = YII_DEBUG ? $exception->getCode() : 500;
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
            <?= SearchForm::widget([
                'options' => [
                    'class' => 'search-form',
                ]
            ]) ?>

        <?php endif; ?>
    </div>
    <!-- /.error-content -->
</div><!-- /.error-page -->
