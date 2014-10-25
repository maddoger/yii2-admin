<?php

namespace maddoger\admin\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu as BaseMenu;

/**
 * Class Menu
 * Menu with fa icons support
 *
 * @package maddoger/yii2-admin\widgets
 */
class Menu extends BaseMenu
{
	/**
	 * @inheritdoc
	 */
	public $linkTemplate = '<a href="{url}">{icon}{label}</a>';

	/**
	 * @inheritdoc
	 */
	public $labelTemplate = '<a href="#">{icon}{label}</a>';

    /**
     * @var string
     */
	public $iconTemplate = '<i class="{icon}"></i>&nbsp;';

	/**
	 * @inheritdoc
	 */
	public $submenuTemplate = "\n<ul class=\"dropdown-menu\">\n{items}\n</ul>\n";

    /**
     * @var string
     */
    public $submenuItemClass = '';

	/**
	 * @inheritdoc
	 */
	public $activateParents = true;

	/**
	 * @inheritdoc
	 */
    /**
     * Normalizes the [[items]] property to remove invisible items and activate certain items.
     * @param array $items the items to be normalized.
     * @param boolean $active whether there is an active child menu item.
     * @return array the normalized menu items
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {

            if (isset($item['roles'])) {
                $item['visible'] = false;
                foreach ($item['roles'] as $role) {
                    if (Yii::$app->user->can($role)) {
                        $item['visible'] = true;
                        break;
                    }
                }
            }
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            if (!isset($items[$i]['options'])) {
                $items[$i]['options'] = [];
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
                Html::addCssClass($items[$i]['options'], $this->submenuItemClass);
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }

        return array_values($items);
    }


	/**
	 * @inheritdoc
	 */
	protected function renderItem($item)
	{
        $icon = ArrayHelper::getValue($item, 'icon');
		if ($icon) {
            if (strpos($icon, 'fa ') === 0) {
                $icon .= ' fa-fw';
            }
			$icon = '<i class="'.$icon.'"></i>&nbsp;';
		}

		if (isset($item['url'])) {
			$template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
			return strtr($template, [
				'{url}' => Url::to($item['url']),
				'{icon}' => $icon,
				'{label}' => $item['label'],
			]);
		} else {
			$template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);
			return strtr($template, [
				'{label}' => $item['label'],
				'{icon}' => $icon,
			]);
		}
	}


	/**
	 * Checks whether a menu item is active.
	 * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
	 * When the `url` option of a menu item is specified in terms of an array, its first element is treated
	 * as the route for the item and the rest of the elements are the associated parameters.
	 * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
	 * be considered active.
	 * @param array $item the menu item to be checked
	 * @return boolean whether the menu item is active
	 */
	protected function isItemActive($item)
	{
        $res = parent::isItemActive($item);
		if (!$res && isset($item['activeUrl'])) {

			if (is_array($item['activeUrl'])) {
				$route = $item['activeUrl'][0];
			} else {
				$route = $item['activeUrl'];
			}
			if ($route[0] !== '/' && Yii::$app->controller) {
				$route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
			}
			$route = ltrim($route, '/');

			$preg = '/^'.str_replace('*', '(.*?)',  str_replace('/', '\/', $route)).'$/is';
			$res = preg_match($preg, $this->route);
		}
        return $res;
	}
}