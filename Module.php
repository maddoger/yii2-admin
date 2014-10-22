<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin;

use maddoger\core\BackendModule;
use maddoger\core\DynamicModel;
use Yii;
use yii\rbac\Item;

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
     * @var string superuser role name.
     * It will be used as a parent for all RBAC roles loaded from modules.
     */
    public $superUserRole = 'superuser';

    /**
     * @var int superuser id
     * This user will get all RBAC roles loaded from modules.
     */
    public $superUserId;

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
                        'url' => ['/' . $this->id . '/user/index'],
                        'activeUrl' => '/' . $this->id . '/user/*',
                        'icon' => 'fa fa-user',
                    ],
                    [
                        'label' => Yii::t('maddoger/admin', 'User roles'),
                        'url' => ['/' . $this->id . '/role/index'],
                        'activeUrl' => '/' . $this->id . '/role/*',
                        'icon' => 'fa fa-users',
                    ],
                    /*[
                        'label' => Yii::t('maddoger/admin', 'Modules'),
                        'url' => ['/'.$this->id.'/modules/index'],
                        'icon' => 'fa fa-gears',
                    ],*/
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRbacItems()
    {
        return [
            //Users
            'admin.user.dashboard' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. Access to dashboard'),
                ],
            'admin.user.profile' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. Update own profile'),
                ],
            'admin.user.view' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. View admins'),
                ],
            'admin.user.create' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. Create admins'),
                ],
            'admin.user.update' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. Update admins'),
                ],
            'admin.user.delete' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. Delete admins'),
                ],
            'admin.user.manager' =>
                [
                    'type' => Item::TYPE_ROLE,
                    'description' => Yii::t('maddoger/admin', 'Admin. Manage admins'),
                    'children' => [
                        'admin.user.view',
                        'admin.user.create',
                        'admin.user.update',
                        'admin.user.delete',
                    ],
                ],
            //RBAC
            'admin.rbac.updateFromModules' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. Update user roles from modules'),
                ],
            'admin.rbac.manageRoles' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. Create, update and delete user roles'),
                ],
            'admin.rbac.manager' =>
                [
                    'type' => Item::TYPE_ROLE,
                    'description' => Yii::t('maddoger/admin', 'Admin. Manage user roles'),
                    'children' => [
                        'admin.rbac.manageRoles',
                        'admin.rbac.updateFromModules',
                    ]
                ],
            //Admin
            'admin.base' =>
                [
                    'type' => Item::TYPE_ROLE,
                    'description' => Yii::t('maddoger/admin', 'Admin. Base access to admin panel'),
                    'children' => [
                        'admin.user.dashboard',
                        'admin.rbac.updateFromModules',
                    ]
                ],
        ];
    }

}