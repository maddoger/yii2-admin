<?php

namespace maddoger\admin\models;

use maddoger\core\ModuleConfigurationModel as BaseModel;

class ModuleConfigurationModel extends BaseModel
{
	public $backendSortNumber;

	public function attributeHelp()
	{
		return [];
	}
}