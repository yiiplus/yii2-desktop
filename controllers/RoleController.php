<?php

namespace yiiplus\desktop\controllers;

use yiiplus\desktop\components\ItemController;
use yii\rbac\Item;

/**
 * RoleController implements the CRUD actions for AuthItem model.
 */
class RoleController extends ItemController
{
    /**
     * @inheritdoc
     */
    public function labels()
    {
        return[
            'Item' => '角色',
            'Items' => '角色列表',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return Item::TYPE_ROLE;
    }
}
