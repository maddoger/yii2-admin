<?php

namespace rusporting\admin\widgets;

use rusporting\elfinder\CoreAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

/**
 * ImageInput
 *
 * @see http://jasny.github.io/bootstrap/javascript/#fileinput
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 */
class ImageInput extends InputWidget
{
	/**
	 * @var string the thumbnail to be displayed
	 */
	public $thumbnail = null;

	/**
	 * @var null|int needed width
	 */
	public $width = 200;

	/**
	 * @var null|int needed height
	 */
	public $height = 200;

	/**
	 * @var array the event handlers for the underlying Jasny file input JS plugin.
	 * Please refer to the [Jasny Bootstrap File Input](http://jasny.github.io/bootstrap/javascript/#fileinput) plugin
	 * Web page for possible events.
	 */
	public $clientEvents = [];


	public $browseServer = true;

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
		if ($this->thumbnail === null && !empty($params['value'])) {
			$this->thumbnail = '<img src="'.Html::encode($params['value']).'" alt="'.Html::encode($params['value']).'" />';
		}

		$params['field'] = $field;

		echo $this->render('imageInput', $params);
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