<?php

/**
 * @var $this yii\web\View
 * @var string $content
 */
use maddoger\admin\AdminModule;
use maddoger\admin\widgets\Alerts;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['bodyClass'] = 'skin-blue';

/**
 * @var \maddoger\admin\AdminModule $adminModule
 */
$adminModule = AdminModule::getInstance();

$logo = $adminModule->logoText ?: Yii::$app->name;
if ($adminModule->logoUrl) {
    $logo = Html::img($adminModule->logoUrl, ['alt' => $logo, 'class' => 'icon']);
}
$header = isset($this->params['header']) ? $this->params['header'] : $this->title;

$this->title = (empty($this->title) ? '' : $this->title.' :: ').Yii::$app->name;

?>
<?php $this->beginContent('@maddoger/admin/views/layouts/base.php'); ?>
    <!-- header logo: style can be found in header.less -->
    <header class="header">
    <a href="<?= Url::home() ?>" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <?= $logo ?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>
    <div class="navbar-right">
    <ul class="nav navbar-nav">
    <?= $this->render($adminModule->headerNotificationsView) ?>
    <?= $this->render($adminModule->headerUserView) ?>
    </ul>
    </div>
    </nav>
    </header>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <?= $this->render($adminModule->sidebarView) ?>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><?= $header ?></h1>
                <?php echo \yii\widgets\Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]); ?>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php echo Alerts::widget() ?>
                <?= $content; ?>
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->
<?php $this->endContent(); ?>