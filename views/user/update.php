<?php

/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\User */

$this->title = Yii::t('maddoger/admin', 'Update user:') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('maddoger/admin', 'Update');
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
