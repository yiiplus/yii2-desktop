<?php
/**
 * yiiplus/yii2-desktop
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\desktop\models;

use Yii;
use yii\debug\panels\EventPanel;
use yiiplus\desktop\components\Configs;
use yiiplus\desktop\components\Helper;
use yii\base\Model;
use yii\helpers\Json;
use yii\rbac\Item;

/**
 * This is the model class for table "tbl_auth_item".
 */
class AuthItem extends Model
{
    public $name;
    public $type;
    public $description;
    public $ruleName;
    public $data;
    public $availableItem;
    public $assignedItem;
    /**
     * @var Item
     */
    private $_item;

    /**
     * Initialize object
     * @param Item $item
     * @param array $config
     */
    public function __construct($item = null, $config = [])
    {
        $this->_item = $item;
        if ($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->data = $item->data === null ? null : Json::encode($item->data);
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ruleName'], 'checkRule'],
            [['name', 'type'], 'required'],
            [['name'], 'checkUnique', 'when' => function () {
                return $this->isNewRecord || ($this->_item->name != $this->name);
            }],
            ['name', 'match', 'not' => 'true', 'pattern' => '/^\/.*$/', 'message' => '不以/开头'],
            [['type'], 'integer'],
            [['description', 'data', 'ruleName'], 'default'],
            [['name'], 'string', 'max' => 64],
            [['availableItem', 'assignedItem'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('yiiplus/desktop', '名称'),
            'type' => Yii::t('yiiplus/desktop', '类型'),
            'description' => Yii::t('yiiplus/desktop', '描述'),
            'ruleName' => Yii::t('yiiplus/desktop', '规则名称'),
            'data' => Yii::t('yiiplus/desktop', '数据'),
            'created_at' => Yii::t('yiiplus/desktop', '创建时间'),
            'updated_at' => Yii::t('yiiplus/desktop', '更新时间'),
            'role' => Yii::t('yiiplus/desktop', '角色'),
            'route' => Yii::t('yiiplus/desktop', '路由'),
            'permission' => Yii::t('yiiplus/desktop', '权限'),
        ];
    }

    /**
     * Check role is unique
     */
    public function checkUnique()
    {
        $authManager = Configs::authManager();
        $value = $this->name;
        if ($authManager->getRole($value) !== null || $authManager->getPermission($value) !== null) {
            $message = Yii::t('yii', '{attribute} "{value}" 已经被占用');
            $params = [
                'attribute' => $this->getAttributeLabel('name'),
                'value' => $value,
            ];
            $this->addError('name', Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
        }
    }

    /**
     * Check for rule
     */
    public function checkRule()
    {
        $name = $this->ruleName;
        if (!Configs::authManager()->getRule($name)) {
            try {
                $rule = Yii::createObject($name);
                if ($rule instanceof \yii\rbac\Rule) {
                    $rule->name = $name;
                    Configs::authManager()->add($rule);
                } else {
                    $this->addError('ruleName', Yii::t('yiiplus/desktop', '无效的规则 "{value}"', ['value' => $name]));
                }
            } catch (\Exception $exc) {
                $this->addError('ruleName', Yii::t('yiiplus/desktop', '"{value}" 规则不存在', ['value' => $name]));
            }
        }
    }

    /**
     * Check if is new record.
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_item === null;
    }

    /**
     * Find role
     * @param string $id
     * @return null|\self
     */
    public static function find($id)
    {
        $item = Configs::authManager()->getRole($id);
        if ($item !== null) {
            return new self($item);
        }

        return null;
    }

    /**
     * Save role to [[\yii\rbac\authManager]]
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            $manager = Configs::authManager();
            if ($this->_item === null) {
                if ($this->type == Item::TYPE_ROLE) {
                    $this->_item = $manager->createRole($this->name);
                } else {
                    $this->_item = $manager->createPermission($this->name);
                }
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }
            $this->_item->name = $this->name;
            $this->_item->description = $this->description;
            $this->_item->ruleName = $this->ruleName;
            $this->_item->data = $this->data === null || $this->data === '' ? null : Json::decode($this->data);
            if ($isNew) {
                $manager->add($this->_item);
            } else {
                $manager->update($oldName, $this->_item);
            }
            Helper::invalidate();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adds an item as a child of another item.
     * @param array $items
     * @return int
     */
    public function addChildren($items)
    {
        $manager = Configs::authManager();
        $success = 0;
        if ($this->_item) {
            foreach ($items as $name) {
                $child = $manager->getPermission($name);
                if ($this->type == Item::TYPE_ROLE && $child === null) {
                    $child = $manager->getRole($name);
                }
                try {
                    $manager->addChild($this->_item, $child);
                    $success++;
                } catch (\Exception $exc) {
                    Yii::error($exc->getMessage(), __METHOD__);
                }
            }
        }
        if ($success > 0) {
            Helper::invalidate();
        }
        return $success;
    }

    /**
     * Remove an item as a child of another item.
     * @param array $items
     * @return int
     */
    public function removeChildren($items)
    {
        $manager = Configs::authManager();
        $success = 0;
        if ($this->_item !== null) {
            foreach ($items as $name) {
                $child = $manager->getPermission($name);
                if ($this->type == Item::TYPE_ROLE && $child === null) {
                    $child = $manager->getRole($name);
                }
                try {
                    $manager->removeChild($this->_item, $child);
                    $success++;
                } catch (\Exception $exc) {
                    Yii::error($exc->getMessage(), __METHOD__);
                }
            }
        }
        if ($success > 0) {
            Helper::invalidate();
        }
        return $success;
    }

    /**
     * Get items
     * @return array
     */
    public function getItems()
    {
        $manager = Configs::authManager();
        $available = [];
        if ($this->type == Item::TYPE_ROLE) {
            foreach (array_keys($manager->getRoles()) as $name) {
                $available[$name] = 'role';
            }
        }
        foreach (array_keys($manager->getPermissions()) as $name) {
            $available[$name] = $name[0] == '/' ? 'route' : 'permission';
        }

        $assigned = [];
        if (isset($this->_item->name)) {
            foreach ($manager->getChildren($this->_item->name) as $item) {
                $assigned[$item->name] = $item->type == 1 ? 'role' : ($item->name[0] == '/' ? 'route' : 'permission');
                unset($available[$item->name]);
            }
            unset($available[$this->name]);
        }

        return [
            'available' => $available,
            'assigned' => $assigned,
        ];
    }

    /**
     * Get item
     * @return Item
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * Get type name
     * @param  mixed $type
     * @return string|array
     */
    public static function getTypeName($type = null)
    {
        $result = [
            Item::TYPE_PERMISSION => 'Permission',
            Item::TYPE_ROLE => 'Role',
        ];
        if ($type === null) {
            return $result;
        }

        return $result[$type];
    }

    /**
     * 获取所有的角色权限路由
     *
     * @return array
     */
    public static function getAllItems()
    {
        $manager = Configs::authManager();
        $roles = [];
        foreach (array_keys($manager->getRoles()) as $name) {
            $roles[$name] = $name;
        }

        $permissions = [];
        $route = [];
        foreach (array_keys($manager->getPermissions()) as $name) {
            if ($name[0] != '/') {
                $permissions[$name] = $name;
            } else {
                $route[$name] = $name;
            }
        }

        return [
            'roles' => $roles,
            'route' => $route,
            'permissions' => $permissions,
        ];
    }

    /**
     * 根据用户查询角色权限
     *
     * @param int $id 用户ID
     *
     * @return array
     */
    public static function getItemByUser($id)
    {
        $manager = Configs::authManager();
        $role = [];
        $role = array_keys($manager->getRolesByUser($id));
        $permission = [];
        $route = [];
        foreach (array_keys($manager->getPermissionsByUser($id)) as $name) {
            if ($name[0] != '/') {
                $permission[] = $name;
            } else {
                $route[] = $name;
            }
        }
        return [
            'role' => $role,
            'permission' => $permission,
            'route' => $route,
        ];
    }
}
