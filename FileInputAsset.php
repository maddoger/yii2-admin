<?php

namespace maddoger\admin;

use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle
{
	public $sourcePath = '@maddoger/admin/assets';

	public $css = [
		'css/fileinput.css',
	];

	public $js = [
		'js/fileinput.js',
		'js/holder.js',
	];

	public  $depends = [
		'yii\web\JqueryAsset',
	];
}