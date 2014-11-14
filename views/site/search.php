<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var string $query */
/* @var mixed $result */

$this->title = Yii::t('maddoger/admin', 'Search: {query}', ['query' => $query]);
$this->params['header'] = Yii::t('maddoger/admin', 'Search: {query}', ['query' => Html::encode($query)]);

echo '<div class="search-page"><div class="panel"><div class="panel-heading"><div class="panel-title">'.
    Yii::t('maddoger/admin', 'Results').'</div></div><div class="panel-body">';
if ($result) {
    $items = [];
    foreach ($result as $item) {
        $items[] =
            Html::a($item['label'], $item['url']).Html::tag('div', $item['url'], ['class' => 'url']);
    }
    echo Html::ul($items, ['encode' => false, 'class' => 'search-results']);
} else {
    echo Yii::t('maddoger/admin', 'Nothing found.');
}
echo '</div></div></div>';

