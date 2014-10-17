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
 * @package maddoger\admin
 */
class AdminAsset extends AssetBundle
{
    public $sourcePath = '@maddoger/admin/assets/dist';

    public $css = [
        'css/admin.css',
    ];
    public $js = [
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