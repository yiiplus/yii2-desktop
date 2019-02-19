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

namespace yiiplus\desktop\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yiiplus\desktop\components\Configs;
use yii\rbac\Item;

/**
 * AuthItemSearch represents the model behind the search form about AuthItem.
 */
class AuthItem extends Model
{
    /**
     * 路由类型
     */
    const TYPE_ROUTE = 101;

    /**
     * 名称
     */
    public $name;

    /**
     * 类型
     */
    public $type;

    /**
     * 描述
     */
    public $description;

    /**
     * 规则名称
     */
    public $ruleName;

    /**
     * 数据
     */
    public $data;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array validation rules
     */
    public function rules()
    {
        return [
            [['name', 'ruleName', 'description'], 'safe'],
            [['type'], 'integer'],
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
            'item_name' => Yii::t('yiiplus/desktop', '项目名称'),
            'type' => Yii::t('yiiplus/desktop', '类型'),
            'description' => Yii::t('yiiplus/desktop', '描述'),
            'ruleName' => Yii::t('yiiplus/desktop', '规则名称'),
            'data' => Yii::t('yiiplus/desktop', '数据'),
        ];
    }

    /**
     * Search authitem
     * 
     * @param array $params 搜索条件
     * 
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        /* @var \yii\rbac\Manager $authManager */
        $authManager = Configs::authManager();
        if ($this->type == Item::TYPE_ROLE) {
            $items = $authManager->getRoles();
        } else {
            $items = array_filter($authManager->getPermissions(), function($item) {
                return $this->type == Item::TYPE_PERMISSION xor strncmp($item->name, '/', 1) === 0;
            });
        }
        $this->load($params);
        if ($this->validate()) {

            $search = mb_strtolower(trim($this->name));
            $desc = mb_strtolower(trim($this->description));
            $ruleName = $this->ruleName;
            foreach ($items as $name => $item) {
                $f = (empty($search) || mb_strpos(mb_strtolower($item->name), $search) !== false) &&
                    (empty($desc) || mb_strpos(mb_strtolower($item->description), $desc) !== false) &&
                    (empty($ruleName) || $item->ruleName == $ruleName);
                if (!$f) {
                    unset($items[$name]);
                }
            }
        }

        return new ArrayDataProvider([
            'allModels' => $items,
        ]);
    }
}
