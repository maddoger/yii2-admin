<?php
namespace maddoger\admin\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;

/**
 * Login form
 */
class Role extends Model
{
    public $name;
    public $description;
    public $rule_name;
    public $data;
    public $isNewRecord = true;

    private $_childRoles;
    private $_childPermissions;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'description', 'rule_name'], 'string'],
            [['data', 'childRoles', 'childPermissions'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('maddoger/admin', 'Role identifier'),
            'description' => Yii::t('maddoger/admin', 'Description'),
            'rule_name' => Yii::t('maddoger/admin', 'Rule name'),
            'data' => Yii::t('maddoger/admin', 'Data'),
            'childRoles' => Yii::t('maddoger/admin', 'Child roles'),
            'childPermissions' => Yii::t('maddoger/admin', 'Child permissions'),
        ];
    }

    /**
     * Save item
     *
     * @return boolean whether the user is logged in successfully
     */
    public function save()
    {
        if ($this->validate()) {

            $authManager = Yii::$app->authManager;

            $item = $authManager->getRole($this->name);
            if ($item) {
                $update = true;
            } else {
                $item = $authManager->createRole($this->name);
                $update = false;
            }

            $item->description = $this->description;
            $item->ruleName = $this->rule_name;
            $item->data = $this->data;

            if ($update) {
                if (!$authManager->update($this->name, $item)) {
                    return false;
                }
            } else {
                if (!$authManager->add($item)) {
                    return false;
                }
            }

            $this->isNewRecord = false;

            $authManager->removeChildren($item);
            if ($this->_childRoles !== null) {
                foreach ($this->_childRoles as $childName) {
                    $child = $authManager->getRole($childName);
                    $authManager->addChild($item, $child);
                }
            }
            if ($this->_childPermissions !== null) {
                foreach ($this->_childPermissions as $childName) {
                    $child = $authManager->getPermission($childName);
                    $authManager->addChild($item, $child);
                }
            }

        } else {
            return false;
        }

        return true;
    }

    /**
     * Delete current item
     */
    public function delete()
    {
        if (!$this->isNewRecord) {
            $item = Yii::$app->authManager->getRole($this->name);
            if ($item) {
                Yii::$app->authManager->remove($item);
            }
        }
    }

    /**
     * @return null
     */
    public function getChildRoles()
    {
        if ($this->_childRoles === null) {
            $children = Yii::$app->authManager->getChildren($this->name);
            if ($children) {
                foreach ($children as $child) {
                    if ($child->type == Item::TYPE_ROLE) {
                        $this->_childRoles[] = $child->name;
                    }
                }
            }
        }
        return $this->_childRoles;
    }

    /**
     * @param $value
     */
    public function setChildRoles($value)
    {
        $this->_childRoles = $value;
    }

    /**
     * @return null
     */
    public function getChildPermissions()
    {
        if ($this->_childPermissions === null) {
            $children = Yii::$app->authManager->getChildren($this->name);
            if ($children) {
                foreach ($children as $child) {
                    if ($child->type == Item::TYPE_PERMISSION) {
                        $this->_childPermissions[] = $child->name;
                    }
                }
            }
        }
        return $this->_childPermissions;
    }

    /**
     * @param $value
     */
    public function setChildPermissions($value)
    {
        $this->_childPermissions = $value;
    }

    /**
     * @param $name
     * @return null
     */
    static public function findByName($name)
    {
        $role = Yii::$app->authManager->getRole($name);
        if (!$role) {
            return null;
        }
        $model = new Role([
            'name' => $role->name,
            'description' => $role->description,
            'rule_name' => $role->ruleName,
            'data' => $role->data,
            'isNewRecord' => false,
        ]);

        return $model ? : null;
    }

    /**
     * Returns roles list description by name as key
     * @param $except string name
     * @return array
     */
    static public function getRolesList($except=null)
    {
        $res = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
        if ($except !== null) {
            unset($res[$except]);
        }
        asort($res);
        return $res;
    }

    /**
     * Returns permissions list description by name as key
     * @param $except string name
     * @return array
     */
    static public function getPermissionsList($except=null)
    {
        $res = ArrayHelper::map(Yii::$app->authManager->getPermissions(), 'name', 'description');
        if ($except !== null) {
            unset($res[$except]);
        }
        ksort($res);
        return $res;
    }

}
