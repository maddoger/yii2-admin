<?php

namespace maddoger\admin\controllers;

use maddoger\admin\models\Role;
use maddoger\core\BackendModule;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * RoleController implements the CRUD actions for Role model.
 *
 * @property \maddoger\admin\Module $module
 */
class RoleController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'update'],
                        'roles' => ['admin.rbac.manageRoles'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['update-from-modules'],
                        'allow' => true,
                        'roles' => ['admin.rbac.updateFromModules'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin.rbac.manageRoles'],
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
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $roles = Yii::$app->authManager->getRoles();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $roles,
            'sort' => [
                'attributes' => ['description', 'name'],
                'defaultOrder' => ['description' => SORT_ASC],
            ],
        ]);

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
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
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Role model.
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
     * Updates rbac items from modules
     * @param int $remove_all recreate all of items
     * @return mixed
     */
    public function actionUpdateFromModules($remove_all = 0)
    {
        $params = static::updateRoles($remove_all, $this->module->superUserId, $this->module->superUserRole);

        return $this->render('updateFromModules', $params);
    }

    public static function updateRoles($removeAll = false, $superUserId = null, $superUserRoleName = null)
    {
        $authManager = Yii::$app->authManager;
        $log = [];
        $hasError = false;

        if ($removeAll) {
            $authManager->removeAll();
        }

        //Superuser
        if ($superUserRoleName) {
            $superUserRole = $authManager->getRole($superUserRoleName);
            if (!$superUserRole) {
                $superUserRole = $authManager->createRole($superUserRoleName);
                $superUserRole->description = Yii::t('maddoger/admin', 'Superuser');
                if (!$authManager->add($superUserRole)) {
                    $log[] = [
                        'error',
                        Yii::t('maddoger/admin', '"{itemName}" ... error ({error})', [
                            'itemName' => $superUserRoleName,
                            'error' => 'creation failed'
                        ])
                    ];
                    $hasError = true;
                    $superUserRole = null;
                } else {
                    $log[] = [
                        'ok',
                        Yii::t('maddoger/admin', '"{itemName}" ... ok', [
                            'itemName' => $superUserRoleName,
                        ])
                    ];
                }
            }
        } else {
            $superUserRole = null;
        }

        if ($superUserRole && $superUserId) {
            $authManager->revoke($superUserRole, $superUserId);
            $authManager->assign($superUserRole, $superUserId);
        }

        foreach (Yii::$app->modules as $id => $module) {
            if (is_array($module)) {
                $module = Yii::$app->getModule($id);
            }
            if ($module instanceof BackendModule) {

                $items = $module->getRbacItems();
                foreach ($items as $item_name => $item_desc) {

                    if (!isset($item_desc['type'])) {
                        $log[] = [
                            'error',
                            Yii::t('maddoger/admin', '"{itemName}" ... error ({error})', [
                                'itemName' => $item_name,
                                'error' => 'type property is undefined'
                            ])
                        ];
                        $hasError = true;
                        continue;
                    }

                    $item = null;

                    if ($item_desc['type'] == Item::TYPE_PERMISSION) {
                        //Permission
                        $item = $authManager->getPermission($item_name);
                        if ($item) {
                            $item->description = isset($item_desc['description']) ? $item_desc['description'] : null;
                            if (!$authManager->update($item_name, $item)) {
                                $log[] = [
                                    'error',
                                    Yii::t('maddoger/admin', '"{itemName}" ... error ({error})', [
                                        'itemName' => $item_name,
                                        'error' => 'updating failed',
                                    ])
                                ];
                                $hasError = true;
                                continue;
                            }

                        } else {

                            $item = $authManager->createPermission($item_name);
                            $item->description = isset($item_desc['description']) ? $item_desc['description'] : null;

                            if (!$authManager->add($item)) {
                                $log[] = [
                                    'error',
                                    Yii::t('maddoger/admin', '"{itemName}" ... error ({error})', [
                                        'itemName' => $item_name,
                                        'error' => 'creation failed',
                                    ])
                                ];
                            }
                        }

                        $log[] = [
                            'ok',
                            Yii::t('maddoger/admin', '"{itemName}" ... ok', [
                                'itemName' => $item_name,
                            ])
                        ];

                    } elseif ($item_desc['type'] == Item::TYPE_ROLE) {
                        //Role
                        $item = $authManager->getRole($item_name);
                        if ($item) {
                            $item->description = isset($item_desc['description']) ? $item_desc['description'] : null;
                            if (!$authManager->update($item_name, $item)) {
                                $log[] = [
                                    'error',
                                    Yii::t('maddoger/admin', '"{itemName}" ... error ({error})', [
                                        'itemName' => $item_name,
                                        'error' => 'updating failed',
                                    ])
                                ];
                                $hasError = true;
                                continue;
                            }

                        } else {
                            $item = $authManager->createRole($item_name);
                            $item->description = isset($item_desc['description']) ? $item_desc['description'] : null;
                            if (!$authManager->add($item)) {
                                $log[] = [
                                    'error',
                                    Yii::t('maddoger/admin', '"{itemName}" ... error ({error})', [
                                        'itemName' => $item_name,
                                        'error' => 'creation failed',
                                    ])
                                ];
                                $hasError = true;
                                continue;
                            }
                        }

                        if (isset($item_desc['children'])) {
                            foreach ($item_desc['children'] as $child) {
                                $childItem = $authManager->getPermission($child);
                                if (!$childItem) {
                                    $childItem = $authManager->getRule($child);
                                }
                                if (!$childItem) {

                                    $log[] = [
                                        'error',
                                        Yii::t('maddoger/admin', '"{itemName}" ... error ({error})', [
                                            'itemName' => $item_name,
                                            'error' => 'child "' . $child . '" not found',
                                        ])
                                    ];
                                    $hasError = true;
                                    continue;
                                }

                                if (!$authManager->hasChild($item, $childItem)) {
                                    $authManager->addChild($item, $childItem);
                                }
                            }
                        }

                        $log[] = [
                            'ok',
                            Yii::t('maddoger/admin', '"{itemName}" ... ok', [
                                'itemName' => $item_name,
                            ])
                        ];

                        //Super user id
                        if ($item) {
                            //Only if no role
                            if (!$superUserRole && $superUserId) {
                                $authManager->revoke($item, $superUserId);
                                $authManager->assign($item, $superUserId);
                            }
                            if ($superUserRole && !$authManager->hasChild($superUserRole, $item)) {
                                $authManager->addChild($superUserRole, $item);
                            }
                        }
                    }
                }
            }
        }
        return ['log' => $log, 'hasError' => $hasError];
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findByName($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('maddoger/admin', 'The requested role does not exist.'));
        }
    }
}
