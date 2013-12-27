<?php
/**
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name/
 * @copyright Copyright (c) 2013-2014 Rusporting Inc.
 * @since 18.12.13
 */

namespace rusporting\admin;

use Yii;
use rusporting\core\Module;
use yii\rbac\Item;

class AdminModule extends Module
{
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

	protected $hasFrontend = false;
	protected $hasBackend = true;

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
	public function getFaIcon()
	{
		return 'gear';
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
	public function getConfigurationModel()
	{
		$model = parent::getConfigurationModel();
		$model->addAttributes([
			'pageTitle' => ['label' => Yii::t('rusporting/admin', 'Window title in admin panel')],
			'brandName' => ['label' => Yii::t('rusporting/admin', 'Brand name'), 'help' => Yii::t('rusporting/admin', 'Help text')],
			'brandLogo' => ['type'=>'file', 'label' => Yii::t('rusporting/admin', 'Brand logo file')],
		]);
		return $model;
	}

	/**
	 * Rules needed for administrator
	 *
	 * @return array|null
	 */
	/**
	 * @inheritdoc
	 */
	public function getRbacRoles()
	{
		return [
			'admin.modulesConfiguration' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/admin', 'Modules Configuration')],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getBackendNavigation()
	{
		return [
			[
				'label' => Yii::t('rusporting/admin', 'Settings'),
				'fa'=>'gears',
				//'url' => 'user/user-backend/index',
				'items' => [
					[
						'label' => Yii::t('rusporting/admin', 'Modules'), 'fa'=>'gears',
						'url'=> ['/'.$this->id.'/modules/index'],
						'activeUrl'=> ['/'.$this->id.'/modules/*'],
					],
					[
						'label' => Yii::t('rusporting/admin', 'Routes'), 'fa'=>'arrows',
						'url'=> ['/'.$this->id.'/routes/index'],
						'activeUrl'=> ['/'.$this->id.'/routes/*'],
					],
				],
			]
		];
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
		usort($backendModules, function($a, $b){
			if ($a->backendSortNumber != $b->backendSortNumber) {
				return $a->backendSortNumber>$b->backendSortNumber ? 1 : -1;
			} else {
				return strcmp($a->getName(), $b->getName());
			}
		});
		return $backendModules;
	}
}