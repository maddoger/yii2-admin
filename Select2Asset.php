<?php

namespace maddoger\admin;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
	public $sourcePath = '@maddoger/admin/assets';

	public $css = [
		'js/select2/select2.css',
		'js/select2/select2-bootstrap.css',
	];

	public $js = [];

	public  $depends = [
		'yii\web\JqueryAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];

	public function init() {
		$this->js = [
			'js/select2/select2.min.js',
			'js/select2/select2_locale_'.\Yii::$app->language.'.js',
		];
		parent::init();
	}
}