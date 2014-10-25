<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = Yii::t('maddoger/admin', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;

$this->params['bodyClass'] = 'bg-black';
?>
<div class="site-reset-password">

    <div class="form-box" id="request-password-box">
        <div class="header"><?= Html::encode($this->title) ?></div>
        <?php $form = ActiveForm::begin([
            'id' => 'reset-password-form',
            'fieldConfig' => [
                'template' => "{input}\n{hint}\n{error}\n",
            ],
        ]); ?>
        <div class="body bg-gray">

            <p><?= Yii::t('maddoger/admin', 'Please choose your new password:') ?></p>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

            <div class="footer">
                <?= Html::submitButton(Yii::t('maddoger/admin', 'Save'), ['class' => 'btn bg-olive btn-block']) ?>
                <p><?= Html::a(Yii::t('maddoger/admin', 'I remembered my old password!'), ['login']) ?></p>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
