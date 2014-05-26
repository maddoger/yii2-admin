<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('<?= $generator->languageCategory ?>', '<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

	<p>
		<?= "<?= " ?>Html::a(\Yii::t('<?= $generator->languageCategory ?>', 'Update'), ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
		<?= "<?php " ?>echo Html::a(\Yii::t('<?= $generator->languageCategory ?>', 'Delete'), ['delete', <?= $urlParams ?>], [
			'class' => 'btn btn-danger',
			'data-confirm' => \Yii::t('<?= $generator->languageCategory ?>', 'Are you sure to delete this item?'),
			'data-method' => 'post',
		]); ?>
	</p>

	<?= "<?php " ?>echo DetailView::widget([
		'model' => $model,
		'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
	foreach ($generator->getColumnNames() as $name) {
		echo "\t\t\t'" . $name . "',\n";
	}
} else {
	foreach ($generator->getTableSchema()->columns as $column) {
		$format = $generator->generateColumnFormat($column);
		echo "\t\t\t'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
	}
}
?>
		],
	]); ?>

</div>