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
	public function init()
	{
		parent::init();
		$this->title = Yii::t('rusporting/admin', 'Modules');
	}

	public function actionIndex()
	{
		$this->subtitle = Yii::t('rusporting/admin', 'Installed modules');
		$modules = Yii::$app->getModule('admin')->getBackendModules();
		return $this->render('index', ['modules' => $modules]);
	}

	public function actionConfig($module)
	{
		/**
		 * @var \rusporting\core\Module $moduleObject
		 */
		$moduleObject = Yii::$app->getModule($module);
		if (!$moduleObject) {
			throw new NotFoundHttpException(Yii::t('rusporting/admin', 'Module not found.'));
		}

		//Get model for configuration
		$model = $moduleObject->getConfigurationModel();
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

			$config = array_filter($model->getAttributes());
			$modulesConfig[$module] = $config;

			if (file_put_contents($path, '<?php return '.var_export($modulesConfig, true).';')) {
				Yii::$app->getSession()->setFlash('success', Yii::t('rusporting/admin', 'Configuration was saved successfully.'));
				return $this->redirect(['index']);
			} else {
				Yii::$app->getSession()->setFlash('error', Yii::t('rusporting/admin', 'Couldn\'t save configuration file.'));
			}
		}

		return $this->render('config', ['module' => $moduleObject, 'model'=>$model, 'configView'=>$configView]);
	}
}