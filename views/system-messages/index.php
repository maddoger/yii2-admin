<?php

/* @var yii\web\View $this */
/* @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('maddoger/admin', 'System messages');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-system-messages">
    <div class="panel panel-info">
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'label' => Yii::t('maddoger/admin', 'Created at'),
                    ],
                    [
                        'attribute' => 'title',
                        'value' => function ($model, $key, $index, $column) {
                            return Html::a(Html::encode($model->title), ['view', 'id' => $model->id], ['title' => Yii::t('maddoger/admin', 'More info')]);
                        },
                        'format' => 'html',
                        'label' => Yii::t('maddoger/admin', 'Title'),
                    ],
                    [
                        'attribute' => 'message',
                        'format' => 'text',
                        'label' => Yii::t('maddoger/admin', 'Message'),
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <p class="pull-right">
        <?= Html::a(Yii::t('maddoger/admin', 'Delete all'), ['delete-all'], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('maddoger/admin', 'Are you sure you want to delete all items?'),
                'method' => 'post',
            ]
        ]) ?>
    </p>
</div>