<?php

namespace rusporting\admin;

use yii\web\AssetBundle;

class DateTimeEditorAsset extends AssetBundle
{
	public $sourcePath = '@rusporting/admin/assets';

	public $css = [
		'js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
	];

	public $js = [
		'js/moment-with-langs.min.js',
		'js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
	];

	public $depends = [
		'yii\bootstrap\BootstrapAsset',
	];
}