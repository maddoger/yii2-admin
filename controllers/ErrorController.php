<?php

namespace rusporting\admin\controllers;

use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use yii\base\Exception;
use yii\base\UserException;

class ErrorController extends Controller
{
	/**
	 * @var string the layout file for error
	 */
	public $layout = 'lite';

	/**
	 * @var string the view file to be rendered. If not set, it will take the value of [[id]].
	 * That means, if you name the action as "error" in "SiteController", then the view name
	 * would be "error", and the corresponding view file would be "views/site/error.php".
	 */
	public $view = 'index';
	/**
	 * @var string the name of the error when the exception name cannot be determined.
	 * Defaults to "Error".
	 */
	public $defaultName;
	/**
	 * @var string the message to be displayed when the exception message contains sensitive information.
	 * Defaults to "An internal server error occurred.".
	 */
	public $defaultMessage;

	public function run($route, $params=[])
	{
		if (($exception = Yii::$app->exception) === null) {
			return '';
		}

		if ($exception instanceof HttpException) {
			$code = $exception->statusCode;
		} else {
			$code = $exception->getCode();
		}

		$action = $this->createAction($code);
		if ($action !== null) {
			$route = $code;
		}
		return parent::run($route, $params);
	}

	public function actionIndex()
	{
		if (($exception = Yii::$app->exception) === null) {
			return '';
		}

		if ($exception instanceof HttpException) {
			$code = $exception->statusCode;
		} else {
			$code = $exception->getCode();
		}
		if ($exception instanceof Exception) {
			$name = $exception->getName();
		} else {
			$name = $this->defaultName ?: Yii::t('rusporting/admin', 'Error');
		}

		if ($exception instanceof UserException) {
			$message = $exception->getMessage();
		} else {
			$message = $this->defaultMessage ?: Yii::t('rusporting/admin', 'An internal server error occurred.');
		}


		if (Yii::$app->getRequest()->getIsAjax()) {
			return "$name: $message";
		} else {

			return $this->render($this->view ?: $this->id, [
				'code' => $code,
				'name' => $name,
				'message' => $message,
				'exception' => $exception,
			]);
		}
	}
}