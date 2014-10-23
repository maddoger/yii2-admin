<?php

namespace maddoger\admin\controllers;

use Yii;
use maddoger\admin\models\User;
use maddoger\admin\models\search\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'roles' => ['admin.user.view'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create'],
                        'roles' => ['admin.user.create'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['update'],
                        'roles' => ['admin.user.update'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin.user.delete'],
                        'verbs' => ['POST'],
                    ],
                    [
                        'actions' => ['profile'],
                        'roles' => ['admin.user.profile'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            switch (Yii::$app->request->post('redirect')) {
                case 'exit':
                    return $this->redirect(['index']);
                case 'new':
                    return $this->redirect(['create']);
                default:
                    return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates own profile.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProfile()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);
        $model->scenario = 'profile';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('maddoger/admin', 'Changes have been saved.'));
            return $this->refresh();
        } else {
            return $this->render('profile', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['status' => User::STATUS_DELETED]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
