<?php

namespace rusporting\admin\controllers;

use rusporting\core\BackendController;
use Yii;

/**
 * @property \rusporting\admin\AdminModule $module
 */
class DashboardController extends BackendController
{
	public function init()
	{
		parent::init();

		$this->title = Yii::t('rusporting/admin', 'Dashboard');
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
		return $this->render('index.twig');
	}
}