<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin\widgets;
use maddoger\admin\Module;
use Yii;
use yii\base\Widget;

/**
 * Search form widget
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger/yii2-admin
 */
class SearchForm extends Widget
{
    /**
     * @var
     */
    public $actionUrl;

    /**
     * @var
     */
    public $options = [];

    /**
     * @var string
     */
    public $view = 'searchForm';

    /**
     * @var string
     */
    public $queryParam = 'q';

    /**
     * @var array plugin options
     */
    public $clientOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->actionUrl) {
            $this->actionUrl = ['/'.Module::getInstance()->id.'/site/search'];
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $query = Yii::$app->request->get($this->queryParam);
        $query = trim(strip_tags($query));

        return $this->render($this->view, [
            'widget' => $this,
            'query' => $query,
        ]);
    }
}