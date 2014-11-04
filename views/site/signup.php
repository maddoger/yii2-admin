<?php
use maddoger\admin\widgets\Alerts;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \maddoger\admin\models\LoginForm */

$this->title = Yii::t('maddoger/admin', 'Administrator registration');
$this->params['bodyClass'] = 'bg-black';

?>
<div class="form-box" id="login-box">

    <?= Alerts::widget() ?>

    <div class="header"><?= $this->title ?></div>
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{input}\n{hint}\n{error}\n",
        ],
        'options' => [
            'autocomplete' => 'off',
        ],
    ]) ?>
    <div class="body bg-gray">
        <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')]); ?>
        <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')]); ?>
        <?= $form->field($model, 'real_name')->textInput(['placeholder' => $model->getAttributeLabel('real_name'), 'autocomplete' => 'off']); ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'autocomplete' => 'off']); ?>
    </div>
    <div class="footer">
        <?= Html::submitButton(Yii::t('maddoger/admin', 'Sign up'), ['class' => 'btn bg-olive btn-block']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>