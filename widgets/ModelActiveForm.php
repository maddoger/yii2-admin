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
		foreach ($modelAttributes as $key) {
			$this->fields[$key] = [];
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
			$res .= $this->field($field)->textInput($options);
		}
		return $res;
	}
}