<?php

/* @var $this yii\web\View */

use maddoger\admin\Module;
use maddoger\admin\widgets\Menu;
use maddoger\core\models\SystemMessage;
use yii\helpers\Html;

$this->title = Yii::t('maddoger/admin', 'Dashboard');

/**
 * @var maddoger\admin\models\User $user
 */
$user = Yii::$app->user->identity;

$menu = Module::getInstance()->getSidebarMenu();

?>
<div class="dashboard">
    <?= Menu::widget([
        'items' => $menu,
        'activateParents' => true,
        'labelTemplate' => '<a href="#">{icon}{label}</a>',
        'submenuTemplate' => "\n<ul class=\"menu\">\n{items}\n</ul>\n",
        'submenuItemClass' => 'treeview',
        'options' => [
            'class' => 'dashboard-menu',
        ],
    ]);
    ?>

    <?php if (Yii::$app->user->can('admin.system-messages.viewList')) { ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-warning"></i>&nbsp;<?= Yii::t('maddoger/admin',
                        'System messages') ?>
                    <small><?= Html::a(Yii::t('maddoger/admin', 'more info'), ['system-messages/index']) ?></small>
                </h3>
            </div>
            <!-- /.panel-header -->
            <div class="panel-body">
                <?php
                $messages = SystemMessage::findLastMessages()->limit(10)->all();
                if ($messages) {
                    foreach ($messages as $message) {
                        $options = [
                            'class' => 'callout'
                        ];
                        if ($message['type']) {
                            Html::addCssClass($options, 'callout-' . $message['type']);
                        }
                        echo Html::tag('div',
                            Html::tag('h4', $message->title) .
                            Html::tag('span', Yii::$app->formatter->asDatetime($message->created_at),
                                ['class' => 'small text-muted']) .
                            Html::tag('p', $message->message),
                            $options
                        );
                    }
                } else {
                    echo '<p class="text-muted">' . Yii::t('maddoger/admin', 'No messages found.') . '</p>';
                }
                ?>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    <?php } ?>
</div>