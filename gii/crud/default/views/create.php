<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

echo "<?php\n";
?>

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 */

$this->title = \Yii::t('<?= $generator->languageCategory ?>', 'Create <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('<?= $generator->languageCategory ?>', '<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">
	<?= "<?php " ?>echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
