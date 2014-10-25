<?php

/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\Role */

$this->title = Yii::t('maddoger/admin', 'Update user role:') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/admin', 'User roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('maddoger/admin', 'Update');
?>
<div class="role-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
