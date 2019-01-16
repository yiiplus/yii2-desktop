<?php

namespace yiiplus\desktop\widgets\table;

use yii\helpers\Html;
use \yii\grid\ActionColumn as YiiActionColumn;
/**
 * 操作栏
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
