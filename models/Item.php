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
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yiiplus\desktop\components\Configs;
use yii\rbac\Item as It;

/**
 * This is the model class for table "auth_item".
 */
class Item extends ActiveRecord
{
    /**
     * 角色
     */
    public $role;

    /**
     * 路由
     */
    public $route;

    /**
     * 权限
     */
    public $permission;

    /**
     * 角色类型
     */
    const TYPE_ROLE = 'role';

    /**
     * 权限类型
     */
    const TYPE_PERMISSION = 'permission';

    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string the table name
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * Returns the validation rules for attributes.
     *
     * @return array validation rules
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            ['name', 'match', 'not' => 'true', 'pattern' => '/^\/.*$/', 'message' => '不以/开头'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
            [['role', 'route', 'permission'], 'safe'],
        ];
    }

    /**
     * Returns the list of all attribute names of the model.
     *
     * @return array list of attribute names.
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
     * 获取子类
     * 
     * @param int $id item-id
     * 
     * @return array
     */
    public static function getChild($id)
    {
        $manager = Configs::authManager();
        $roles = [];
        foreach (array_keys($manager->getChildRoles($id)) as $name) {
            $roles[$name] = $name;
        }

        $permissions = [];
        $route = [];
        foreach (array_keys($manager->getPermissionsByRole($id)) as $name) {
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
     * 搜索
     * 
     * @param array $params 搜索条件
     * 
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $query = self::find();
        $query->joinWith('authItemChildren');
        $query->groupBy('name');
        $attributes = array_keys($this->attributes);
        Yii::$app->controller->id == self::TYPE_ROLE ? $query->where(['type' => It::TYPE_ROLE]) : $query->where(['type' => It::TYPE_PERMISSION]);
        if (Yii::$app->controller->id == 'permission') {
            $query->andFilterWhere(['not like', 'name', '/%', false]);
        }
        if (Yii::$app->request->get('filter')) { // 高级搜索
            $filter = json_decode(Yii::$app->request->get('filter'), true);
            foreach ($attributes as $attribute) {
                if (isset($filter[$attribute])) {
                    $query->andFilterWhere(['like', $attribute, $filter[$attribute]]);
                }
            }
        } elseif (Yii::$app->request->get('search')) { // 快速搜索
            $filter = Yii::$app->request->get('search');
            $query->andFilterWhere(['like', 'name', $filter]);
        }

        return $query;
    }

    /**
     * 关联授权项目
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * 关联规则
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * 关联子类项目
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * 关联子类项目
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * 关联子类项目
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Item::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * 关联父类项目
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(Item::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }
}
