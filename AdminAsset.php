<?php

namespace maddoger\admin;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
	public $sourcePath = '@maddoger/admin/assets';
	public $css = [
		'font-awesome/css/font-awesome.min.css',
		'js/select2/select2.css',
		'js/select2/select2-bootstrap.css',
		'css/fileinput.css',
		'css/sb-admin.less'
	];
	public $js = [];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];

	public function init() {
		$this->js = [
			'js/tablesorter/jquery.tablesorter.js',
			'js/select2/select2.min.js',
			'js/select2/select2_locale_'.\Yii::$app->language.'.js',
			'js/fileinput.js',
			'js/holder.js',
			'js/tree-editor.js',
			'js/common.js',
		];
		parent::init();
	}
}