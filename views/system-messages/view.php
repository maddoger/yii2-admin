<?php

/* @var yii\web\View $this */
/* @var yii\data\ActiveDataProvider $dataProvider */
/* @var maddoger\core\models\SystemMessage $model */

use yii\helpers\Html;
use yii\helpers\VarDumper;

$this->title = Yii::t('maddoger/admin', 'System message #{id}', ['id' => $model->id]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/admin', 'System messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$createdBy = null;
if ($model->created_by) {
    $userClass = Yii::$app->user->identityClass;
    $createdByUser = $userClass::findOne($model->created_by);
    if ($createdByUser) {
        $createdBy = Html::a($createdByUser->username, ['/admin/user/view', 'id' => $model->created_by]);
    }
}

?>
<div class="system-messages-view">

    <div class="panel panel-info">
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt><?= Yii::t('maddoger/admin', 'Created at') ?></dt>
                <dd><?= Yii::$app->formatter->asDatetime($model->created_at, 'long') ?></dd>
                <dt><?= Yii::t('maddoger/admin', 'User ID') ?></dt>
                <dd><?= $createdBy ?: 'system' ?></dd>
                <dt><?= Yii::t('maddoger/admin', 'Title') ?></dt>
                <dd><?= Yii::$app->formatter->asHtml($model->title) ?></dd>
                <dt><?= Yii::t('maddoger/admin', 'Message') ?></dt>
                <dd><?= Yii::$app->formatter->asNtext($model->message) ?></dd>
                <dt><?= Yii::t('maddoger/admin', 'Data') ?></dt>
                <dd><?= VarDumper::dumpAsString($model->data, 10, true) ?></dd>
            </dl>
        </div>
    </div>
    <p class="pull-right">
        <?= Html::a(Yii::t('maddoger/admin', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('maddoger/admin', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ]
        ]) ?>
    </p>
</div>