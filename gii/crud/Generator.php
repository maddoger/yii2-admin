<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace rusporting\admin\gii\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\web\Controller;

/**
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\crud\Generator
{
	public $modelClass;
	public $moduleID;
	public $controllerClass;
	public $baseControllerClass = 'rusporting\core\BackendController';
	public $indexWidgetType = 'grid';
	public $searchModelClass;
	public $languageCategory;
	public $rolesPrefix;

	public function getName()
	{
		return 'CRUD Generator by Rusporting';
	}

	public function getDescription()
	{
		return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
			operations for the specified data model.';
	}

	public function rules()
	{
		return array_merge(parent::rules(), [
			[['moduleID', 'controllerClass', 'modelClass', 'searchModelClass', 'baseControllerClass', 'languageCategory', 'rolesPrefix'], 'filter', 'filter' => 'trim'],
			[['modelClass', 'searchModelClass', 'controllerClass', 'baseControllerClass', 'indexWidgetType', 'languageCategory'], 'required'],
			[['searchModelClass'], 'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==', 'message' => 'Search Model Class must not be equal to Model Class.'],
			[['modelClass', 'controllerClass', 'baseControllerClass', 'searchModelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
			[['modelClass'], 'validateClass', 'params' => ['extends' => BaseActiveRecord::className()]],
			[['baseControllerClass'], 'validateClass', 'params' => ['extends' => Controller::className()]],
			[['controllerClass'], 'match', 'pattern' => '/Controller$/', 'message' => 'Controller class name must be suffixed with "Controller".'],
			[['controllerClass', 'searchModelClass'], 'validateNewClass'],
			[['indexWidgetType'], 'in', 'range' => ['grid', 'list']],
			[['modelClass'], 'validateModelClass'],
			[['moduleID'], 'validateModuleID'],
		]);
	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), [
			'modelClass' => 'Model Class',
			'moduleID' => 'Module ID',
			'controllerClass' => 'Controller Class',
			'baseControllerClass' => 'Base Controller Class',
			'indexWidgetType' => 'Widget Used in Index Page',
			'searchModelClass' => 'Search Model Class',
			'languageCategory' => 'Language category for Yii::t()',
			'rolesPrefix' => 'Roles prefix in controller',
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function hints()
	{
		return [
			'modelClass' => 'This is the ActiveRecord class associated with the table that CRUD will be built upon.
				You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
			'controllerClass' => 'This is the name of the controller class to be generated. You should
				provide a fully qualified namespaced class, .e.g, <code>app\controllers\PostController</code>.',
			'baseControllerClass' => 'This is the class that the new CRUD controller class will extend from.
				You should provide a fully qualified class name, e.g., <code>yii\web\Controller</code>.',
			'moduleID' => 'This is the ID of the module that the generated controller will belong to.
				If not set, it means the controller will belong to the application.',
			'indexWidgetType' => 'This is the widget type to be used in the index page to display list of the models.
				You may choose either <code>GridView</code> or <code>ListView</code>',
			'searchModelClass' => 'This is the class representing the data being collected in the search form.
			 	A fully qualified namespaced class name is required, e.g., <code>app\models\search\PostSearch</code>.',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function stickyAttributes()
	{
		return ['baseControllerClass', 'moduleID', 'indexWidgetType', 'rolesPrefix'];
	}
}
