<?php

/**
 * @var $this yii\web\View
 * @var string $content
 */
use maddoger\admin\Module;
use maddoger\admin\widgets\Menu;
use maddoger\core\BackendModule;

/**
 * @var \maddoger\admin\Module $adminModule
 */
$adminModule = Module::getInstance();

/**
 * @var \maddoger\core\BackendModule $module
 */
Yii::beginProfile('SIDEBAR_MENU');

$menu = $adminModule->getSidebarMenu();

echo Menu::widget([
    'items' => $menu,
    'activateParents' => true,
    'labelTemplate' => '<a href="#">{icon}{label} <i class="fa fa-angle-left pull-right"></i></a>',
    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
    'submenuItemClass' => 'treeview',
    'options' => [
        'class' => 'sidebar-menu',
    ],
]);

Yii::endProfile('SIDEBAR_MENU');
