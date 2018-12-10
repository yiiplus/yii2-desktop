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

use Closure;
use Yii;
use yii\base\Model;
use yii\base\Object;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * 生成树形菜单列表
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class TreeColumn extends Object
{
    /**
     * 列表对象
     * @var object
     */
    public $grid;

    /**
     * 列表头部对象
     * @var object
     */
    public $header;

    /**
     * 列表底部对象
     * @var object
     */
    public $footer;

    /**
     * 列表内容对象
     * @var object
     */
    public $content;

    /**
     * @var boolean whether this column is visible. Defaults to true.
     */
    public $visible = true;

    /**
     * @var 排列列组标签的HTML属性.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var 排列列组标签的HTML属性
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $headerOptions = [];

    /**
     * @var 排列列组标签的HTML属性
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $contentOptions = [];

    /**
     * @var 排列列组标签的HTML属性
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $footerOptions = [];

    /**
     * @var 将与此列关联的属性名称串起来。当[[content]] nor [[value]]
     * 指定后，将从每个数据模型中检索并显示指定属性的值。[[content]] nor [[value]]
     * 此外，如果未指定[[Lab] ]，则将显示与属性相关联的标签。
     */
    public $attribute;

    /**
     * 默认标签值
     * @var string
     */
    public $label;

    /**
     * @var boolean whether the header label should be HTML-encoded.
     * @see label
     */
    public $encodeLabel = true;

    /**
     * 数据单元格默认值
     * @var string
     */
    public $value;

    /**
     * 数据默认类型.
     * @var string
     */
    public $format = 'text';

    /**
     * 呈现头部单元格
     */
    public function renderHeaderCell()
    {
        return Html::tag('th', $this->renderHeaderCellContent(), $this->headerOptions);
    }

    /**
     * 呈现底部单元格
     */
    public function renderFooterCell()
    {
        return Html::tag('td', $this->renderFooterCellContent(), $this->footerOptions);
    }

    /**
     * 呈现数据单元
     *
     * @param mixed   $model object
     * @param mixed   $key   数据相关联的key值
     * @param integer $index 数据索引
     *
     * @return string
     */
    public function renderDataCell($model, $key, $index)
    {
        if ($this->contentOptions instanceof Closure) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }
        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }

    /**
     * 呈现标题单元格内容。
     * 默认实现简单地呈现[[header] ]。
     * 可以重写此方法来自定义标题单元格的呈现。
     *
     * @return string
     */
    protected function renderHeaderCellContent()
    {
        if ($this->header !== null || $this->label === null && $this->attribute === null) {
            return trim($this->header) !== '' ? $this->header : $this->grid->emptyCell;
        }

        $provider = $this->grid->dataProvider;

        if ($this->label === null) {
            if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQueryInterface) {
                /* @var $model Model */
                $model = new $provider->query->modelClass;
                $label = $model->getAttributeLabel($this->attribute);
            } else {
                $models = $provider->getModels();
                if (($model = reset($models)) instanceof Model) {
                    /* @var $model Model */
                    $label = $model->getAttributeLabel($this->attribute);
                } else {
                    $label = Inflector::camel2words($this->attribute);
                }
            }
        } else {
            $label = $this->label;
        }

        return $this->encodeLabel ? Html::encode($label) : $label;
    }

    /**
     * 呈现页脚单元格内容。
     * 默认实现简单地呈现[[footer] ]。
     * 可以重写此方法来自定义页脚单元格的呈现。
     *
     * @return string
     */
    protected function renderFooterCellContent()
    {
        return trim($this->footer) !== '' ? $this->footer : $this->grid->emptyCell;
    }

    /**
     * 呈现单元格数据
     *
     * @param mixed   $model object
     * @param mixed   $key   与数据模型关联的密钥
     * @param integer $index 索引值
     *
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            return $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        } else {
            if ($this->content !== null) {
                return call_user_func($this->content, $model, $key, $index, $this);
            } else {
                return $this->grid->emptyCell;
            }
        }
    }

    /**
     * 返回数据单元格值
     *
     * @param mixed   $model object
     * @param mixed   $key   与数据模型关联的密钥
     * @param integer $index 索引[[GridView::dataProvider]].
     *
     * @return string
     */
    public function getDataCellValue($model, $key, $index)
    {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                return ArrayHelper::getValue($model, $this->value);
            } else {
                return call_user_func($this->value, $model, $key, $index, $this);
            }
        } elseif ($this->attribute !== null) {
            return ArrayHelper::getValue($model, $this->attribute);
        }
        return null;
    }
}
