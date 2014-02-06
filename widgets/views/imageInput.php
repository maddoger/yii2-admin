<?php
/**
 * @var yii\web\View $this
 * @var rusporting\admin\widgets\FileInput $widget
 * @var string $field
 */
?>
<div class="file-input <?= (empty($value) ? 'no-file' : 'has-file') ?>" id="<?= $widget->id ?>">

	<?= $field ?>

	<div class="preview-container">
		<!--<div class="no-file message"><?php /*echo Yii::t('rusporting/admin', 'No file'); */?><br /><br /></div>-->
		<div class="preview no-file"><img data-src="holder.js/<?=
			($widget->previewWidth ? $widget->previewWidth : '200') .'x'.($widget->previewHeight ? $widget->previewHeight : '200') ?>/text:<?=
			($widget->width ? $widget->width : '...') .'x'.($widget->height ? $widget->height : '***') ?>" alt="<?=
			($widget->width ? $widget->width : '...') .'x'.($widget->height ? $widget->height : '***') ?>"></div>
		<div class="preview has-file" style="width: <?=($widget->previewWidth ? $widget->previewWidth.'px' : 'auto')?>; height: <?=($widget->previewHeight ? $widget->previewHeight.'px' : 'auto')?>;"><? if (!empty($value)) { ?><a href="<?= \yii\helpers\Html::encode($value) ?>" target="_blank" title="<?= \yii\helpers\Html::encode($value) ?>"><?= $widget->thumbnail ?></a><?php } ?></div>
		<div class="has-file file-info"><span class="filename"></span></div>
	</div>
	<div class="btn-toolbar actions-bar">

		<div class="btn-group">
			<?php if ($widget->upload) { ?>
				<span class="btn btn-success choose-button">
					<i class="fa fa-upload"></i>
					<span class="no-file"><?php echo Yii::t('rusporting/admin', 'Upload…'); ?></span>
					<span class="has-file"><?php echo Yii::t('rusporting/admin', 'Replace…'); ?></span>
					<input type="file" name="<?= $widget->name ?>" class="afile" accept="image/*" />
				</span>

			<?php } else {  ?>
				<a href="javascript:void(0);" class="browse-server-button btn btn-success ">
					<span class="no-file"><?php echo Yii::t('rusporting/admin', 'Browse server'); ?></span>
					<span class="has-file"><?php echo Yii::t('rusporting/admin', 'Replace…'); ?></span>
				</a>
			<?php } ?>

			<?php if ($widget->setUrl || ($widget->upload && $widget->browseServer)) : ?>
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only"><?php echo Yii::t('rusporting/admin', 'Toggle Dropdown'); ?></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<?php if ($widget->upload && $widget->browseServer) : ?>
						<li><a href="javascript:void(0);" class="browse-server-button"><?php echo Yii::t('rusporting/admin', 'Browse server'); ?></a></li>
					<?php endif; ?>
					<?php if ($widget->setUrl) : ?>
						<li><a href="javascript:void(0);" class="set-url-button"><?php echo Yii::t('rusporting/admin', 'Set URL'); ?></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
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