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
use yii\db\Query;

use yiiplus\desktop\components\Configs;
use yiiplus\desktop\behaviors\PositionBehavior;
/**
 * 菜单model
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * 父类
     */
    public $parent_name;

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return Configs::instance()->menuTable;
    }

    /**
     * 获取db
     *
     * @return object
     */
    public static function getDb()
    {
        if (Configs::instance()->db !== null) {
            return Configs::instance()->db;
        } else {
            return parent::getDb();
        }
    }

    /**
     * behaviors
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'order',
                'groupAttributes' => [
                    'parent' // 菜单父类字段名
                ],
            ],
        ];
    }

    /**
     * 规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent', 'route', 'data', 'order'], 'default'],
            [['parent'], 'filterParent', 'when' => function() {
                return !$this->isNewRecord;
            }],
            [['icon'], 'string'],
            [['order'], 'integer'],
            [['route'], 'in',
                'range' => static::getSavedRoutes(),
                'message' => Yii::t('yiiplus/desktop', '路由 "{value}" 没有找到')]
        ];
    }

    /**
     * Use to loop detected.
     */
    public function filterParent()
    {
        $parent = $this->parent;
        $db = static::getDb();
        $query = (new Query)->select(['parent'])
            ->from(static::tableName())
            ->where('[[id]]=:id');
        while ($parent) {
            if ($this->id == $parent) {
                $this->addError('parent_name', Yii::t('yiiplus/desktop', '检测到循环'));
                return;
            }
            $parent = $query->params([':id' => $parent])->scalar($db);
        }
    }

    /**
     * 别名
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yiiplus/desktop', 'ID'),
            'name' => Yii::t('yiiplus/desktop', '名称'),
            'parent' => Yii::t('yiiplus/desktop', '父级'),
            'parent_name' => Yii::t('yiiplus/desktop', '父级名称'),
            'route' => Yii::t('yiiplus/desktop', '路由'),
            'order' => Yii::t('yiiplus/desktop', '排序'),
            'data' => Yii::t('yiiplus/desktop', '数据'),
            'icon' => Yii::t('yiiplus/desktop', '图标'),
        ];
    }

    /**
     * 获取parent
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuParent()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent']);
    }

    /**
     * 获取子类
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['parent' => 'id']);
    }
    private static $_routes;

    /**
     * 获取路由
     *
     * @return   array
     */
    public static function getSavedRoutes()
    {
        if (self::$_routes === null) {
            self::$_routes = [];
            foreach (Configs::authManager()->getPermissions() as $name => $value) {
                if ($name[0] === '/' && substr($name, -1) != '*') {
                    self::$_routes[] = $name;
                }
            }
        }
        return self::$_routes;
    }

    /**
     * getMenuSource
     *
     * @return \yii\db\ActiveQuery
     */
    public static function getMenuSource()
    {
        $tableName = static::tableName();
        return (new \yii\db\Query())
            ->select(['m.id', 'm.name', 'm.route', 'parent_name' => 'p.name'])
            ->from(['m' => $tableName])
            ->leftJoin(['p' => $tableName], '[[m.parent]]=[[p.id]]')
            ->all(static::getDb());
    }

    /**
     * 获取菜单下拉列表
     *
     * @param array   $tree      菜单数组
     * @param array   &$result   返回数组
     * @param integer $deep      循环值
     * @param string  $separator 空格
     *
     * @return array
     */
    public static function getDropDownList($tree = [], &$result = [], $deep = 0, $separator = '&nbsp;&nbsp;&nbsp;&nbsp;')
    {
        $deep++;
        foreach($tree as $list) {
            $result[$list['id']] = str_repeat($separator, $deep - 1) . $list['name'];
            if (isset($list['children'])) {
                self::getDropDownList($list['children'], $result, $deep);
            }
        }
        return $result;
    }
}
