<?php

use maddoger\admin\models\Role;
use maddoger\admin\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Authentication') ?></div>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off', 'value' => '']) ?>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Bio') ?></div>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'real_name')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($model, 'avatar', [
                        'template' => '{label} <br />'.($model->avatar ? Html::img($model->avatar) : '').'{input} {hint} {error}',
                    ])->fileInput() ?>
                    <?= $form->field($model, 'delete_avatar')->checkbox() ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Roles') ?></div>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'rbacRoles', ['template' => '{input}'])->checkboxList(Role::getRolesList($model->name)) ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Status') ?></div>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'role')->dropDownList(User::getRoleList()) ?>
                    <?= $form->field($model, 'status')->dropDownList(User::getStatusList()) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="btn-group">
            <?= Html::submitButton(Yii::t('maddoger/admin', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::submitButton(Yii::t('maddoger/admin', 'Save and exit'), ['name' => 'redirect', 'value' => 'exit', 'class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::t('maddoger/admin', 'Save and create new'), ['name' => 'redirect', 'value' => 'new', 'class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
