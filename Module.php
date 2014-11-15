<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin;

use maddoger\core\BackendModule;
use Yii;
use yii\rbac\Item;

/**
 * Module
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger/yii2-admin
 */
class Module extends BackendModule
{
    /**
     * @var string URL to logo for admin panel
     */
    public $logoUrl;

    /**
     * @var string logo text
     */
    public $logoText;

    /**
     * @var string
     */
    public $dashboardView;

    /**
     * @var array menu items for sidebar menu
     */
    public $sidebarMenu;

    /**
     * @var bool append modules navigation to sidebar menu
     */
    public $sidebarMenuUseModules = true;

    /**
     * @var int time in seconds for sidebar menu cache
     * 0 - infinity
     * false - disable caching
     * Default: 60 seconds
     */
    public $sidebarMenuCache = 60;

    /**
     * @var string view for sidebar
     */
    public $sidebarView = '@maddoger/admin/views/layouts/_sidebar.php';

    /**
     * @var string view for main menu
     */
    public $sidebarMenuView = '@maddoger/admin/views/layouts/_sidebarMenu.php';

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
     * @var bool
     */
    public $searchUseModulesSources = true;

    /**
     * @var array additional search sources
     */
    public $searchSources;

    /**
     * @var bool
     */
    public $sendSystemMessageOnServerError = true;

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
                        'roles' => ['admin.user.view'],
                    ],
                    [
                        'label' => Yii::t('maddoger/admin', 'User roles'),
                        'url' => ['/' . $this->id . '/role/index'],
                        'activeUrl' => '/' . $this->id . '/role/*',
                        'icon' => 'fa fa-users',
                        'roles' => ['admin.rbac.manageRoles'],
                    ],
                    [
                        'label' => Yii::t('maddoger/admin', 'System messages'),
                        'url' => ['/' . $this->id . '/system-messages/index'],
                        'activeUrl' => '/' . $this->id . '/system-messages/*',
                        'icon' => 'fa fa-warning',
                        'roles' => ['admin.system-messages.viewList'],
                    ],
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
            'admin.system-messages.viewList' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. View system messages'),
                ],
            'admin.system-messages.viewDetail' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. View system messages with details'),
                ],
            'admin.system-messages.delete' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/admin', 'Admin. Delete system messages'),
                ],
            'admin.base' =>
                [
                    'type' => Item::TYPE_ROLE,
                    'description' => Yii::t('maddoger/admin', 'Admin. Base access to admin panel'),
                    'children' => [
                        'admin.user.dashboard',
                        'admin.user.profile',
                        'admin.system-messages.viewList',
                    ]
                ],
        ];
    }

    /**
     * @return array
     */
    public function getSearchSources()
    {
        return [
            [
                'class' => '\maddoger\core\search\ArraySearchSource',
                'data' => [
                    [
                        'label' => Yii::t('maddoger/admin', 'Users'),
                        'url' => ['/' . $this->id . '/user/index'],
                    ],
                    [
                        'label' => Yii::t('maddoger/admin', 'User roles'),
                        'url' => ['/' . $this->id . '/role/index'],
                    ],
                ],
                'roles' => ['admin.user.view', 'admin.rbac.manageRoles'],
            ],
            [
                'class' => '\maddoger\core\search\ArraySearchSource',
                'data' => [
                    [
                        'label' => Yii::t('maddoger/admin', 'System messages'),
                        'url' => ['/' . $this->id . '/system-messages/index'],
                    ],
                ],
                'roles' => ['admin.system-messages.viewList'],
            ],
            [
                'class' => '\maddoger\core\search\ActiveSearchSource',
                'modelClass' => '\maddoger\admin\models\User',
                'searchAttributes' => ['username', 'email', 'real_name'],
                'url' => ['/' . $this->id . '/user/view', 'id' => null],
                'label' => 'username',
                'labelPrefix' => Yii::t('maddoger/admin', 'User - '),
                'roles' => ['admin.user.view'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSidebarMenu()
    {
        $menu = null;

        $cacheKey = 'ADMIN_SIDEBAR_MENU';
        if ($this->sidebarMenuCache !== false) {
            $menu = Yii::$app->cache->get($cacheKey);
        }

        if (!$menu) {

            $menu = $this->sidebarMenu ?:
                [
                    [
                        'label' => Yii::t('maddoger/admin', 'Dashboard'),
                        'icon' => 'fa fa-dashboard',
                        'url' => ['/' . Module::getInstance()->id . '/site/index'],
                        'sort' => -1,
                    ],
                ];

            if ($this->sidebarMenuUseModules) {

                $sortIndex = 0;

                //Get navigation from modules
                foreach (Yii::$app->modules as $moduleId => $module) {

                    if (!($module instanceof \yii\base\Module)) {
                        $module = Yii::$app->getModule($moduleId, true);
                    }

                    if ($module instanceof BackendModule) {


                        $sort = $module->sortNumber ?: (++$sortIndex)*100;
                        $navigation = $module->getNavigation();
                        foreach ($navigation as $key => $value) {
                            if (!isset($navigation[$key]['sort'])) {
                                $navigation[$key]['sort'] = $sort;
                            }
                        }

                        $menu = array_merge($menu, $navigation);
                    }
                }

                //Sort
                usort($menu, function ($a, $b) {
                    $res = 0;
                    if ($a['sort'] != $b['sort']) {
                        $res = $a['sort'] > $b['sort'] ? 1 : -1;
                    }
                    /*if (!$res) {
                        $res = strcmp($a['label'], $b['label']);
                    }*/
                    return $res;
                });
            }

            if ($this->sidebarMenuCache !== false) {
                Yii::$app->cache->set($cacheKey, $menu, $this->sidebarMenuCache);
            }
        }

        return $menu;
    }
}