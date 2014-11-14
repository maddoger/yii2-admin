<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin\widgets;
use Yii;
use yii\bootstrap\Alert;
use yii\bootstrap\Widget;
use yii\helpers\Html;

/**
 * Alerts implements flash display for session's flashes
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger/yii2-admin
 */
class Alerts extends Widget
{
    const FLASH_ERROR = 'error';
    const FLASH_SUCCESS = 'success';
    const FLASH_INFO = 'info';
    const FLASH_WARNING = 'warning';

    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        self::FLASH_ERROR => 'alert-danger',
        self::FLASH_SUCCESS => 'alert-success',
        self::FLASH_INFO => 'alert-info',
        self::FLASH_WARNING => 'alert-warning'
    ];
    /**
     * @var array the alert icons
     */
    public $alertIcons = [
        self::FLASH_ERROR => 'ban',
        self::FLASH_SUCCESS => 'check',
        self::FLASH_INFO => 'info',
        self::FLASH_WARNING => 'warning'
    ];

    /**
     * @var string
     */
    public $template = '<div class="alerts">{alerts}</div>';

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    public function run()
    {
        parent::init();

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        $alerts = '';

        foreach ($flashes as $type => $messages) {
            if (!isset($this->alertTypes[$type])) {
                /* initialize css class for each alert box */
                $type = self::FLASH_INFO;
            }

            if (!is_array($messages)) {
                $messages = [$messages];
            }

            foreach ($messages as $message) {
                $this->options['class'] = $this->alertTypes[$type] . $appendCss;

                /* assign unique id to each alert box */
                $this->options['id'] = $this->getId() . '-' . $type;

                $body = Html::tag('i', '',
                    ['class' => 'fa fa-'.$this->alertIcons[$type]]
                ) . $message;

                $alerts .= Alert::widget([
                    'body' => $body,
                    'closeButton' => $this->closeButton,
                    'options' => $this->options,
                ]);

            }

            $session->removeFlash($type);
        }

        if ($alerts) {
            return strtr($this->template, [
                '{alerts}' => $alerts
            ]);
        }

        return null;
    }

}