<?php


/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\User */

$this->title = Yii::t('maddoger/admin', 'Create user');
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
