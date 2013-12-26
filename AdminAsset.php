<?php

namespace rusporting\admin;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
	public $sourcePath = '@rusporting/admin/assets';
	public $css = ['css/sb-admin.less', 'font-awesome/css/font-awesome.min.css', 'js/select2/select2.css', 'js/select2/select2-bootstrap.css'];
	public $js = ['js/tablesorter/jquery.tablesorter.js', 'js/select2/select2.min.js', 'js/common.js'];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];
}