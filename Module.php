<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin;

use maddoger\core\BackendModule;
use Yii;

/**
 * Module
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger\admin
 */
class Module extends BackendModule
{
    /**
     * Init module
     */
    public function init()
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['maddoger/admin'])) {

            Yii::$app->i18n->translations['maddoger/admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@maddoger/admin/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'maddoger/admin' => 'maddoger/admin.php',
                ],
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('maddoger/admin', 'Admin Panel Module');
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return 'dev';
    }
}