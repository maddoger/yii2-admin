<?php
/**
 * @var $this yii\web\View
 */

$this->title = \Yii::t('rusporting/admin', 'File manager');
$this->params['breadcrumbs'][] = $this->title;

echo rusporting\elfinder\Widget::widget(
	 array(
		 'connectorRoute' => 'files/connector',
	 )
);

