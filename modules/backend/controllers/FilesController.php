<?php

namespace maddoger\admin\modules\backend\controllers;

use maddoger\core\BackendController;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\validators\FileValidator;
use yii\validators\ImageValidator;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use yii\web\VerbFilter;
use Yii;

/**
 * FilesController
 */
class FilesController extends BackendController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => 'yii\web\AccessControl',
				'rules' => [
					[
						'actions' => ['index', 'connector', 'dialog'],
						'allow' => true,
						'roles' => ['uploads.view', 'uploads.write'],
					],
					/*[
						'actions' => ['image-upload', 'clipboard-upload'],
						'allow' => true,
						'roles' => ['image.upload'],
					],*/
					[
						'allow' => false,
					]
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'image-upload' => ['post'],
					'clipboard-upload' => ['post'],
					'file-upload' => ['post'],
				],
			],
		];
	}

	public function actions()
	{
		$adminModule = Yii::$app->getModule('admin');

		$attributes = array(
			array( // hide .gitignore
				'pattern' => '/\.gitignore$/',
				'read' => false,
				'write' => false,
				'hidden' => true,
				'locked' => true
			),
			array( // hide .php
				'pattern' => '/\.php$/',
				'read' => false,
				'write' => false,
				'hidden' => true,
				'locked' => true
			),
		);
		if (!Yii::$app->user->checkAccess('uploads.write')) {
			//deny to uploading
			$attributes[] = array(
				'pattern' => '//',
				'read' => true,
				'write' => false,
				'hidden' => false,
				'locked' => true
			);
		}

		return array(
			'connector' => array(
				'class' => 'maddoger\elfinder\ConnectorAction',
				'clientOptions'=>array(
					'locale' => '',
					'debug'  => false,
					'roots'  => array(
						array(
							'driver' => 'LocalFileSystem',
							'path'   => Yii::getAlias('@frontendPath'.$adminModule->uploadsDir),
							'URL'    => Yii::getAlias('@frontendUrl'.$adminModule->uploadsDir),
							'attributes' => $attributes
						)
					)
				)
			)
		);
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionDialog()
	{
		return $this->renderPartial('dialog');
	}

	/*public function actionFileUpload()
	{
		$validator = new FileValidator();
		$error = null;

		$file = UploadedFile::getInstanceByName('file');

		if($validator->validate($file, $error))
		{
			$adminModule = Yii::$app->getModule('admin');

			$path = $adminModule->uploadsDir.'/pages/'.date('Y/m/d');
			$fileName =
				Inflector::slug(pathinfo($file->name, PATHINFO_FILENAME)).'_'.
				time().'.'.strtolower(pathinfo($file->name, PATHINFO_EXTENSION));

			$base = Yii::getAlias('@frontendUrl'.$path);

			$fileDir = Yii::getAlias('@frontendPath'.$path);
			if (!is_dir($fileDir)) {
				FileHelper::createDirectory($fileDir);
			}

			if($file->saveAs($fileDir.'/'.$fileName))
			{
				$array = array(
					'filelink' => $base.'/'.$fileName,
					'filename' => $file->name
				);
				return json_encode($array);
			}
		}

		//Yii::$app->response->setStatusCode('400');
		return json_encode(['error'=>\Yii::t('maddoger/admin', 'Bad file.')]);
	}

	public function actionClipboardUpload()
	{
		if (!isset($_POST['contentType']) || !isset($_POST['data'])) {
			throw new BadRequestHttpException();
		}

		$contentType = $_POST['contentType'];
		$data = base64_decode($_POST['data']);
		$fileName = md5($data).'.png';

		$adminModule = Yii::$app->getModule('admin');

		//$folder = isset($_POST['folder']) ? realpath($_POST['folder']) : '';
		$folder = isset($_POST['folder']) ? $_POST['folder'] : '';
		$path = $adminModule->uploadsDir.$folder;
		$base = Yii::getAlias('@frontendUrl'.$path);

		$fileDir = Yii::getAlias('@frontendPath'.$path);
		if (!is_dir($fileDir)) {
			FileHelper::createDirectory($fileDir);
		}

		if(file_put_contents($fileDir.'/'.$fileName, $data))
		{
			$array = array(
				'filelink' => $base.'/'.$fileName,
			);
			return json_encode($array);
		}

		//Yii::$app->response->setStatusCode('400');
		return json_encode(['error'=>\Yii::t('maddoger/admin', 'Bad file.')]);
	}

	public function actionImageUpload()
	{
		$image = new ImageValidator();
		$error = null;

		$file = UploadedFile::getInstanceByName('file');

		if($image->validate($file, $error))
		{
			$adminModule = Yii::$app->getModule('admin');

			//$folder = isset($_POST['folder']) ? realpath($_POST['folder']) : '';
			$folder = isset($_POST['folder']) ? $_POST['folder'] : '';
			$path = $adminModule->uploadsDir.$folder;


			$fileName =
				Inflector::slug(pathinfo($file->name, PATHINFO_FILENAME)).'_'.
				time().'.'.strtolower(pathinfo($file->name, PATHINFO_EXTENSION));

			$base = Yii::getAlias('@frontendUrl'.$path);

			$fileDir = Yii::getAlias('@frontendPath'.$path);
			if (!is_dir($fileDir)) {
				FileHelper::createDirectory($fileDir);
			}

			if($file->saveAs($fileDir.'/'.$fileName))
			{
				$array = array(
					'filelink' => $base.'/'.$fileName,
				);
				return json_encode($array);
			}
		}

		//Yii::$app->response->setStatusCode('400');
		return json_encode(['error'=>\Yii::t('maddoger/admin', 'Bad file.')]);
	}*/
}
