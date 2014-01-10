<?php

use rusporting\admin\widgets\ModelActiveForm;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \rusporting\core\DynamicModel $model
 */

?>
<div class="row">
	<div class="col-lg-6">
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