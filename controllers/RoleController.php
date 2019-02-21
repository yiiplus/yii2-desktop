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

namespace yiiplus\desktop\controllers;

use yiiplus\desktop\components\ItemController;
use yii\rbac\Item;

/**
 * RoleController implements the CRUD actions for AuthItem model.
 */
class RoleController extends ItemController
{
    /**
     * 标签
     * 
     * @return array|void
     */
    public function labels()
    {
        return[
            'Item' => '角色',
            'Items' => '角色列表',
        ];
    }

    /**
     * 类型
     * 
     * @return int
     */
    public function getType()
    {
        return Item::TYPE_ROLE;
    }
}
