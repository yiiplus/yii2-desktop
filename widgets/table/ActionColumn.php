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

namespace yiiplus\desktop\widgets\table;

use yii\helpers\Html;
use \yii\grid\ActionColumn as YiiActionColumn;

/**
 * 操作栏
 *
 * @author liguangquan <liguangquan@163.com>
 * @since 2.0.0
 */
class ActionColumn extends YiiActionColumn
{
    public function renderDataCell($model, $key, $index)
    {
        if ($this->contentOptions instanceof Closure) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }

        return Html::tag('span', $this->renderDataCellContent($model, $key, $index), $options);
    }
}
