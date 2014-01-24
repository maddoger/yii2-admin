<?php

namespace rusporting\admin\modules\backend\controllers;

use rusporting\core\BackendController;
use rusporting\core\DynamicModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class ModulesController
 *
 * @property \rusporting\admin\AdminModule $module
 * @package rusporting\admin\controllers
 */
class ModulesController extends BackendController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => 'yii\web\AccessControl',
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin.modulesConfiguration'],
					],
					[
						'allow' => false,
					]
				],
			],
		];
	}

	public function init()
	{
		parent::init();
		$this->title = Yii::t('rusporting/admin', 'Modules');

		$this->breadcrumbs[] = ['label'=>Yii::t('rusporting/admin', 'Modules'), 'fa'=>'gears', 'url'=> ['/admin/modules']];
	}

	public function actionIndex()
	{
		$this->subtitle = Yii::t('rusporting/admin', 'Installed modules');
		$modules = Yii::$app->getModule('admin')->getBackendModules();
		return $this->render('index', ['modules' => $modules]);
	}

	public function actionConfig($module, $back_url=null)
	{
		/**
		 * @var \rusporting\core\Module $moduleObject
		 */
		$moduleObject = Yii::$app->getModule($module);
		if (!$moduleObject) {
			throw new NotFoundHttpException(Yii::t('rusporting/admin', 'Module not found.'));
		}

		$this->breadcrumbs[] = ['label'=>$moduleObject->getName(), 'url'=> ['/admin/modules/config', 'module'=>$moduleObject->id], 'fa'=>$moduleObject->getFaIcon()];
		$this->breadcrumbs[] = ['label'=>Yii::t('rusporting/admin', 'Configuration')];

		//Get model for configuration
		$model = $moduleObject->getConfigurationModel();
		if (!$model) {
			throw new NotFoundHttpException(Yii::t('rusporting/admin', 'Settings not found.'));
		}
		$model->scenario = 'all';
		$attributes = $model->attributes();
		foreach ($attributes as $name) {
			$model->{$name} = $moduleObject->{$name};
		}
		$configView = $moduleObject->getConfigurationView();

		if ($model->load($_POST) && $model->validate()) {
			//All is good, saving
			$path = Yii::getAlias('@common' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'modules-config.php');
			$modulesConfig = (file_exists($path)) ? include($path) : [];

			//Remove all model attributes from config
			$oldConfig = isset($modulesConfig[$module]) ? $modulesConfig[$module] : [];
			foreach ($model->attributes as $attr) {
				unset($oldConfig[$attr]);
			}
			$modulesConfig[$module] = array_merge($oldConfig, array_filter($model->getAttributes()));

			if (file_put_contents($path, '<?php return '.var_export($modulesConfig, true).';')) {
				Yii::$app->getSession()->setFlash('success', Yii::t('rusporting/admin', 'Configuration was saved successfully.'));

				if ($back_url !== null) {
					return $this->redirect($back_url);
				} else {
					return $this->redirect(['index']);
				}

			} else {
				Yii::$app->getSession()->setFlash('error', Yii::t('rusporting/admin', 'Couldn\'t save configuration file.'));
			}
		}

		$this->title = $moduleObject->getName();
		$this->subtitle = Yii::t('rusporting/admin', 'Module configuration');

		return $this->render('config', ['module' => $moduleObject, 'model'=>$model, 'configView'=>$configView]);
	}
}