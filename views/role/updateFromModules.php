<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $log array */
/* @var $hasError bool */

$this->title = Yii::t('maddoger/admin', 'Update user roles from modules');
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/admin', 'User roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-view">
    <div class="panel <?= $hasError ? 'panel-danger' : 'panel-success' ?>">
        <div class="panel-heading">
            <span class="panel-title"><?= Yii::t('maddoger/admin', 'Log') ?></span> &nbsp;
            <?= Html::a(Yii::t('maddoger/admin', 'Go back'), ['index'], ['class' => 'btn btn-xs btn-default']) ?>
        </div>
        <ul class="list-group">
        <?php
            foreach ($log as $item) {
                $content = isset($item[1]) ? $item[1] : $item[0];
                $class = isset($item[1]) ? $item[0] : '';
                $options = [
                    'class' => 'list-group-item',
                ];
                if ($class == 'error') {
                    Html::addCssClass($options, 'list-group-item-danger');
                } elseif ($class == 'warning') {
                    Html::addCssClass($options, 'list-group-item-warning');
                }
                echo Html::tag('li', $item[1], $options);
            }
        ?>
        </ul>
    </div>
</div>
