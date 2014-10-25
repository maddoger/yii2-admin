<?php

use maddoger\admin\models\Role;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Description') ?></div>
                </div>
                <div class="panel-body">

                    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>


                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('maddoger/admin', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Child roles') ?></div>
                </div>
                <div class="panel-body">

                    <?= $form->field($model, 'childRoles', ['template' => '{input}'])->checkboxList(Role::getRolesList($model->name)) ?>

                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Special permissions') ?></div>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'childPermissions', ['template' => '{input}'])->checkboxList(Role::getPermissionsList()) ?>
                </div>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
