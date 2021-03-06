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
use yii\rbac\Rule;
use yiiplus\desktop\components\Configs;

/**
 * BizRule
 */
class BizRule extends \yii\base\Model
{
    /**
     * @var string name of the rule
     */
    public $name;

    /**
     * @var integer UNIX timestamp representing the rule creation time
     */
    public $createdAt;

    /**
     * @var integer UNIX timestamp representing the rule updating time
     */
    public $updatedAt;

    /**
     * @var string Rule classname.
     */
    public $className;

    /**
     * @var Rule
     */
    private $_item;

    /**
     * Initilaize object
     * 
     * @param \yii\rbac\Rule $item 类目
     * 
     * @param array $config
     */
    public function __construct($item, $config = [])
    {
        $this->_item = $item;
        if ($item !== null) {
            $this->name = $item->name;
            $this->className = get_class($item);
        }
        parent::__construct($config);
    }

    /**
     * Returns the validation rules for attributes.
     *
     * @return array validation rules
     */
    public function rules()
    {
        return [
            [['name', 'className'], 'required'],
            [['className'], 'string'],
            [['className'], 'classExists']
        ];
    }

    /**
     * Validate class exists
     * 
     * @return null
     */
    public function classExists()
    {
        if (!class_exists($this->className)) {
            $message = Yii::t('yiiplus/desktop', "未知的类 '{class}'", ['class' => $this->className]);
            $this->addError('className', $message);
            return;
        }
        if (!is_subclass_of($this->className, Rule::className())) {
            $message = Yii::t('yiiplus/desktop', "'{class}' 必须继承 'yii\rbac\Rule' 或者是它的子类", [
                    'class' => $this->className]);
            $this->addError('className', $message);
        }
    }

    /**
     * Returns the list of all attribute names of the model.
     *
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return [
            'name'      => Yii::t('yiiplus/desktop', '名称'),
            'className' => Yii::t('yiiplus/desktop', '类名'),
        ];
    }

    /**
     * Check if new record.
     * 
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_item === null;
    }

    /**
     * Find model by id
     * 
     * @param type $id ID
     * 
     * @return null|static
     */
    public static function find($id)
    {
        $item = Configs::authManager()->getRule($id);
        if ($item !== null) {
            return new static($item);
        }

        return null;
    }

    /**
     * Save model to authManager
     * 
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            $manager = Configs::authManager();
            $class = $this->className;
            if ($this->_item === null) {
                $this->_item = new $class();
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }
            $this->_item->name = $this->name;

            if ($isNew) {
                $manager->add($this->_item);
            } else {
                $manager->update($oldName, $this->_item);
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Get item
     * 
     * @return Item
     */
    public function getItem()
    {
        return $this->_item;
    }
}
