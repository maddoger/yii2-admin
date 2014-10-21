<?php

use maddoger\admin\models\Role;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('maddoger/admin', 'Create') : Yii::t('maddoger/admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Child roles') ?></div>
                </div>
                <div class="panel-body">

                    <?= $form->field($model, 'childRoles', ['template' => '{input}'])->checkboxList(Role::getRolesList($model->name), ['separator' => '<br/>', 'class' => 'checkbox-list']) ?>

                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/admin', 'Special permissions') ?></div>
                </div>
                <div class="panel-body">

                    <?= $form->field($model, 'childPermissions', ['template' => '{input}'])->checkboxList(Role::getPermissionsList(), ['separator' => '<br/>', 'class' => 'checkbox-list']) ?>

                </div>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
