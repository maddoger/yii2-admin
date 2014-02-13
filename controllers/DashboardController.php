<?php

namespace maddoger\admin\controllers;

use maddoger\core\BackendController;
use Yii;

/**
 * @property \maddoger\admin\AdminModule $module
 */
class DashboardController extends BackendController
{
	public function init()
	{
		parent::init();

		$this->title = Yii::t('maddoger/admin', 'Dashboard');
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex()
	{
		return $this->render('index', [
			'navigation' => $this->module->getBackendModulesNavigationItems()
		]);
	}
}