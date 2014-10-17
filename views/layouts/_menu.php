<?php

/**
 * @var $this yii\web\View
 * @var string $content
 */
use maddoger\admin\Module;
use yii\helpers\Html;

/**
 * @var \maddoger\core\BackendModule $module
 */

$menu = [
    [
        'label' => Yii::t('maddoger/admin', 'Dashboard'),
        'class' => 'fa fa-dashboard',
        'url' => ['/'.Module::getInstance()->id.'/site/index'],
    ],
];

foreach (Yii::$app->modules as $module) {
    if ($module instanceof \maddoger\core\BackendModule) {

        $navigation = $module->getNavigation();
        $menu = array_merge($menu, $navigation);
    }
}

function menu_items($items, $child=false)
{
    if (!$items) {
        return '';
    }
    $res = '';
    foreach ($items as $item) {

        $content = Html::tag('span', $item['label']);
        if (isset($item['icon'])) {
            $content = Html::tag('i', '', ['class' => $item['icon']]).' ' . $content;
        }

        if (isset($item['items']) && !empty($item['items'])) {
            $content .= Html::tag('i', '', ['class' => 'fa fa-angle-left pull-right']);
            $content = Html::a($content, '#');
            $content .= menu_items($item['items'], true);
            $res .= Html::tag('li', $content, ['class' => 'treeview']);
        } else {
            $res .= Html::tag('li', Html::a($content, $item['url']));
        }

    }
    if (!empty($res)) {
        return Html::tag('ul', $res, ['class' => $child ? 'treeview-menu' : 'sidebar-menu']);
    }
    return empty($res) ? '' : '<ul>'.$res.'</ul>';
}

echo menu_items($menu);

?>