<?php

use yii\base\View;
use rusporting\core\Module;
use yii\helpers\Html;
use rusporting\admin\widgets\ModelActiveForm;

/**
 * @var \yii\base\View $this
 * @var \rusporting\core\Module $module
 */

if ($configView !== null) {
	echo $this->renderFile($configView, $_params_, $this->context);
} else {
?>
<div class="row">
	<div class="col-lg-12">
		<?php
			$attributesOptions = $model->attributeOptions();
			$form = ModelActiveForm::begin(['model' => $model, 'options' => array('class' => 'form-horizontal'),
				'fieldConfig' => array(
					'labelOptions' => ['class' => 'control-label col-lg-3'],
					'template' => "{label}\n<div class=\"col-lg-9\">{input}\n{error}\n{hint}</div>",
					'hintOptions' => ['class' => 'hint-block text-muted small'],
				)]
			);
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