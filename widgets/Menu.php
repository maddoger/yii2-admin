<?php

namespace rusporting\admin\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Menu as BaseMenu;

/**
 * Class Menu
 * Menu with fa icons support
 *
 * @package rusporting\admin\widgets
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
	public $labelTemplate = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">{icon}{label}</a>';

	/**
	 * @inheritdoc
	 */
	public $submenuTemplate = "\n<ul class=\"dropdown-menu\">\n{items}\n</ul>\n";

	/**
	 * @inheritdoc
	 */
	public $activateParents = true;

	/**
	 * @inheritdoc
	 */
	protected function renderItems($items)
	{
		$n = count($items);
		$lines = [];
		foreach ($items as $i => $item) {
			$options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
			$tag = ArrayHelper::remove($options, 'tag', 'li');
			$class = [];
			if ($item['active']) {
				$class[] = $this->activeCssClass;
			}
			if ($i === 0 && $this->firstItemCssClass !== null) {
				$class[] = $this->firstItemCssClass;
			}
			if ($i === $n - 1 && $this->lastItemCssClass !== null) {
				$class[] = $this->lastItemCssClass;
			}
			if (!empty($item['items'])) {
				//$item['fa'] = 'caret-square-o-down';
				$class[] = 'dropdown';
				if ($item['active']) {
					$class[] = 'open';
				}
			}

			if (!empty($class)) {
				if (empty($options['class'])) {
					$options['class'] = implode(' ', $class);
				} else {
					$options['class'] .= ' ' . implode(' ', $class);
				}
			}

			$menu = $this->renderItem($item);
			if (!empty($item['items'])) {
				$menu .= strtr($this->submenuTemplate, [
					'{items}' => $this->renderItems($item['items']),
				]);

				//Add multi option
			}
			$lines[] = Html::tag($tag, $menu, $options);
		}
		return implode("\n", $lines);
	}


	/**
	 * @inheritdoc
	 */
	protected function renderItem($item)
	{
		if (isset($item['items'])) {
			$item['label'] .= ' <span class="caret"></span>';
		}

		$icon = '';
		if (isset($item['fa']) && !empty($item['fa'])) {
			$icon = '<i class="fa fa-'.$item['fa'].'"></i> ';
		}

		if (isset($item['url'])) {
			$template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
			return strtr($template, [
				'{url}' => Html::url($item['url']),
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
}