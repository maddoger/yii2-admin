<?php

namespace maddoger\admin\widgets;

use maddoger\admin\DateTimeEditorAsset;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * DateTimeEditor Widget For Yii2 class file.
 *
 * @property array $plugins
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 */

class DateTimeEditor extends InputWidget
{
    public $config = [];

	public $options = [];

    /*
     * @var object model for active text area
     */
    public $model = null;

    /*
     * @var string selector for init js scripts
     */
    protected $selector = null;

    /*
     * @var string name of textarea tag or name of attribute
     */
    public $attribute = null;

    /*
     * @var string value for text area (without model)
     */
    public $value = '';

	/**
	 * @var string format
	 */
	public $jsFormat = 'DD.MM.YYYY - HH:mm';

	/**
	 * @var string format
	 */
	public $phpFormat = 'd.m.Y - H:i';

	/**
	 * @var null|int Max characters count. Default is null (unlimited)
	 */
	public $maxLength = null;

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->config['id'])) {
            $this->config['id'] = $this->getId();
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
		if (!$this->selector) {
			$this->selector = '#' . $this->config['id'];
			$this->options['id'] = $this->config['id'];

			if (!isset($this->options['class'])) {
				$this->options['class'] = 'form-control';
			}

            $fieldName = $this->name ? $this->name : $this->attribute;

			if (!is_null($this->model)) {
				$fieldName = Html::getInputName($this->model, $this->attribute);
				if (!array_key_exists('id', $this->options)) {
					$this->options['id'] = Html::getInputId($this->model, $this->attribute);
				}
				if (empty($this->value)) {
					$value = Html::getAttributeValue($this->model, $this->attribute);
					if ($value !== null) {
						if (!is_numeric($value)) {
							$value = strtotime($value);
							$this->value = date($this->phpFormat, $value);
						}
					}
				}
			}

			if (!isset($this->options['class'])) {
				$this->options['class'] = 'form-control';
			}

			echo '<div id="'.$this->config['id'].'" class="input-group datetime-editor">';
			echo Html::textInput($fieldName, $this->value, $this->options);
			echo '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>';
			echo '</div>';
		}

        DateTimeEditorAsset::register($this->getView());
        $this->registerClientScript();
    }

    /**
     * Registers CKEditor JS
     */
    protected function registerClientScript()
    {
        $view = $this->getView();

        /*
         * Language fix
         * @author <https://github.com/sim2github>
         */
        $appLanguage = strtolower(substr(Yii::$app->language , 0, 2)); //First 2 letters
        $this->config['language'] = $appLanguage;
		$this->config['format'] = $this->jsFormat;

        $config = empty($this->config) ? '' : Json::encode($this->config);
        $js = "jQuery('" . $this->selector . "').datetimepicker($config);";
        $view->registerJs($js);
    }
}