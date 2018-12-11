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

namespace yiiplus\desktop\widgets\grid;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * 组装菜单移动标签
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class PositionColumn extends DataColumn
{
    /**
     * 将用于在每个单元格中呈现内容的模板串起来。
     */
    public $template = '{first}&nbsp;{prev}&nbsp;{value}&nbsp;{next}&nbsp;{last}';

    /**
     * 小图标数组默认值
     */
    public $buttons = [];

    /**
     * 小图标默认class
     */
    public $buttonOptions = ["class"=>"btn btn-default btn-xs"];

    /**
     * 动作的字符串路径 例如："项目/位置"
     */
    public $route = 'position';

    /**
     * 获取params字符串名称
     */
    public $positionParam = 'at';

    /**
     * 回调的默认值
     */
    public $urlCreator;

    /**
     * 默认执行 init
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    /**
     * 初始化默认按钮
     *
     * @return bool
     */
    protected function initDefaultButtons()
    {
        $this->buttons = ArrayHelper::merge(
            [
                // 移动到顶端
                'first' => [
                    'icon' => 'triangle-top',
                    'visible' => function ($model) {
                        /* @var $model \yii\db\BaseActiveRecord */
                        if ($this->attribute !== null && isset($model[$this->attribute])) {
                            return $model[$this->attribute] > 0;
                        }
                        return true;
                    },
                    'options' => [
                        'title' => 'Move top',
                        'aria-label' => 'Move top',
                        'data-method' => 'post'
                    ],
                ],
                // 移动到末尾
                'last' => [
                    'icon' => 'triangle-bottom',
                    'visible' => function ($model) {
                        /* @var $model \yii\db\BaseActiveRecord */
                        if ($this->attribute !== null && isset($model[$this->attribute])) {
                            return $model[$this->attribute] < $this->grid->dataProvider->getTotalCount();
                        }
                        return true;
                    },
                    'options' => [
                        'title' => 'Move bottom',
                        'aria-label' => 'Move bottom',
                        'data-method' => 'post'
                    ],
                ],
                // 移动到上一个
                'prev' => [
                    'icon' => 'arrow-up',
                    'visible' => function ($model) {
                        /* @var $model \yii\db\BaseActiveRecord */
                        if ($this->attribute !== null && isset($model[$this->attribute])) {
                            return $model[$this->attribute] > 0;
                        }
                        return true;
                    },
                    'options' => [
                        'title' => 'Move up',
                        'aria-label' => 'Move up',
                        'data-method' => 'post'
                    ],
                ],
                // 移动到下一个
                'next' => [
                    'icon' => 'arrow-down',
                    'visible' => function ($model) {
                        /* @var $model \yii\db\BaseActiveRecord */
                        if ($this->attribute !== null && isset($model[$this->attribute])) {
                            return $model[$this->attribute] < $this->grid->dataProvider->getTotalCount();
                        }
                        return true;
                    },
                    'options' => [
                        'title' => 'Move down',
                        'aria-label' => 'Move down',
                        'data-method' => 'post'
                    ],
                ],
            ],
            $this->buttons
        );
    }

    /**
     * 生成模版内容
     *
     * @param object $model
     * @param string $key
     * @param string $index
     *
     * @return render
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
                $name = $matches[1];
                if ($name === 'value') {
                    return $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
                }
                return $this->renderButton($name, $model, $key, $index);
            }, $this->template);
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }
    }

    /**
     * 组装button.
     *
     * @param string  $name button name.
     * @param mixed   $model
     * @param string  $key
     * @param integer $index
     *
     * @return string rendered HTML
     *
     * @throws InvalidConfigException on invalid button format.
     */
    protected function renderButton($name, $model, $key, $index)
    {
        if (!isset($this->buttons[$name])) {
            return '';
        }
        $button = $this->buttons[$name];

        if ($button instanceof \Closure) {
            $url = $this->createUrl($name, $model, $key, $index);
            return call_user_func($button, $url, $model, $key);
        }
        if (!is_array($button)) {
            throw new InvalidConfigException("Button should be either a Closure or array configuration.");
        }

        // Visibility :
        if (isset($button['visible'])) {
            if ($button['visible'] instanceof \Closure) {
                if (!call_user_func($button['visible'], $model, $key, $index)) {
                    return '';
                }
            } elseif (!$button['visible']) {
                return '';
            }
        }

        // URL :
        if (isset($button['url'])) {
            $url = call_user_func($button['url'], $name, $model, $key, $index);
        } else {
            $url = $this->createUrl($name, $model, $key, $index);
        }

        // label :
        if (isset($button['label'])) {
            $label = $button['label'];

            if (isset($button['encode'])) {
                $encodeLabel = $button['encode'];
                unset($button['encode']);
            } else {
                $encodeLabel = true;
            }
            if ($encodeLabel) {
                $label = Html::encode($label);
            }
        } else {
            $label = '';
        }

        // icon :
        if (isset($button['icon'])) {
            $icon = $button['icon'];
            $label = Html::icon($icon) . (empty($label) ? '' : ' ' . $label);
        }

        $options = array_merge(ArrayHelper::getValue($button, 'options', []), $this->buttonOptions);

        return Html::a($label, $url, $options);
    }

    /**
     * 为给定的位置和模型创建URL。
     * 为每个按钮和每一行调用此方法。
     *
     * @param string                   $position the position name
     * @param \yii\db\BaseActiveRecord $model    the data model
     * @param mixed                    $key      the key associated with the data model
     * @param integer                  $index    the current row index
     *
     * @return string the created URL
     */
    public function createUrl($position, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $position, $model, $key, $index);
        } else {
            $params = array_merge(
                Yii::$app->getRequest()->getQueryParams(),
                is_array($key) ? $key : ['id' => (string) $key]
            );
            $params[$this->positionParam] = $position;
            $params[0] = $this->route;

            return Url::toRoute($params);
        }
    }
}
