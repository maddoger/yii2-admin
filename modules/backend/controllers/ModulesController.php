<?php

namespace rusporting\admin\modules\backend\controllers;

use rusporting\core\BackendController;
use Yii;
use yii\helpers\Html;
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
		$moduleObject = Yii::$app->getModule($module);
		if (!$moduleObject) {
			throw new NotFoundHttpException(Yii::t('rusporting/admin', 'Module not found.'));
		}
		/*$man = Yii::$app->getUrlManager();

		$urls = [
			'admin/backend/modules/index',
			'/admin/backend/modules/index',
			['admin/backend/modules/index'],
			['/admin/backend/modules/index'],

			'admin/modules/index',
			'/admin/modules/index',
			['admin/modules/index'],
			['/admin/modules/index'],

			'admin/modules',
			'/admin/modules',
			['admin/modules'],
			['/admin/modules'],


			'config',
			'/config',
			['config'],
			['/config'],
		];

		foreach ($urls as $url) {
			echo (is_array($url) ? 'array ['.$url[0].']' : $url),' -> ',Html::url($url),'<br />';
			echo (is_array($url) ? 'array ['.$url[0].']' : $url),' -> ',
			(is_array($url) ? $man->createUrl($url[0], array_slice($url, 1)) : $man->createUrl($url) ),
			'<br /><br />';
		}
		return false;*/

		return $this->render('config', ['module' => $moduleObject]);
	}
}