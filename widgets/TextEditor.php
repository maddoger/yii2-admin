<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace rusporting\admin\widgets;

use rusporting\admin\TextEditorAsset;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * CKEditor Widget For Yii2 class file.
 *
 * @property array $plugins
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 */

class TextEditor extends InputWidget
{
    /**
     * @var array the options for the Imperavi Redactor.
     * Please refer to the corresponding [Imperavi Web page](http://imperavi.com/redactor/docs/)  for possible options.
     */
    public $config = [];

	public $options = [];

    /**
     * @var array plugins that you want to use
     */
    public $plugins = [];

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
	 * @var bool Auto grow
	 */
	public $autogrow = true;

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
		if (!isset($this->config['filebrowserBrowseUrl'])) {
			$this->config['filebrowserBrowseUrl'] = Yii::$app->urlManager->createUrl('/admin/files/dialog');
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

			if (!is_null($this->model)) {
				echo Html::activeTextarea($this->model, $this->attribute, $this->options);
			} else {
				echo Html::textarea($this->attribute, $this->value, $this->options);
			}

		}

        TextEditorAsset::register($this->getView());
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
        if($appLanguage != 'en') // By default $language = 'en-US', someone use underscore
            $this->config['language'] = $appLanguage;

		if ($this->autogrow) {
			$this->config['extraPlugins'] = (isset($this->config['extraPlugins']) ? $this->config['extraPlugins'].',' : '') . 'autogrow';
			$this->config['removePlugins'] = (isset($this->config['removePlugins']) ? $this->config['removePlugins'].',' : '') .'resize';
			$this->config['autoGrow_onStartup'] = true;
		}

        $config = empty($this->config) ? '' : Json::encode($this->config);
        $js = "jQuery('" . $this->selector . "').ckeditor($config);";
        $view->registerJs($js);
    }
}