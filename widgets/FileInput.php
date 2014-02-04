<?php

namespace rusporting\admin\widgets;

use rusporting\elfinder\CoreAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

/**
 * FileInput
 *
 * @see http://jasny.github.io/bootstrap/javascript/#fileinput
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 */
class FileInput extends InputWidget
{
	/**
	 * @var array the event handlers for the underlying Jasny file input JS plugin.
	 * Please refer to the [Jasny Bootstrap File Input](http://jasny.github.io/bootstrap/javascript/#fileinput) plugin
	 * Web page for possible events.
	 */
	public $clientEvents = [];

	public $browseServer = true;

	public $upload = true;

	public $setUrl = true;

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		} else {
			$this->setId($this->options['id']);
		}

		if (!isset(Yii::$app->getI18n()->translations['rusporting/admin'])) {
			//register translation messages from module
			//so no need do add to config/main.php
			Yii::$app->getI18n()->translations['rusporting/admin'] =
				array(
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@rusporting/admin/messages',
				);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$options = $this->options;
		$options['id'] = $this->options['id'].'-input';

		$params = [
			'widget' => $this,
		];

		if ($this->hasModel()) {
			$field = Html::activeHiddenInput($this->model, $this->attribute, $options);
			$this->name = isset($options['name']) ? $options['name'] : Html::getInputName($this->model, $this->attribute);
			$params['value'] = Html::getAttributeValue($this->model, $this->attribute);
		} else {
			$field = Html::hiddenInput($this->name, $this->value, $options);
			$params['value'] = $this->value;
		}

		$this->options['name'] = $this->name;
		$params['field'] = $field;

		if (!$this->upload) $this->browseServer = true;

		echo $this->render('fileInput', $params);
		if ($this->browseServer) {
			CoreAsset::register($this->view);
			$this->options['browseServerConnectorUrl'] = Html::url(['/admin/files/connector']);
		}

		$this->registerPlugin();
	}

	/**
	 * Registers Jasny File Input Bootstrap plugin and the related events
	 */
	protected function registerPlugin()
	{
		$view = $this->getView();

		$id = $this->options['id'];

		$options = empty($this->options) ? '' : Json::encode($this->options);
		$view->registerJs(";jQuery('#$id').fileinput(".$options.");");

		if (!empty($this->clientEvents)) {
			$js = [];
			foreach ($this->clientEvents as $event => $handler) {
				$js[] = ";jQuery('#$id').on('$event', $handler);";
			}
			$view->registerJs(implode("\n", $js));
		}
	}
}