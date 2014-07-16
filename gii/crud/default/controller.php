<?php

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/**
 * This is the template for generating a CRUD controller class file.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
	$searchModelAlias = $searchModelClass.'Search';
}

/** @var ActiveRecordInterface $class */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
use <?= ltrim($generator->searchModelClass, '\\') ?><?php if (isset($searchModelAlias)):?> as <?= $searchModelAlias ?><?php endif ?>;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
	public function behaviors()
	{
		return [
<?php if (!empty($generator->rolesPrefix)) { ?>
			'access' => [
				'class' => 'yii\filters\AccessControl',
				'rules' => [
					[
						'actions' => ['index', 'view'],
						'allow' => true,
						'roles' => ['<?= $generator->rolesPrefix ?>.read'],
					],
					[
						'actions' => ['create'],
						'allow' => true,
						'roles' => ['<?= $generator->rolesPrefix ?>.create'],
					],
					[
						'actions' => ['update'],
						'allow' => true,
						'roles' => ['<?= $generator->rolesPrefix ?>.update'],
					],
					[
						'actions' => ['delete'],
						'allow' => true,
						'roles' => ['<?= $generator->rolesPrefix ?>.delete'],
					],
					[
						'allow' => false,
					]
				],
			],
<?php } ?>
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all <?= $modelClass ?> models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single <?= $modelClass ?> model.
	 * <?= implode("\n\t * ", $actionParamComments) . "\n" ?>
	 * @return mixed
	 */
	public function actionView(<?= $actionParams ?>)
	{
		return $this->render('view', [
			'model' => $this->findModel(<?= $actionParams ?>),
		]);
	}

	/**
	 * Creates a new <?= $modelClass ?> model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new <?= $modelClass ?>;

		if ($model->load($_POST) && $model->save()) {
			return $this->redirect(['view', <?= $urlParams ?>]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing <?= $modelClass ?> model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * <?= implode("\n\t * ", $actionParamComments) . "\n" ?>
	 * @return mixed
	 */
	public function actionUpdate(<?= $actionParams ?>)
	{
		$model = $this->findModel(<?= $actionParams ?>);

		if ($model->load($_POST) && $model->save()) {
			return $this->redirect(['view', <?= $urlParams ?>]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing <?= $modelClass ?> model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * <?= implode("\n\t * ", $actionParamComments) . "\n" ?>
	 * @return mixed
	 */
	public function actionDelete(<?= $actionParams ?>)
	{
		$this->findModel(<?= $actionParams ?>)->delete();
		return $this->redirect(['index']);
	}

	/**
	 * Finds the <?= $modelClass ?> model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * <?= implode("\n\t * ", $actionParamComments) . "\n" ?>
	 * @return <?= $modelClass ?> the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel(<?= $actionParams ?>)
	{
<?php
if (count($pks) === 1) {
	$condition = '$id';
} else {
	$condition = [];
	foreach ($pks as $pk) {
		$condition[] = "'$pk' => \$$pk";
	}
	$condition = '[' . implode(', ', $condition) . ']';
}
?>
		if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException(Yii::t('<?= $generator->languageCategory ?>', 'The requested page does not exist.'));
		}
	}
}
