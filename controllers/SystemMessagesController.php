<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\admin\controllers;

use maddoger\core\models\SystemMessage;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SystemMessagesController.php
 *
 * @author Vitaliy Syrchikov <maddoger@gmail.com>
 * @link http://syrchikov.name
 * @package maddoger/yii2-admin
 */
class SystemMessagesController extends Controller
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
                        'actions' => ['index'],
                        'roles' => ['admin.system-messages.viewList'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['view'],
                        'roles' => ['admin.system-messages.viewDetail'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete', 'delete-all'],
                        'allow' => true,
                        'roles' => ['admin.system-messages.delete'],
                        'verbs' => ['POST'],
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
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SystemMessage::findLastMessages(0)
        ]);
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single SystemMessage model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing SystemMessage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes all existing SystemMessage models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDeleteAll()
    {
        SystemMessage::deleteAll();
        return $this->redirect(['index']);
    }

    /**
     * Finds the SystemMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SystemMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('maddoger/admin', 'The requested system message does not exist.'));
        }
    }
}