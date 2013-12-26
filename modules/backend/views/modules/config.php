<?php

use yii\base\View;
use rusporting\core\Module;
use yii\helpers\Html;
use rusporting\admin\widgets\ModelActiveForm;

/**
 * @var \yii\base\View $this
 * @var \rusporting\core\Module $module
 */
$this->params['breadcrumbs'] = [
	['label'=>Yii::t('rusporting/admin', 'Modules'), 'fa'=>'gears', 'url'=> ['/admin/modules']],
	['label'=>$module->getName(), 'url'=> ['/admin/modules/config', 'module'=>$module->id], 'fa'=>$module->getFaIcon()],
	['label'=>Yii::t('rusporting/admin', 'Configuration')],
];

if ($configView !== null) {
	echo $this->renderFile($configView, $_params_, $this->context);
} else {
?>
<div class="row">
	<div class="col-lg-12">
		<?php
			$attributesOptions = $model->attributeOptions();
			$form = ModelActiveForm::begin(['model' => $model]);
			echo $form->fields();
		?>
		<br>

		<div class="form-group">
			<?= Html::submitButton(Yii::t('rusporting/admin', 'Save'), ['class' => 'btn btn-primary']) ?>
		</div>

		<?php
		ModelActiveForm::end();
		?>
	</div>
</div>
<?php } ?>