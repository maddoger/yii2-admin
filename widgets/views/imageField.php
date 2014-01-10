<div class="fileinput fileinput-new" data-provides="fileinput">
	<div class="fileinput-new thumbnail" style="width: <?=$width?>px; height: <?=$height?>px;">
		<?=$thumbnail;?>
	</div>
	<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: <?=$width?>px; max-height: <?=$height?>px;"></div>
	<div>
		<span class="btn btn-default btn-file btn-block">
			<span class="fileinput-new"><?= Yii::t('rusporting/admin', 'Select image') ?></span>
			<span class="fileinput-exists"><?= Yii::t('rusporting/admin', 'Change') ?></span>
			<?=$field;?>
		</span>
		<a href="#" class="btn btn-default btn-block fileinput-exists" data-dismiss="fileinput"><?= Yii::t('rusporting/admin', 'Remove') ?></a>
	</div>
</div>