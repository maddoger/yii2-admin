<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\Role */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/admin', 'User roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$childRoles = $model->getChildRoles();
if ($childRoles) {
    $childRoles = array_intersect_key($model->getRolesList(), array_flip($childRoles));
}
$childPermissions = $model->getChildPermissions();
if ($childPermissions) {
    $childPermissions = array_intersect_key($model->getPermissionsList(), array_flip($childPermissions));
}
?>
<div class="role-view">
    <div class="panel panel-default">
        <div class="panel-body">
            <p>
                <?= Html::a(Yii::t('maddoger/admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('maddoger/admin', 'Delete'), ['delete', 'id' => $model->name], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('maddoger/admin', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'description:ntext',
                    [
                        'attribute' => 'childRoles',
                        'value' => $childRoles ? Html::ul($childRoles, ['class' => 'list-unstyled']) : null,
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'childPermissions',
                        'value' => $childPermissions ? Html::ul($childPermissions, ['class' => 'list-unstyled']) : null,
                        'format' => 'html',
                    ],

                    //'rule_name',
                    //'data:ntext',
                ],
            ]) ?>
        </div>
    </div>

</div>
