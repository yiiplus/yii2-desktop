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

namespace yiiplus\desktop\modules\migrations\models;

use yii\helpers\Html;

/**
 * 动态域
 *
 * @author zhangxu <2217418603@qq.com>
 * @since 2.0.0
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * 资源控制
     *
     * @param array $options
     * @return $this
     */
    public function staticControl($options = [])
    {
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeStaticControl($this->model, $this->attribute, $options);
        return $this;
    }

    /**
     * 后缀
     *
     * @param string $suffix
     * @param string $suffixType
     * @param int $size
     * @return $this
     */
    public function suffix($suffix = '', $suffixType = 'addon', $size = 300)
    {
        $size = !empty($size) ? "input-group-{$size} " : '';
        $this->template = "{label}\n<div class=\"input-group $size\">{input}\n<div class=\"input-group-" . $suffixType . "\">" . $suffix . "</div></div>\n{hint}\n{error}";
        return $this;
    }

    /**
     * 前缀
     *
     * @param string $prefix
     * @param string $prefixType
     * @param int $size
     * @return $this
     */
    public function prefix($prefix = '', $prefixType = 'addon', $size = 300)
    {
        $size = !empty($size) ? "input-group-{$size} " : '';
        $this->template = "{label}\n<div class=\"input-group $size\"><div class=\"input-group-" . $prefixType . "\">" . $prefix . "</div>\n{input}</div>\n{hint}\n{error}";
        return $this;
    }

    /**
     * 真假
     *
     * @param array $options
     * @param bool $enclosedByLabel
     * @return $this
     */
    public function boolean($options = [], $enclosedByLabel = true)
    {
        if ($enclosedByLabel) {
            $this->parts['{input}'] = Html::activeBoolean($this->model, $this->attribute, $options);
            $this->parts['{label}'] = '';
        } else {
            if (isset($options['label']) && !isset($this->parts['{label}'])) {
                $this->parts['{label}'] = $options['label'];
                if (!empty($options['labelOptions'])) {
                    $this->labelOptions = $options['labelOptions'];
                }
            }
            unset($options['labelOptions']);
            $options['label'] = null;
            $this->parts['{input}'] = Html::activeBoolean($this->model, $this->attribute, $options);
        }
        $this->adjustLabelFor($options);

        return $this;
    }
}