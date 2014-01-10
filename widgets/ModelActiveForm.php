<?php

namespace rusporting\admin\widgets;

use yii\widgets\ActiveForm;
use yii\base\Model;

class ModelActiveForm extends ActiveForm
{
	/**
	 * @var Model model for data
	 */
	public $model = null;

	public $fields = null;

	public function setModel($model)
	{
		$this->model = $model;
		return $this->updateFields();
	}

	/**
	 * Returns fields array
	 * @return null|array
	 */
	public function getFields()
	{
		if (!$this->fields && $this->model) {
			$this->updateFields();
		}
		return $this->fields;
	}

	/**
	 * Update fields from form
	 * @return bool
	 */
	public function updateFields()
	{
		if (!$this->model) {
			return false;
		}
		$modelAttributes = $this->model->attributes();
		$options = (method_exists($this->model, 'attributeOptions')) ? $this->model->attributeOptions() : [];
		//var_dump($options);
		foreach ($modelAttributes as $key) {
			$f = isset($options[$key]) ? $options[$key] : [];
			foreach ($f as $k=>$v) {

			}
			$this->fields[$key] = $f;
		}
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function errorSummary($options = [])
	{
		return parent::errorSummary($this->model, $options);
	}

	/**
	 * @inheritdoc
	 */
	public function field($attribute, $options = [])
	{
		return parent::field($this->model, $attribute, $options);
	}

	public function fields()
	{
		$res = '';
		$fields = $this->getFields();
		foreach ($fields as $field=>$options) {
			$f = $this->field($field);
			switch ($options['type']) {
				case 'checkbox':
					$f->checkbox(isset($options['inputOptions']) ? $options['inputOptions'] : []);
					break;
				case 'checkboxList':
					$f->checkboxList($options['items'], isset($options['inputOptions']) ? $options['inputOptions'] : []);
					break;
				case 'dropDownList':
					$f->dropDownList($options['items'], isset($options['inputOptions']) ? $options['inputOptions'] : []);
					break;
				case 'listBox':
					$f->listBox($options['items'], isset($options['inputOptions']) ? $options['inputOptions'] : []);
					break;
				case 'textarea':
					$f->textarea(isset($options['inputOptions']) ? $options['inputOptions'] : []);
					break;
				case 'password':
					$f->textarea(isset($options['inputOptions']) ? $options['inputOptions'] : []);
					break;
				case 'widget':
					$f->widget($options['class'], isset($options['inputOptions']) ? $options['inputOptions'] : []);
					break;
				case 'radio':
					$f->radio($options['items']);
					break;
				default:
					$f->textInput(isset($options['inputOptions']) ? $options['inputOptions'] : []);
			}

			if (isset($options['hint'])) {
				$f->hint($options['hint'], isset($options['hintOptions']) ? $options['hintOptions'] : []);
			}
			$res .= $f;
		}
		return $res;
	}
}