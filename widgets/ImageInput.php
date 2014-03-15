<?php

namespace maddoger\admin\widgets;

use maddoger\admin\FileInputAsset;
use maddoger\elfinder\CoreAsset;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
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
	 * @var string url of preview image for thumbnail
	 */
	public $preview = null;

	/**
	 * @var int width of preview
	 */
	public $previewWidth = null;

	/**
	 * @var int height of preview
	 */
	public $previewHeight = null;

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
		if ($this->previewWidth === null) {
			$this->previewWidth = $this->width;
		}
		if ($this->previewHeight === null) {
			$this->previewHeight = $this->height;
		}

		if (!isset(Yii::$app->getI18n()->translations['maddoger/admin'])) {
			//register translation messages from module
			//so no need do add to config/main.php
			Yii::$app->getI18n()->translations['maddoger/admin'] =
				array(
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@maddoger/admin/messages',
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
		$preview = $this->preview !== null ? $this->preview : $params['value'];
		if ($this->thumbnail === null && !empty($preview)) {
			$this->thumbnail = '<img src="'.Html::encode($preview).'" alt="'.Html::encode($preview).'" />';
		}

		$params['field'] = $field;

		if (!$this->upload) $this->browseServer = true;

		echo $this->render('imageInput', $params);
		if ($this->browseServer) {
			CoreAsset::register($this->view);
			$this->options['browseServerConnectorUrl'] = Url::to(['/admin/files/connector']);
		}

		$this->registerPlugin();
	}

	/**
	 * Registers Jasny File Input Bootstrap plugin and the related events
	 */
	protected function registerPlugin()
	{
		$view = $this->getView();
		FileInputAsset::register($view);

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