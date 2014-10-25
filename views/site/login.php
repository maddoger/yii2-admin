<?php
use maddoger\admin\widgets\Alerts;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \maddoger\admin\models\LoginForm */

$this->title = Yii::t('maddoger/admin', 'Sign in');
$this->params['bodyClass'] = 'bg-black';

?>
<div class="form-box" id="login-box">

    <?= Alerts::widget() ?>

    <div class="header"><?= $this->title ?></div>
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{input}\n{hint}\n{error}\n",
        ],
    ]) ?>
    <div class="body bg-gray">
        <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')]); ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]); ?>
        <?= $form->field($model, 'rememberMe')->checkbox(); ?>
    </div>
    <div class="footer">
        <?= Html::submitButton(Yii::t('maddoger/admin', 'Sign me in'), ['class' => 'btn bg-olive btn-block']) ?>
        <p><?= Html::a(Yii::t('maddoger/admin', 'I forgot my password'), ['request-password-reset']) ?></p>

    </div>
    <?php ActiveForm::end() ?>
</div>