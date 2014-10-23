<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use maddoger\admin\models\Role;
use maddoger\admin\models\User;

/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\User */

$this->title = Yii::t('maddoger/admin', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title"><?= Yii::t('maddoger/admin', 'Authentication') ?></div>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title"><?= Yii::t('maddoger/admin', 'Bio') ?></div>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'real_name')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'avatar')->textInput(['maxlength' => 255]) ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('maddoger/admin', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

            </div>
        </div>

        <?php ActiveForm::end(); ?>

</div>
