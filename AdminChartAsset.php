<?php

namespace maddoger\admin;

use yii\web\AssetBundle;

class AdminChartAsset extends AssetBundle
{
	public $sourcePath = '@maddoger/admin/assets';
	public $css = [];
	public $js = [
		'js/flot/chart-data-flot.js',
		'js/flot/excanvas.min.js',
		'js/flot/jquery.flot.js',
		'js/flot/jquery.flot.pie.js',
		'js/flot/jquery.flot.resize.js',
		'js/flot/jquery.flot.tooltip.min.js',
		'js/morris/chart-data-morris.js',
	];
	public $depends = [
		'maddoger\admin\AdminAsset',
	];
}