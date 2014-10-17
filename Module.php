<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin;

use maddoger\core\BackendModule;
use maddoger\core\DynamicModel;
use Yii;

/**
 * Module
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger\admin
 */
class Module extends BackendModule
{
    /**
     * @var string URL to logo for admin panel
     */
    public $logoUrl;

    /**
     * @var string view for sidebar
     */
    public $sidebarView = '@maddoger/admin/views/layouts/_sidebar.php';

    /**
     * @var string view for main menu
     */
    public $menuView = '@maddoger/admin/views/layouts/_menu.php';

    /**
     * @var string view for user menu in the header
     */
    public $headerUserView = '@maddoger/admin/views/layouts/_headerUser.php';

    /**
     * @var string view for notifications menu in the header
     */
    public $headerNotificationsView = '@maddoger/admin/views/layouts/_headerNotifications.php';

    /**
     * Init module
     */
    public function init()
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['maddoger/admin'])) {

            Yii::$app->i18n->translations['maddoger/admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@maddoger/admin/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'maddoger/admin' => 'maddoger/admin.php',
                ],
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('maddoger/admin', 'Admin Panel Module');
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return 'dev';
    }

    /**
     * Returns configuration model
     *
     * @return \yii\base\Model
     */
    public function getConfigurationModel()
    {
        $model = new DynamicModel();

        //Logo url
        $model->defineAttribute('logoUrl', $this->logoUrl, Yii::t('maddoger/admin', 'Url to logo for admin panel'));
        $model->addRule('logoUrl', 'string');
        $model->addRule('logoUrl', 'default', ['value' => null]);

        //Sort number
        $model->defineAttribute('sortNumber', $this->sortNumber, Yii::t('maddoger/admin', 'Sort number'));
        $model->addRule('sortNumber', 'integer');
        $model->addRule('sortNumber', 'filter', ['filter' => 'intval']);

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function getNavigation()
    {
        return [
            [
                'label' => Yii::t('maddoger/admin', 'Admin panel'),
                'icon' => 'fa fa-gear',
                'items' => [
                    [
                        'label' => Yii::t('maddoger/admin', 'Users'),
                        'url' => ['/'.$this->id.'/users/index'],
                        'icon' => 'fa fa-user',
                    ],
                    [
                        'label' => Yii::t('maddoger/admin', 'User roles'),
                        'url' => ['/'.$this->id.'/roles/index'],
                        'icon' => 'fa fa-users',
                    ],
                    [
                        'label' => Yii::t('maddoger/admin', 'Modules'),
                        'url' => ['/'.$this->id.'/modules/index'],
                        'icon' => 'fa fa-gears',
                    ],
                ]
            ]
        ];
    }


}