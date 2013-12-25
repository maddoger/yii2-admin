<?php
/**
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name/
 * @copyright Copyright (c) 2013-2014 Rusporting Inc.
 * @since 18.12.13
 */

namespace rusporting\admin;

use Yii;
use rusporting\core\components\Module;

class AdminModule extends Module
{
	public $layout = 'main';
	public $pageTitle = 'Rusporting Marketing';
	public $brandLogo;
	public $brandName = 'Rusporting Marketing';
	public $dashboardUrl = null;

	/**
	 * Translation category for Yii::t function
	 *
	 * @var string
	 */
	public $translationCategory = 'rusporting/admin';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		//Console
		if (Yii::$app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'rusporting\admin\console\controllers';
		}

		//register translation messages from module
		//so no need do add to config/main.php
		Yii::$app->getI18n()->translations[$this->translationCategory] =
			array(
				'class' => 'yii\i18n\PhpMessageSource',
				'basePath' => '@rusporting/admin/messages',
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return Yii::t('rusporting/admin', '_module_name_');
	}

	/**
	 * @inheritdoc
	 */
	public function getDescription()
	{
		return Yii::t('rusporting/admin', '_module_description_');
	}

	/**
	 * @inheritdoc
	 */
	public function getVersion()
	{
		return '0.1';
	}

	/**
	 * @inheritdoc
	 */
	public function getIcon()
	{
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function isMultiLanguage()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function hasFrontend()
	{
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function hasBackend()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function getConfigurationForm()
	{
		return null;
	}

	/**
	 * Rules needed for administrator
	 *
	 * @return array|null
	 */
	public function getRights()
	{
		return [
			'moduleSettings' => ['label' => Yii::t('rusporting/admin', 'Change site settings')],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getBackendNavigation()
	{
		return null;
	}

	/*** Admin panel functions ***/

	public function getBackendModulesNavigationItems()
	{
		$modules = $this->getBackendModules();
		$items = [];
		foreach ($modules as $module) {
			/**
			 * @var Module $module
			 */
			$childItems = $module->getBackendNavigation();
			if ($childItems !== false) {
				if (!$childItems) {
					$faIcon = $module->getFaIcon();
					$item = ['label' => $module->getName(), 'url' => $module->getBackendIndex()];
					if ($faIcon) {
						$item['fa'] = $faIcon;
					}
					$items[] = $item;
				} else {
					$items = array_merge($items, $childItems);
				}
			}
		}
		return $items;
	}

	/**
	 * @return array
	 */
	public function getBackendModules()
	{
		$backendModules = [];
		$allModules = Yii::$app->getModules(false);

		foreach ($allModules as $module) {
			//If is rusporting module with info
			if ($module instanceof Module) {
				if ($module->hasBackend()) {
					$backendModules[] = $module;
				}
			}
		}
		return $backendModules;
	}
}