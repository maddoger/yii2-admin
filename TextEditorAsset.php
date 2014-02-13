<?php

namespace maddoger\admin;

use yii\web\AssetBundle;

class TextEditorAsset extends AssetBundle
{
	public $sourcePath = '@maddoger/admin/assets';

	public $js = [
		'js/ckeditor/ckeditor.js',
		'js/ckeditor/adapters/jquery.js',
	];

	public  $depends = [
		'yii\web\JqueryAsset',
	];
}