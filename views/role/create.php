<?php

/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\Role */

$this->title = Yii::t('maddoger/admin', 'Create user role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/admin', 'User roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
