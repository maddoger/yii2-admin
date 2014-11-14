<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin\controllers;

use maddoger\admin\models\LoginForm;
use maddoger\admin\models\PasswordResetRequestForm;
use maddoger\admin\models\ResetPasswordForm;
use maddoger\admin\models\SignupForm;
use maddoger\admin\models\User;
use maddoger\admin\widgets\Alerts;
use maddoger\core\BackendModule;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * SiteController for authorisation
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'error',
                            'reset-password',
                            'request-password-reset',
                            'captcha',
                            'install'
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'search'],
                        'roles' => ['admin.user.dashboard'],
                        'allow' => true,
                    ],
                    //For superuser
                    [
                        'allow' => (
                            $this->module->superUserId &&
                            Yii::$app->user->id &&
                            Yii::$app->user->id == $this->module->superUserId
                        )
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'error') {
            if (!Yii::$app->user->isGuest) {
                $this->layout = 'main';
            } else {
                $this->layout = 'minimal';
            }
        }
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'width' => 240,
                'height' => 100,
                'padding' => 1,
                'foreColor' => 0x3d9970,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionSearch($q)
    {
        $q = trim(strip_tags($q));

        /**
         * @var \maddoger\admin\Module $module
         */
        $module = $this->module;

        $sources = $module->searchSources ?: [];
        if ($module->searchUseModulesSources) {
//Get navigation from modules
            foreach (Yii::$app->modules as $moduleId => $module) {

                if (!($module instanceof \yii\base\Module)) {
                    $module = Yii::$app->getModule($moduleId, true);
                }

                if ($module instanceof BackendModule) {
                    $moduleSources = $module->getSearchSources();
                    if ($moduleSources) {
                        $sources = array_merge($sources, $moduleSources);
                    }
                }
            }
        }

        $content = [];

        //Data to models
        foreach ($sources as $source) {
            if (is_array($source)) {
                $roles = ArrayHelper::remove($source, 'roles');
                if ($roles) {
                    $can = false;
                    foreach ($roles as $role) {
                        if (Yii::$app->user->can($role)) {
                            $can = true;
                            break;
                        }
                    }
                    if (!$can) {
                        continue;
                    }
                }
                $source = Yii::createObject($source);
            }
            /**
             * @var \maddoger\core\search\BaseSearchSource $source
             */
            $result = $source->getResult($q);
            if ($result) {
                $content = array_merge($content, $result);
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $content;
        }


        return $this->render('search', [
            'query' => $q,
            'result' => $content,
        ]);
    }

    /**
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'base';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return string
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'base';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash(Alerts::FLASH_SUCCESS,
                    Yii::t('maddoger/admin', 'Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash(Alerts::FLASH_ERROR,
                    Yii::t('maddoger/admin', 'Sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * @param $token
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'base';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash(Alerts::FLASH_SUCCESS,
                Yii::t('maddoger/admin', 'New password was saved.'));
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionInstall()
    {
        //Check users count
        if (User::find()->count() > 0) {
            return $this->redirect(['index']);
        }

        $this->layout = 'base';
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $user = $model->signup()) {

            //Update roles
            RoleController::updateRoles(true, $user->id, $this->module->superUserRole);

            return $this->redirect(['index']);
        } else {
            return $this->render('signup', [
                'model' => $model,
            ]);
        }
    }
}