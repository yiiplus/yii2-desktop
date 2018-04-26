<?php

namespace yiiplus\desktop\components;

use yii\rbac\Rule;

/**
 * Description of GuestRule
 */
class GuestRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'guest_rule';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return $user->getIsGuest();
    }
}
