<?php

namespace maddoger\admin;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
	public $sourcePath = '@maddoger/admin/assets';
	public $css = [
		'font-awesome/css/font-awesome.min.css',
		'css/sb-admin.css'
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
			'js/holder.js',
			'js/tree-editor.js',
			'js/common.js',
		];
		parent::init();
	}
}