<?php
/**
 * yiiplus\desktop
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\desktop\behaviors;

use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;

/**
 * 作用菜单移动场景
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class PositionBehavior extends Behavior
{
    /**
     * 数据库顺序字段order默认position
     *
     * @var string
     */
    public $positionAttribute = 'position';

    /**
     * 所属分类parent
     *
     * @var array
     */
    public $groupAttributes = [];

    /**
     * 默认保存的positionAttribute值
     * @var mixed
     */
    private $positionOnSave;


    /**
     * 将一个菜单像上移动一个
     *
     * @return boolean
     */
    public function movePrev()
    {
        $positionAttribute = $this->positionAttribute;
        /* @var $previousRecord BaseActiveRecord */
        $previousRecord = $this->owner->find()
            ->andWhere($this->createGroupConditionAttributes())
            ->andWhere([$positionAttribute => ($this->owner->$positionAttribute - 1)])
            ->one();
        if (empty($previousRecord)) {
            return false;
        }

        $previousRecord->updateAttributes([
            $positionAttribute => $this->owner->$positionAttribute
        ]);

        $this->owner->updateAttributes([
            $positionAttribute => $this->owner->$positionAttribute - 1
        ]);

        return true;
    }

    /**
     * 将一个菜单像下移动一个
     *
     * @return boolean
     */
    public function moveNext()
    {
        $positionAttribute = $this->positionAttribute;

        /* @var $nextRecord BaseActiveRecord */
        $nextRecord = $this->owner->find()
            ->andWhere($this->createGroupConditionAttributes())
            ->andWhere([$positionAttribute => ($this->owner->$positionAttribute + 1)])
            ->one();

        if (empty($nextRecord)) {
            return false;
        }

        $nextRecord->updateAttributes([
            $positionAttribute => $this->owner->$positionAttribute
        ]);

        $this->owner->updateAttributes([
            $positionAttribute => $this->owner->getAttribute($positionAttribute) + 1
        ]);

        return true;
    }

    /**
     * 移动到列表顶端
     *
     * @return boolean
     */
    public function moveFirst()
    {
        $positionAttribute = $this->positionAttribute;
        if ($this->owner->$positionAttribute == 1) {
            return false;
        }

        $this->owner->updateAllCounters(
            [
                $positionAttribute => +1
            ],
            [
                'and',
                $this->createGroupConditionAttributes(),
                ['<', $positionAttribute, $this->owner->$positionAttribute]
            ]
        );

        $this->owner->updateAttributes([
            $positionAttribute => 1
        ]);

        return true;
    }

    /**
     * 移动到列表底端
     *
     * @return boolean
     */
    public function moveLast()
    {
        $positionAttribute = $this->positionAttribute;

        $recordsCount = $this->countGroupRecords();
        if ($this->owner->getAttribute($positionAttribute) == $recordsCount) {
            return false;
        }

        $this->owner->updateAllCounters(
            [
                $positionAttribute => -1
            ],
            [
                'and',
                $this->createGroupConditionAttributes(),
                ['>', $positionAttribute, $this->owner->$positionAttribute]
            ]
        );

        $this->owner->updateAttributes([
            $positionAttribute => $recordsCount
        ]);

        return true;
    }

    /**
     * 将所有者记录移动到特定位置。
     * 如果指定位置超过记录总数，
     * 所有者将被移动到列表的末尾
     *
     * @param integer $position order值
     *
     * @return boolean
     */
    public function moveToPosition($position)
    {
        //整数大于1
        if (!is_numeric($position) || $position < 1) {
            return false;
        }
        $positionAttribute = $this->positionAttribute;

        $oldRecord = $this->owner->findOne($this->owner->getPrimaryKey());

        $oldRecordPosition = $oldRecord->$positionAttribute;

        if ($oldRecordPosition == $position) {
            return true;
        }

        if ($position < $oldRecordPosition) {
            // Move Up:
            $this->owner->updateAllCounters(
                [
                    $positionAttribute => +1
                ],
                [
                    'and',
                    $this->createGroupConditionAttributes(),
                    ['>=', $positionAttribute, $position],
                    ['<', $positionAttribute, $oldRecord->$positionAttribute],
                ]
            );

            $this->owner->updateAttributes([
                $positionAttribute => $position
            ]);

            return true;
        } else {
            // Move Down:
            $recordsCount = $this->countGroupRecords();
            if ($position >= $recordsCount) {
                return $this->moveLast();
            }

            $this->owner->updateAllCounters(
                [
                    $positionAttribute => -1
                ],
                [
                    'and',
                    $this->createGroupConditionAttributes(),
                    ['>', $positionAttribute, $oldRecord->$positionAttribute],
                    ['<=', $positionAttribute, $position],
                ]
            );

            $this->owner->updateAttributes([
                $positionAttribute => $position
            ]);

            return true;
        }
    }

    /**
     * 组装条件数组 ['parent' => 0]
     *
     * @see groupAttributes
     *
     * @return array
     */
    protected function createGroupConditionAttributes()
    {
        $condition = [];
        if (!empty($this->groupAttributes)) {
            foreach ($this->groupAttributes as $attribute) {
                $condition[$attribute] = $this->owner->$attribute;
            }
        }
        return $condition;
    }

    /**
     * 查询当前分类的记录数
     *
     * @see groupAttributes parent
     *
     * @return integer records 记录数.
     */
    protected function countGroupRecords()
    {
        $query = $this->owner->find();
        if (!empty($this->groupAttributes)) {
            $query->andWhere($this->createGroupConditionAttributes());
        }
        return $query->count();
    }

    /**
     * 事件列表
     *
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * 插入前置操作
     *
     * @param ModelEvent
     */
    public function beforeInsert($event)
    {
        $positionAttribute = $this->positionAttribute;
        if ($this->owner->$positionAttribute > 0) {
            $this->positionOnSave = $this->owner->$positionAttribute;
        }
        $this->owner->$positionAttribute = $this->countGroupRecords() + 1;
    }

    /**
     * 更新前置操作
     *
     * @param ModelEvent
     */
    public function beforeUpdate($event)
    {
        $positionAttribute = $this->positionAttribute;

        $isNewGroup = false;
        foreach ($this->groupAttributes as $groupAttribute) {
            if ($this->owner->isAttributeChanged($groupAttribute, false)) {
                $isNewGroup = true;
                break;
            }
        }

        if ($isNewGroup) {
            $oldRecord = $this->owner->findOne($this->owner->getPrimaryKey());
            $oldRecord->moveLast();
            $this->positionOnSave = $this->owner->$positionAttribute;
            $this->owner->$positionAttribute = $this->countGroupRecords() + 1;
        } else {
            if ($this->owner->isAttributeChanged($positionAttribute, false)) {
                $this->positionOnSave = $this->owner->$positionAttribute;
                $this->owner->$positionAttribute = $this->owner->getOldAttribute($positionAttribute);
            }
        }
    }

    /**
     * 插入更新后操作
     *
     * @param ModelEvent
     */
    public function afterSave($event)
    {
        if ($this->positionOnSave !== null) {
            $this->moveToPosition($this->positionOnSave);
        }
        $this->positionOnSave = null;
    }

    /**
     * 删除后操作
     *
     * @param ModelEvent
     */
    public function beforeDelete($event)
    {
        $this->moveLast();
    }
}