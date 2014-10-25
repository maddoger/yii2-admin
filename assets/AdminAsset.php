<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin\assets;

use yii\web\AssetBundle;

/**
 * AdminAsset
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger/yii2-admin
 */
class AdminAsset extends AssetBundle
{
    public $sourcePath = '@maddoger/admin/assets/dist';

    public $css = [
        'css/app.css',
    ];
    public $js = [
        'js/slimscroll/jquery.slimscroll.min.js',
        'js/app.js',
    ];

    public $depends = [
        'maddoger\admin\assets\AdminIEAsset',
        'maddoger\admin\assets\FontAwesomeAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}