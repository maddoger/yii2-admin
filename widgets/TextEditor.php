<?php

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
     * @var array the options
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
        $this->config['language'] = $appLanguage;

		if ($this->autogrow) {
			$this->config['extraPlugins'] = (isset($this->config['extraPlugins']) ? $this->config['extraPlugins'].',' : '') . 'autogrow';
			$this->config['removePlugins'] = (isset($this->config['removePlugins']) ? $this->config['removePlugins'].',' : '') .'resize';
			//$this->config['autoGrow_onStartup'] = true;
		}
		if ($this->maxLength !== null && $this->maxLength>0) {
			$this->config['extraPlugins'] = (isset($this->config['extraPlugins']) ? $this->config['extraPlugins'].',' : '') . 'wordcount';
			$this->config['wordcount'] = [
				'showWordCount' => false,
				'showCharCount' => true,
				 // Whether or not to include Html chars in the Char Count
				 'countHTML' => false,
				// Option to limit the characters in the Editor
				'charLimit' => $this->maxLength,
				// Option to limit the words in the Editor
				'wordLimit' => 'unlimited',
			];
		}

        $config = empty($this->config) ? '' : Json::encode($this->config);
        $js = "jQuery('" . $this->selector . "').ckeditor($config);";
        $view->registerJs($js);
    }
}