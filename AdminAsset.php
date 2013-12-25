<?php

namespace rusporting\admin;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
	public $sourcePath = '@rusporting/admin/assets';
	public $css = ['css/sb-admin.less', 'font-awesome/css/font-awesome.min.css'];
	public $js = ['js/tablesorter/jquery.tablesorter.js', 'js/tablesorter/tables.js'];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];
}