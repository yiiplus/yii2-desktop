<?php

namespace yiiplus\desktop\controllers;

use yiiplus\desktop\components\ItemController;
use yii\rbac\Item;

/**
 * PermissionController implements the CRUD actions for AuthItem model.
 */
class PermissionController extends ItemController
{

    /**
     * @inheritdoc
     */
    public function labels()
    {
        return[
            'Item' => '权限',
            'Items' => '权限列表',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return Item::TYPE_PERMISSION;
    }
}
