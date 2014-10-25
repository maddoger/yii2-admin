<?php
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = Yii::t('maddoger/admin', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;

$this->params['bodyClass'] = 'bg-black';

?>
<div class="site-request-password-reset">

    <div class="form-box" id="request-password-box">
        <div class="header"><?= Html::encode($this->title) ?></div>
        <?php $form = ActiveForm::begin([
            'id' => 'request-password-reset-form',
            'fieldConfig' => [
                'template' => "{input}\n{hint}\n{error}\n",
            ],
        ]); ?>
        <div class="body bg-gray">

        <p><?= Yii::t('maddoger/admin', 'Please fill out your email. A link to reset password will be sent there.') ?></p>

                <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>
                <?= $form->field($model, 'code')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-5">{image}</div><div class="col-lg-7">{input}</div></div>',
                    'options' => [
                        'placeholder' => $model->getAttributeLabel('code'),
                        'class' => 'form-control',
                    ],
                ]) ?>

            <div class="footer">
                <?= Html::submitButton(Yii::t('maddoger/admin', 'Send'), ['class' => 'btn bg-olive btn-block']) ?>
                <p><?= Html::a(Yii::t('maddoger/admin', 'I remembered my password!'), ['login']) ?></p>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
