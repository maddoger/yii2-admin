<?php

namespace maddoger\admin\widgets;

use maddoger\admin\DateTimeEditorAsset;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * DateEditor Widget For Yii2 class file.
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 */

class TimeEditor extends DateTimeEditor
{
	public $jsFormat = 'HH:mm';
	public $phpFormat = 'H:i';

	public function init()
	{
		parent::init();
		$this->config['pickDate'] = false;
	}
}