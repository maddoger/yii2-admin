<?php
use maddoger\admin\AdminAsset;
use yii\helpers\Html;
use maddoger\admin\widgets\Breadcrumbs;
use maddoger\admin\widgets\Menu;
use maddoger\user\widgets\Alert;

/**
 * @var \yii\web\View $this
 * @var string $content
 * @var \maddoger\admin\AdminModule $module
 */

AdminAsset::register($this);
$module = Yii::$app->getModule('admin');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php if (!empty($this->title)) {
			echo Html::encode($this->title), ' - ';
		}
		echo Html::encode($module->pageTitle); ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
/*NavBar::begin([
	'brandLabel' => 'My Company',
	'brandUrl' => Yii::$app->homeUrl,
	'options' => [
		'class' => 'navbar-inverse navbar-fixed-left',
	],
]);
$menuItems = [
	['label' => 'Home', 'url' => ['/']],
];
if (Yii::$app->user->isGuest) {
	$menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
	$menuItems[] = ['label' => 'Logout (' . Yii::$app->user->identity->username .')' , 'url' => ['/site/logout']];
}
echo Nav::widget([
	'options' => ['class' => 'navbar-nav navbar-right'],
	'items' => $menuItems,
]);
NavBar::end();*/
?>

<div id="wrapper">

	<!-- Sidebar -->
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="main-nav-bar">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only"><?= Yii::t('maddoger/admin', 'Toggle navigation') ?></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>"><?php
				$brandLogo = $module->brandLogo;
				if (!empty($brandLogo)) {
					echo '<img src="'.@Html::encode(Yii::getAlias($module->brandLogo)).'" alt="'.Html::encode($module->brandName).'" class="brand-logo" />';
				} else {
					echo Html::encode($module->brandName);
				}
				?></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<?php
			//Modules navigation
			$items = $module->getBackendModulesNavigationItems();
			if ($items) {
				$route = Yii::$app->controller->getRoute();
				if (Yii::$app->controller->module->id == 'backend') {
					$route = str_replace('/backend', '', $route);
				}
				array_unshift($items, ['label'=> Yii::t('maddoger/admin', 'Dashboard'), 'url'=> ['/admin/dashboard/index'], 'fa' => 'dashboard']);
				echo Menu::widget([
					'options' => ['class' => 'nav navbar-nav side-nav'],
					'items' => $items,
					'route' => $route,
				]);
			}
			?>
			<!--<div class="copyright bottom">
				<p class="pull-left">&copy; Руспортинг <? /*= date('Y') */ ?></p>
				</div>-->
			<?php
			if (!Yii::$app->user->isGuest) {
				$identity = Yii::$app->user->identity;

				?>
				<ul class="nav navbar-nav navbar-right navbar-user">
					<li><a href="<?= Yii::$app->urlManager->createUrl('/..') ?>"><i class="fa fa-reply"></i> <?= Yii::t('maddoger/admin', 'To site') ?></a></li>
					<li class="divider"></li>
					<li class="dropdown user-dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
								class="fa fa-user"></i> <?= Html::encode($identity->short_name) ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="<?= Yii::$app->urlManager->createUrl('/user/users/view', ['id' => $identity->getId()]) ?>"><i class="fa fa-user"></i> <?= Yii::t('maddoger/admin', 'Profile') ?></a></li>
							<!--<li><a href="#"><i class="fa fa-envelope"></i> Inbox <span class="badge">7</span></a></li>-->
							<!--<li><a href="<?/*= Yii::$app->urlManager->createUrl('/config') */?>"><i class="fa fa-gear"></i> <?/*= Yii::t('maddoger/admin', 'Settings') */?></a></li>-->
							<li class="divider"></li>
							<li><a href="<?= Yii::$app->urlManager->createUrl('/user/logout') ?>"><i class="fa fa-power-off"></i> <?= Yii::t('maddoger/admin', 'Log Out') ?></a></li>
						</ul>
					</li>
				</ul>
			<?php
			}
			?>
		</div>
		<!-- /.navbar-collapse -->
	</nav>

	<div id="page-wrapper">

		<div class="row">
			<div class="col-lg-12">
				<h1><?php
					if (isset($controller->title)) {
						echo Html::encode($controller->title);
						if ($controller->subtitle !== null) {
							echo ' <small>', Html::encode($controller->subtitle), '</small>';
						}
					} else {
						echo Html::encode($this->title);
					} ?></h1>
				<?php
				$breadcrumbs = isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : null;
				if (!$breadcrumbs) {
					$breadcrumbs = [['label'=>Yii::t('maddoger/admin', 'Dashboard'), 'fa'=>'dashboard']];
				} else {
					array_unshift($breadcrumbs, ['label'=>Yii::t('maddoger/admin', 'Dashboard'), 'url'=>['/admin/dashboard/index'], 'fa'=>'dashboard']);
				}
				echo Breadcrumbs::widget(['homeLink'=>false, 'links' => $breadcrumbs]);
				?>
				<?= Alert::widget() ?>
			</div>
		</div>
		<!-- /.row -->
		<?= $content ?>
	</div>
	<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
