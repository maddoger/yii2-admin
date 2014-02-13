<?php

use yii\base\View;
use maddoger\core\Module;
use yii\helpers\Html;

/**
 * @var \yii\base\View $this
 * @var \maddoger\core\Module $module
 */
$this->params['breadcrumbs'] = [['label'=>Yii::t('maddoger/admin', 'Modules'), 'fa'=>'gears']];
?>
<div class="row">
	<div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-hover table-sorter">
				<thead>
				<tr>
					<th><?= Yii::t('maddoger/admin', 'ID') ?> <i class="fa fa-sort"></i></th>
					<th><?= Yii::t('maddoger/admin', 'Module name') ?> <i class="fa fa-sort"></i></th>
					<th><?= Yii::t('maddoger/admin', 'Description') ?> <i class="fa fa-sort"></i></th>
					<th><?= Yii::t('maddoger/admin', 'Version') ?> <i class="fa fa-sort"></i></th>
					<th><?= Yii::t('maddoger/admin', 'Configuration') ?> <i class="fa fa-sort"></i></th>
				</tr>
				</thead>
				<tbody>
				<?php if ($modules) { foreach ($modules as $module) {?>
				<tr>
					<td><?= Html::encode($module->id) ?></td>
					<td><?= Html::encode($module->getName()) ?></td>
					<td><?= Html::encode($module->getDescription()) ?></td>
					<td><?= Html::encode($module->getVersion()) ?></td>
					<td>
						<?php if ($module->getConfigurationModel() !== null) {
							echo Html::a('<i class="fa fa-gear"></i> '.Yii::t('maddoger/admin', 'Configuration'),['modules/config', 'module'=>$module->id]);
							} else {
							echo '<span class="text-muted">'.Yii::t('maddoger/admin', 'No configuration').'</span>';
							}
						?>
					</td>
				</tr>
				<?php }} else { ?>
				<tr>
					<td><?= Yii::t('maddoger/admin', 'Modules not found.') ?></td>
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>