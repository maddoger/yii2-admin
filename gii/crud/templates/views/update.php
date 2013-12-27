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

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 */

$this->title = \Yii::t('<?= $generator->languageCategory ?>', 'Update <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>: {name}', ['name'=> $model-><?= $generator->getNameAttribute() ?>']);
$this->params['breadcrumbs'][] = ['label' => \Yii::t('<?= $generator->languageCategory ?>', '<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = \Yii::t('<?= $generator->languageCategory ?>', 'Update');
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">
	<?= "<?php " ?>echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
