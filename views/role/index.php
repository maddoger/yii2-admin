<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('maddoger/admin', 'User roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="clearfix">
            <?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('maddoger/admin', 'Create new role'), ['create'], ['class' => 'btn btn-success']) ?>

                <div class="btn-group pull-right">
                    <?= Html::a(
                        Html::tag('i', '', ['class' => 'glyphicon glyphicon-refresh']) . ' ' .
                        Yii::t('maddoger/admin', 'Update from modules'),
                        ['update-from-modules'],
                        ['class' => 'btn btn-default']) ?>

                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><?= Html::a(
                                Yii::t('maddoger/admin', 'Remove all and update from modules'),
                                ['update-from-modules', 'remove_all' => 1]) ?></li>
                    </ul>
                </div>

            </div>
            <br />

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [

                [
                    'attribute' => 'description',
                    'label' => Yii::t('maddoger/admin', 'Description'),
                ],
                [
                    'attribute' => 'name',
                    'label' => Yii::t('maddoger/admin', 'Role identifier'),
                ],
                //'rule_name',
                //'data:ntext',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        </div>

    </div>

</div>
