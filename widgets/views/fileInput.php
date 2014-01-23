<?php
/**
 * @var yii\web\View $this
 * @var rusporting\admin\widgets\FileInput $widget
 * @var string $field
 */
?>
<div class="file-input simple-file <?= (empty($value) ? 'no-file' : 'has-file') ?>" id="<?= $widget->id ?>">

	<?= $field ?>

	<div class="preview-container">
		<div class="no-file message"><?php echo Yii::t('rusporting/admin', 'No file'); ?><br /><br /></div>
		<div class="preview has-file"><a href="<?= \yii\helpers\Html::encode($value) ?>" target="_blank" title="<?= \yii\helpers\Html::encode($value) ?>"><?= \yii\helpers\Html::encode(basename($value)) ?></a></div>
	</div>
	<div class="btn-toolbar actions-bar">

		<div class="btn-group">
			<span class="btn btn-success choose-button">
				<i class="fa fa-upload"></i>
				<span class="no-file"><?php echo Yii::t('rusporting/admin', 'Upload…'); ?></span>
				<span class="has-file"><?php echo Yii::t('rusporting/admin', 'Replace…'); ?></span>
				<input type="file" name="<?= $widget->name ?>" class="afile" />
        	</span>

			<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only"><?php echo Yii::t('rusporting/admin', 'Toggle Dropdown'); ?></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="javascript:void(0);" class="browse-server-button"><?php echo Yii::t('rusporting/admin', 'Browse server'); ?></a></li>
			</ul>
		</div>

        <span class="btn btn-warning <?= (empty($value) ? 'disabled' : '') ?> clear-button">
			<i class="fa fa-trash-o"></i> <?php echo Yii::t('rusporting/admin', 'Remove'); ?>
		</span>

		<button type="reset" class="btn btn-default disabled reset-button">
			<i class="fa fa-refresh"></i> <?php echo Yii::t('rusporting/admin', 'Reset'); ?>
		</button>
	</div>
	<div class="overlay">
		<div class="overlay-bg">&nbsp;</div>
		<div class="drop-hint">
                <span
					class="drop-hint-info"><?php echo Yii::t('rusporting/admin', 'Drop Image Here…'); ?></span>
		</div>
	</div>
</div>