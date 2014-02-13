<?php
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

\maddoger\elfinder\CoreAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<title><?= Html::encode($this->title) ?></title>
	<?php
		$this->registerCss('
		body, html {
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
			overflow: hidden;
		}
		#elfinder {
			width: auto;
			height: 100% !important;
		}
		');
	?>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="elfinder"></div>
<?php $this->endBody() ?>
<script type="text/javascript" charset="utf-8">
	// Helper function to get parameters from the query string.
	function getUrlParam(paramName) {
		var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
		var match = window.location.search.match(reParam) ;

		return (match && match.length > 1) ? match[1] : '' ;
	}

	$().ready(function() {
		var funcNum = getUrlParam('CKEditorFuncNum');

		var elf = $('#elfinder').elfinder({
			url : '<?= Html::url(['files/connector']); ?>',
			lang: '<?= substr(Yii::$app->language,0,2) ?>',
			getFileCallback : function(file) {
				window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
				window.close();
			},
			resizable: false
		}).elfinder('instance');
	});
</script>
</body>
</html>
<?php $this->endPage() ?>
