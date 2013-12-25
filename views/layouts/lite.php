<?php
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
BootstrapAsset::register($this);
BootstrapPluginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<style>
		.error-container {
			text-align: center;
		}
		.error-container .code {
			color: #fff;
			font-size: 200px;
			text-stroke: 1px transparent;
			padding: 110px 0 40px 0;
			display: block;
			text-shadow: 0 1px 0 #ccc, 0 2px 0 #c9c9c9, 0 3px 0 #bbb, 0 4px 0 #b9b9b9, 0 5px 0 #aaa, 0 6px 1px rgba(0,0,0,.1), 0 0 5px rgba(0,0,0,.1), 0 1px 3px rgba(0,0,0,.3), 0 3px 5px rgba(0,0,0,.2), 0 5px 10px rgba(0,0,0,.25), 0 10px 10px rgba(0,0,0,.2), 0 20px 20px rgba(0,0,0,.15);
		}
		.error-container .error-actions {
			margin-top: 40px;
		}
	</style>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container" id="content">
	<?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
