<div class="dashboard">
<ul class="dashboard-list">
<?php
	$list = [];

	foreach ($navigation as $moduleNavigation) {
		if (isset($moduleNavigation['roles']) && !Yii::$app->user->checkAccess($moduleNavigation['roles'])) {
			continue;
		}
		echo '<li class="well"><h3>'.$moduleNavigation['label'].'</h3>';
		if (isset($moduleNavigation['items']) && count($moduleNavigation['items'])>0) {
			echo '<ul>';
			foreach ($moduleNavigation['items'] as $link) {

				if (isset($link['roles']) && !Yii::$app->user->checkAccess($link['roles'])) {
					continue;
				}

				echo '<li><a href="'.\yii\helpers\Html::url($link['url']).'"><i class="fa fa-'.@$link['fa'].'"></i> '.$link['label'].'</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<ul>
			<li>
			<a href="'.\yii\helpers\Html::url($moduleNavigation['url']).'"><i class="fa fa-'.@$moduleNavigation['fa'].'"></i> '.$moduleNavigation['label'].'</a>
			</li>
			</ul>';
		}
		echo '</li>';
	}
?>
</ul>
</div>