<?php

use maddoger\admin\models\User;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel maddoger\admin\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('maddoger/admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <div class="panel panel-default">
        <div class="panel-body">

            <p>
                <?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('maddoger/admin', 'Create user'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    'username',
                    //'auth_key',
                    //'access_token',
                    //'password_hash',
                    // 'password_reset_token',
                    'email:email',
                    'real_name',
                    // 'avatar',
                    [
                        'attribute' => 'role',
                        'filter' => User::getRoleList(),
                        'value' => 'roleDescription'
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => User::getStatusList(),
                        'value' => 'statusDescription'
                    ],
                    //'last_visit_at:datetime',
                    // 'created_at',
                    // 'updated_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>

</div>
