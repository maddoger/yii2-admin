<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="error-container">
				<h1 class="code"><?= $code ?></h1>
				<h2 class="name"><?= $name ?></h2>
				<div class="error-details">
					<?= $message ?>
				</div> <!-- /error-details -->
				<div class="error-actions">
					<a href="<?= Yii::$app->request->getReferrer(); ?>" class="btn btn-primary btn-lg">
						<i class="fa fa-chevron-left"></i> <?= Yii::t('rusporting/admin', 'Return back') ?>
					</a> &nbsp;
					<?php if (Yii::$app->user->isGuest) { ?>
					<a href="<?php Yii::$app->urlManager->createUrl('/..') ?>" class="btn btn-info btn-lg">
						<i class=fa fa-home"></i> <?= Yii::t('rusporting/admin', 'Back to index') ?>
					</a>
					<?php } else { ?>
					<a href="<?= Yii::$app->homeUrl ?>" class="btn btn-info btn-lg">
						<i class=fa fa-home"></i> <?= Yii::t('rusporting/admin', 'Back to dashboard') ?>
					</a>
					<?php }	?>
				</div>
			</div>
		</div>
	</div>
</div>