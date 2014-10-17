<?php

/**
 * @var $this yii\web\View
 * @var string $content
 */
?>
<?php $this->beginContent('@maddoger/admin/views/layouts/base.php'); ?>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <aside class="right-side strech">
            <section class="content-header">
                <?php if (isset($this->params['header'])): ?>
                    <h1><?= $this->params['header'] ?></h1>
                <?php endif ?>
                <?php echo \yii\widgets\Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]); ?>
            </section>
            <section class="content">
                <?php //echo \yz\admin\widgets\Alerts::widget(); ?>
                <?= $content; ?>
            </section>
        </aside>
    </div>
<?php $this->endContent(); ?>