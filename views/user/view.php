<?php

use maddoger\admin\models\Role;
use maddoger\admin\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model maddoger\admin\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$childRoles = $model->getRbacRoles();
if ($childRoles) {
    $childRoles = array_intersect_key(Role::getRolesList(), array_flip($childRoles));
}

?>
<div class="user-view">

    <div class="panel panel-default">
        <div class="panel-body">
            <p>
                <?= Html::a(Yii::t('maddoger/admin', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('maddoger/admin', 'Delete'), ['delete', 'id' => $model->id], [
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
                    'id',
                    'username',
                    //'auth_key',
                    //'access_token',
                    //'password_hash',
                    //'password_reset_token',
                    'email:email',
                    'real_name',
                    'avatar:image',
                    'roleDescription',
                    [
                        'attribute' => 'statusDescription',
                        'value' => Html::tag('span', $model->getStatusDescription(), ['class' =>
                            $model->status == User::STATUS_ACTIVE ? 'label label-success' :
                                ($model->status == User::STATUS_DELETED ? 'label label-danger' :
                                    $model->status == User::STATUS_BLOCKED ? 'label label-warning' :
                                        'label label-info'
                                )
                        ]),
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'rbacRoles',
                        'value' => $childRoles ? Html::ul($childRoles, ['class' => 'list-unstyled']) : null,
                        'format' => 'html',
                    ],

                    'last_visit_at:datetime',

                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

</div>
