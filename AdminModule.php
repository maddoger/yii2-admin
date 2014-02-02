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
use yii\caching\FileDependency;
use yii\helpers\Html;
use yii\rbac\Item;

class AdminModule extends Module
{
	public $pageTitle = 'Rusporting Marketing';
	public $brandLogo;
	public $brandName = 'Rusporting Marketing';
	public $dashboardUrl = null;

	public $uploadsDir = '/uploads';

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
			'uploadsDir' => ['label' => Yii::t('rusporting/admin', 'Uploads directory')],
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
			'uploads.read' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/admin', 'View uploaded files')],
			'uploads.write' => ['type'=>Item::TYPE_OPERATION, 'description' => Yii::t('rusporting/admin', 'Upload files')],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getBackendNavigation()
	{
		return [
			[
				'label' => Yii::t('rusporting/admin', 'File manager'),
				'fa'=>'files-o',
				'url' => ['/'.$this->id . '/files/index'],
				'roles' => ['uploads.read'],
			],
			[
				'label' => Yii::t('rusporting/admin', 'Settings'),
				'fa'=>'gears',
				//'url' => 'user/user-backend/index',
				'items' => [
					[
						'label' => Yii::t('rusporting/admin', 'Modules'), 'fa'=>'gears',
						'url'=> ['/'.$this->id.'/modules/index'],
						'activeUrl'=> ['/'.$this->id.'/modules/*'],
						'roles' => ['admin.modulesConfiguration'],
					],
					/*[
						'label' => Yii::t('rusporting/admin', 'Routes'), 'fa'=>'arrows',
						'url'=> ['/'.$this->id.'/routes/index'],
						'activeUrl'=> ['/'.$this->id.'/routes/*'],
					],*/
				],
			]
		];
	}

	/*** Admin panel functions ***/

	public function getBackendModulesNavigationItems()
	{
		//Cache
		$cacheKey = 'adminModule.BackendModulesNavigationItems';
		$items = Yii::$app->cache->get($cacheKey);
		if (true && YII_DEBUG || $items === false) {

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
						$item = ['label' => $module->getName(), 'url' => $module->getBackendIndex(), 'activeUrl' => ['/'.$module->id.'/*']];
						if ($faIcon) {
							$item['fa'] = $faIcon;
						}
						$items[] = $item;
					} else {
						$items = array_merge($items, $childItems);
					}
				}
			}
			$items = $this->checkItemsRoles($items);
			Yii::$app->cache->set($cacheKey, $items, 600, new FileDependency(['fileName' => Yii::getAlias('@frontendPath/config/modules.php')]));
		}

		return $items;
	}

	public function checkItemsRoles($items)
	{
		if (!$items) return [];
		foreach ($items as $key=>$item) {
			if (isset($item['roles'])) {
				//check roles
				$allow = false;
				foreach ($item['roles'] as $role) {
					if (Yii::$app->user->checkAccess($role)) {
						$allow = true;
						break;
					}
				}
				if (!$allow) {
					unset($items[$key]);
					continue;
				}
			}
			if (isset($items['items'])) {
				$items[$key]['items'] = $this->checkItemsRoles($item['items']);
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

		foreach ($allModules as $id=>$module) {
			if (is_array($module)) {
				$module = Yii::$app->getModule($id);
			}
			//If is rusporting module with info
			if ($module instanceof Module) {
				if ($module->hasBackend()) {
					$backendModules[] = $module;
				}
			}
		}

		usort($backendModules, function($a, $b){
			if ($a->backendSortNumber === null && $b->backendSortNumber !== null) {
				return 1;
			} elseif ($a->backendSortNumber !== null && $b->backendSortNumber === null) {
				return -1;
			} elseif ($a->backendSortNumber != $b->backendSortNumber) {
				return $a->backendSortNumber>$b->backendSortNumber ? 1 : -1;
			} else {
				return strcmp($a->getName(), $b->getName());
			}
		});
		return $backendModules;
	}
}