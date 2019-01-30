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

namespace yiiplus\desktop\components;

use yii\helpers\BaseHtml;
use yii\helpers\StringHelper;

/**
 * Html Class
 *
 * @author gengxiankun <gengxiankun@126.com>
 * @since 2.0.0
 */
class Html extends BaseHtml
{
    public static function icon($name)
    {
        $options = ['class' => 'fa'];
        if (!StringHelper::startsWith($name, 'fa-')) {
            $name = 'fa-' . $name;
        }
        self::addCssClass($options, $name);
        return self::tag('i', '', $options);
    }

    public static function staticControl($value, $options = [])
    {
        static::addCssClass($options, 'form-control-static');
        $value = (string) $value;
        if (isset($options['encode'])) {
            $encode = $options['encode'];
            unset($options['encode']);
        } else {
            $encode = true;
        }
        return static::tag('p', $encode ? static::encode($value) : $value, $options);
    }

    public static function activeStaticControl($model, $attribute, $options = [])
    {
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = static::getAttributeValue($model, $attribute);
        }
        return static::staticControl($value, $options);
    }

    public static function boolean($name, $checked = false, $options = [])
    {
        $options['data-toggle'] = 'switcher';
        return static::booleanInput('checkbox', $name, $checked, $options);
    }

    public static function activeBoolean($model, $attribute, $options = [])
    {
        $options['data-toggle'] = 'switcher';
        return static::activeBooleanInput('checkbox', $model, $attribute, $options);
    }

    /**
     * 标红字符串中含有的关键词
     * @param $q string 关键词
     * @param $str string 待过滤字符串
     * @return string 处理后的html
     */
    public static function weight($q, $str)
    {
        return preg_replace('/' . $q . '/i', Html::tag('span', '$0', ['style' => 'color:#f00']), $str);
    }
}