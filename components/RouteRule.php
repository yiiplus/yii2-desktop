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

namespace yiiplus\desktop\components;

use Yii;
use yii\rbac\Rule;

/**
 * RouteRule Rule for check route with extra params.
 *
 * @author gengxiankun <gengxiankun@126.com>
 * @since 2.0.0
 */
class RouteRule extends Rule
{
    const RULE_NAME = 'route_rule';

    /**
     * @inheritdoc
     */
    public $name = self::RULE_NAME;

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $routeParams = isset($item->data['params']) ? $item->data['params'] : [];
        foreach ($routeParams as $key => $value) {
            if (!array_key_exists($key, $params) || $params[$key] != $value) {
                return false;
            }
        }
        return true;
    }
}
