<?php

namespace rusporting\admin\models;

use rusporting\core\ModuleConfigurationModel as BaseModel;

class ModuleConfigurationModel extends BaseModel
{
	public $backendSortNumber;

	public function attributeHelp()
	{
		return [];
	}
}