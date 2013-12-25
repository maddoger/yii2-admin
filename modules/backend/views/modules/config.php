<?php

use yii\base\View;
use rusporting\core\Module;
use yii\helpers\Html;

/**
 * @var \yii\base\View $this
 * @var \rusporting\core\Module $module
 */
$this->params['breadcrumbs'] = [
	['label'=>Yii::t('rusporting/admin', 'Modules'), 'fa'=>'gears', 'url'=> ['/admin/modules']],
	['label'=>$module->getName(), 'url'=> ['/admin/modules/config', 'module'=>$module->id], 'fa'=>$module->getFaIcon()],
	['label'=>Yii::t('rusporting/admin', 'Configuration')],
];
?>
<div class="row">
	<div class="col-lg-12">

	</div>
</div>