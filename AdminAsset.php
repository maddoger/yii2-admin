<?php

namespace rusporting\admin;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
	public $sourcePath = '@rusporting/admin/assets';
	public $css = [
		'font-awesome/css/font-awesome.min.css',
		'js/select2/select2.css',
		'js/select2/select2-bootstrap.css',
		'css/fileinput.css',
		'css/datepicker.css',
		'css/sb-admin.less'
	];
	public $js = [
		'js/tablesorter/jquery.tablesorter.js',
		'js/select2/select2.min.js',
		'js/fileinput.js',
		'js/holder.js',
		'js/tree-editor.js',
		'js/bootstrap-datepicker.js',
		'js/common.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];
}