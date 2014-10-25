<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin\assets;

use yii\web\AssetBundle;

/**
 * AdminIEAsset HTML5 fixes for IE8 and bellow
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger/yii2-admin
 */
class AdminIEAsset extends AssetBundle
{
    public $sourcePath = '@maddoger/admin/assets/dist';

    public $js = [
        'js/html5/html5shiv.min.js',
        'js/html5/respond.min.js',
        //'//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
        //'//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js',
    ];

    public $jsOptions = [
        'condition' => 'lte IE9',
        'position' => \yii\web\View::POS_HEAD,
    ];
}