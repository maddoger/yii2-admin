<?php

/**
 * @var $this yii\web\View
 * @var string $content
 */
use maddoger\admin\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var \maddoger\core\BackendModule $module
 */

$menu = [
    [
        'label' => Yii::t('maddoger/admin', 'Dashboard'),
        'class' => 'fa fa-dashboard',
        'url' => ['/'.Module::getInstance()->id.'/site/index'],
        'sort' => -1,
    ],
];


//Get navigation from modules
foreach (Yii::$app->modules as $module) {
    if ($module instanceof \maddoger\core\BackendModule) {

        $sort = $module->sortNumber;
        $navigation = $module->getNavigation();
        foreach ($navigation as $key=>$value) {
            if (!isset($navigation[$key]['sort'])) {
                $navigation[$key]['sort'] = $sort;
            }
        }
        $menu = array_merge($menu, $navigation);
    }
}
//Сортируем меню
usort($menu, function($a, $b){
    $res = 0;
    if ($a['sort'] != $b['sort']) {
        $res = $a['sort']>$b['sort'] ? -1 : 1;
    }
    if (!$res) {
        $res = strcmp($a['label'], $b['label']);
    }
    return $res;
});

//Route and its params
$currentRoute = null;
$routeParams = Yii::$app->request->getQueryParams();
if (Yii::$app->controller !== null) {
    $currentRoute = Yii::$app->controller->getRoute();
}

$menu_items = function($items, $child=false) use (&$menu_items, $currentRoute)
{
    if (!$items) {
        return '';
    }
    $res = '';
    foreach ($items as $item) {

        $label = ArrayHelper::getValue($item, 'label', '-undefined-');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $icon = ArrayHelper::getValue($item, 'icon');
        $items = ArrayHelper::getValue($item, 'items');
        $options = ArrayHelper::getValue($item, 'options', []);
        $active = ArrayHelper::getValue($item, 'active');
        if ($active === null) {
            $active = false;
            if (is_array($url) && isset($url[0])) {
                $route = $url[0];
                if ($route[0] !== '/' && Yii::$app->controller) {
                    $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
                }
                if (ltrim($route, '/') === $currentRoute) {
                    $active = true;
                }
                //var_dump(ltrim($route, '/'), $currentRoute);
            }
        }
        if ($active) {
            Html::addCssClass($options, 'active');
        }

        $content = Html::tag('span', $label);
        if ($icon) {
            $content = Html::tag('i', '', ['class' => $icon]).' ' . $content;
        }

        if ($items) {
            $content .= Html::tag('i', '', ['class' => 'fa fa-angle-left pull-right']);
            $content = Html::a($content, $url);
            $content .= $menu_items($item['items'], true);
            Html::addCssClass($options, 'treeview');
        } else {
            $content = Html::a($content, $url);
        }
        $res .= Html::tag('li', $content, $options);

    }
    if (!empty($res)) {
        return Html::tag('ul', $res, ['class' => $child ? 'treeview-menu' : 'sidebar-menu']);
    }
    return empty($res) ? '' : '<ul>'.$res.'</ul>';
};

echo $menu_items($menu);

?>