<div class="dashboard">
<ul class="dashboard-list">
<?php
	$list = [];

	foreach ($navigation as $moduleNavigation) {
		echo '<li class="well"><h3>'.$moduleNavigation['label'].'</h3>';
		if (isset($moduleNavigation['items']) && count($moduleNavigation['items'])>0) {
			echo '<ul>';
			foreach ($moduleNavigation['items'] as $link) {

				echo '<li><a href="'.\yii\helpers\Url::to($link['url']).'"><i class="fa fa-'.@$link['fa'].'"></i> '.$link['label'].'</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<ul>
			<li>
			<a href="'.\yii\helpers\Url::to($moduleNavigation['url']).'"><i class="fa fa-'.@$moduleNavigation['fa'].'"></i> '.$moduleNavigation['label'].'</a>
			</li>
			</ul>';
		}
		echo '</li>';
	}
?>
</ul>
</div>