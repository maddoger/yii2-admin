<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

/** @var \yii\db\ActiveRecord $model */
$model = new $generator->modelClass;
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
	$safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

	<?= "<?php " ?>$form = ActiveForm::begin([
			'options' => array('class' => 'form-horizontal'),
			'fieldConfig' => array(
				'labelOptions' => ['class' => 'control-label col-lg-2'],
				'template' => "{label}\n<div class=\"col-lg-10\">{input}\n{error}\n{hint}</div>",
				'hintOptions' => ['class' => 'hint-block text-muted small'],
			),
		]); ?>

	<p>
		<?= "<?= " ?>Html::submitButton($model->isNewRecord ? \Yii::t('<?= $generator->languageCategory ?>', 'Create') : \Yii::t('<?= $generator->languageCategory ?>', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</p>

<?php foreach ($safeAttributes as $attribute) {
	echo "\t\t<?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
} ?>
		<p>
			<?= "<?= " ?>Html::submitButton($model->isNewRecord ? \Yii::t('<?= $generator->languageCategory ?>', 'Create') : \Yii::t('<?= $generator->languageCategory ?>', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</p>

	<?= "<?php " ?>ActiveForm::end(); ?>

</div>
