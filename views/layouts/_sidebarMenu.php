<?php

/**
 * @var $this yii\web\View
 * @var string $content
 */
use maddoger\admin\AdminModule;
use maddoger\admin\widgets\Menu;

/**
 * @var \maddoger\admin\AdminModule $adminModule
 */
$adminModule = AdminModule::getInstance();

/**
 * @var \maddoger\core\BackendModule $module
 */
Yii::beginProfile('SIDEBAR_MENU');

$cacheKey = 'ADMIN_SIDEBAR_MENU';
$menu = Yii::$app->cache->get($cacheKey);

if (!$menu) {

    $menu = $adminModule->sidebarMenu ?:
        [
            [
                'label' => Yii::t('maddoger/admin', 'Dashboard'),
                'class' => 'fa fa-dashboard',
                'url' => ['/' . AdminModule::getInstance()->id . '/site/index'],
                'sort' => -1,
            ],
        ];

    if ($adminModule->sidebarMenuUseModules) {

        //Get navigation from modules
        foreach (Yii::$app->modules as $module) {
            if ($module instanceof \maddoger\core\BackendModule) {

                $sort = $module->sortNumber;
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
                $res = $a['sort'] > $b['sort'] ? -1 : 1;
            }
            /*if (!$res) {
                $res = strcmp($a['label'], $b['label']);
            }*/
            return $res;
        });
    }

    Yii::$app->cache->set($cacheKey, $menu, 60);
}

echo Menu::widget([
    'items' => $menu,
    'activateParents' => true,
    'labelTemplate' => '<a href="#">{label} <i class="fa fa-angle-left pull-right"></i></a>',
    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
    'submenuItemClass' => 'treeview',
    'options' => [
        'class' => 'sidebar-menu',
    ],
]);

Yii::endProfile('SIDEBAR_MENU');
