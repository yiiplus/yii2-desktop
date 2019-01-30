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

namespace yiiplus\desktop\widgets\tree;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * 移动标签
 *
 * @author liguangquan <liguangquan@163.com>
 * @since 2.0.0
 */
class PositionColumn extends DataColumn
{
    /**
     * 将用于在每个单元格中呈现内容的模板串起来。
     *
     * @var string
     */
    public $template = '{first}&nbsp;{prev}&nbsp;{value}&nbsp;{next}&nbsp;{last}';

    /**
     * 小图标数组默认值
     *
     * @var array
     */
    public $buttons = [];

    /**
     * 小图标默认class
     *
     * @var array
     */
    public $buttonOptions = ["class"=>"btn btn-default btn-xs"];

    /**
     * 动作的字符串路径 例如："项目/位置"
     *
     * @var string
     */
    public $route = 'position';

    /**
     * 获取params字符串名称
     *
     * @var string
     */
    public $positionParam = 'at';

    /**
     * 回调的默认值
     *
     * @var string
     */
    public $urlCreator;

    /**
     * 组装当前分类查询条件
     *
     * @var array
     */
    public $groupAttributes = [];

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
                    'options' => [
                        'title' => Yii::t('yiiplus/desktop','置顶'),
                        'aria-label' => Yii::t('yiiplus/desktop','置顶'),
                        'data-method' => 'post'
                    ],
                ],
                // 移动到末尾
                'last' => [
                    'icon' => 'triangle-bottom',
                    'options' => [
                        'title' => Yii::t('yiiplus/desktop','置底'),
                        'aria-label' => Yii::t('yiiplus/desktop','置底'),
                        'data-method' => 'post'
                    ],
                ],
                // 移动到上一个
                'prev' => [
                    'icon' => 'arrow-up',
                    'options' => [
                        'title' => Yii::t('yiiplus/desktop','上移'),
                        'aria-label' => Yii::t('yiiplus/desktop','上移'),
                        'data-method' => 'post'
                    ],
                ],
                // 移动到下一个
                'next' => [
                    'icon' => 'arrow-down',
                    'options' => [
                        'title' => Yii::t('yiiplus/desktop','下移'),
                        'aria-label' => Yii::t('yiiplus/desktop','下移'),
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
     * @param object $model model数据
     * @param string $key   主键id
     * @param string $index 数组键值
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
     * @param string  $name   按钮名称
     * @param mixed   $model  model数据
     * @param string  $key    主键id
     * @param integer $index  数组键值
     *
     * @return HTML
     *
     * @throws InvalidConfigException
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
            throw new InvalidConfigException(Yii::t('yiiplus/desktop', "Button必须是一个闭包或者数组配置"));
        }

        // 路由 :
        if (isset($button['url'])) {
            $url = call_user_func($button['url'], $name, $model, $key, $index);
        } else {
            $url = $this->createUrl($name, $model, $key, $index);
        }

        // 标签 :
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

        // 图标 :
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
     * @param string                   $position 排序名称
     * @param \yii\db\BaseActiveRecord $model    model数据
     * @param mixed                    $key      主键id
     * @param integer                  $index    数组键值
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
