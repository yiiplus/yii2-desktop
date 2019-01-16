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

namespace yiiplus\desktop\widgets\tree;

use Yii;
use Closure;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\i18n\Formatter;

/**
 * 无限极分类
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    liguangquan@163.com
 * @copyright 2006-2018 YiiPlus Ltd
 * @link      http://www.yiiplus.com
 */
class TreeGrid extends Widget
{
    /**
     * Db数据
     *
     * @var object
     */
    public $dataProvider;
    
    /**
     * 如果在配置数据列时没有显式指定类名，则触发默认数据列类
     *
     * @var string
     */
    public $dataColumnClass;

    /**
     * 默认表格class名
     *
     * @var array
     */
    public $options = ['class' => 'table table-striped table-bordered'];

    /**
     * 控制jquery->treegrid默认为收起状态
     *
     * @var array
     */
    public $pluginOptions = [];

    /**
     * 为表格头部自定义属性
     *
     * @var array
     */
    public $headerRowOptions = [];

    /**
     * 表格底部自定义属性
     *
     * @var array
     */
    public $footerRowOptions = [];

    /**
     * 当没有任何数据的时候，默认显示的html内容
     *
     * @var object
     */
    public $emptyText;

    /**
     * 数据为空的表格class值
     *
     * @var array
     */
    public $emptyTextOptions = ['class' => 'empty'];

    /**
     * 控制表格头部<th>显示
     *
     * @var boolean
     */
    public $showHeader = true;

    /**
     * 控制表格底部<tfoot>显示
     *
     * @var boolean
     */
    public $showFooter = false;

    /**
     * 格式化数据
     *
     * @var mixed
     */
    public $formatter;

    /**
     * 回调函数
     *
     * @var array
     */
    public $rowOptions = [];

    /**
     * 用于构建树的键列的字符串名称
     *
     * @var string
     */
    public $keyColumnName;

    /**
     * 用于构建树的父列的字符串名称
     *
     * @var string
     */
    public $parentColumnName;

    /**
     * 用于构建树的根值
     *
     * @var mixed
     */
    public $parentRootValue = null;

    /**
     * column
     *
     * @var array
     */
    public $columns = [];

    /**
     * 入口init()
     *
     * @return InvalidConfigException
     */
    public function init()
    {
        // DB 数据源
        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }

        // 数据不存在使用 yii2 语言包
        if ($this->emptyText === null) {
            $this->emptyText = Yii::t('yii', 'No results found.');
        }

        // 获取默认表格 id
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        // 格式化数据
        if ($this->formatter == null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }

        // 参数检测
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
        if (!$this->keyColumnName) {
            throw new InvalidConfigException('The "keyColumnName" property must be specified"');
        }
        if (!$this->parentColumnName) {
            throw new InvalidConfigException('The "parentColumnName" property must be specified"');
        }

        // 初始化
        $this->initColumns();
    }

    /**
     * 入口run()
     *
     * @return HTML标签
     */
    public function run()
    {
        $id = $this->options['id']; // 表格 id 值默认值 w0
        $options = Json::htmlEncode($this->pluginOptions);

        $view = $this->getView();
        TreeGridAsset::register($view); // 加载配置的静态 <js;css> 文件

        $view->registerJs("jQuery('#${id}').treegrid(${options});");

        $header = $this->showHeader ? $this->renderTableHeader() : false; // 表格头部
        $body = $this->renderItems(); // 表格主体
        $footer = $this->showFooter ? $this->renderTableFooter() : false; // 表格底部

        $content = array_filter([
            $header,
            $body,
            $footer
        ]);
        return Html::tag('table', implode("\n", $content), $this->options);
    }

    /**
     * 数据不存在时表格显示内容
     *
     * @return string
     *
     * @see emptyText
     */
    public function renderEmpty()
    {
        $options = $this->emptyTextOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        return Html::tag($tag, ($this->emptyText === null ? Yii::t('yii', 'No results found.') : $this->emptyText), $options);
    }

    /**
     * 用给定的数据模型和键呈现表行。
     *
     * @param mixed   $model model
     * @param mixed   $key   主键id值
     * @param integer $index string
     *
     * @return string
     */
    public function renderTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column TreeColumn */
        foreach ($this->columns as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index);
        }
        if ($this->rowOptions instanceof Closure) {//如果定义了回调函数
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;
        $id = ArrayHelper::getValue($model, $this->keyColumnName); //id值
        Html::addCssClass($options, "treegrid-$id");

        $parentId = ArrayHelper::getValue($model, $this->parentColumnName); //parent值

        if ($parentId) {
            if(ArrayHelper::getValue($this->pluginOptions, 'initialState') == 'collapsed'){
                Html::addCssStyle($options, 'display: none;'); //如果设置的收起，子类不显示
            }
            Html::addCssClass($options, "treegrid-parent-$parentId");
        }

        return Html::tag('tr', implode('', $cells), $options);
    }

    /**
     * 生成表格头部
     *
     * @return string
     */
    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column TreeColumn */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        return "<thead>\n" . $content . "\n</thead>";
    }

    /**
     * 生成表格头部
     *
     * @return string
     */
    public function renderTableFooter()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column TreeColumn */
            $cells[] = $column->renderFooterCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->footerRowOptions);
        return "<tfoot>\n" . $content . "\n</tfoot>";
    }

    /**
     * 表格主体
     *
     * @return string
     */
    public function renderItems()
    {
        // 生成树形数据源
        $this->dataProvider->setKeys([]);
        $models = array_values($this->dataProvider->getModels());
        $models = $this->normalizeData($models, $this->parentRootValue); // 生成树形数据
        $this->dataProvider->setModels($models);    // 将树形数据应用 setModels()
        $this->dataProvider->setKeys(null); // 将树形数据所有 id 应用setKeys(null)
        $this->dataProvider->prepare();
        $keys = $this->dataProvider->getKeys(); // 获取所有 id 值

        $rows = [];
        foreach ($models as $index => $model) { // $model 数据值
            $key = $keys[$index]; // 对应id值
            $rows[] = $this->renderTableRow($model, $key, $index); // 生成数据路由
        }

        // 没有找到数据
        if (empty($rows)) {
            $colspan = count($this->columns);
            return "<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>";
        }

        return implode("\n", $rows);
    }

    /**
     * 生成表单数据入口
     */
    protected function initColumns()
    {
        if (empty($this->columns)) { //column 未找到
            $this->guessColumns();
        }
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => $this->dataColumnClass ? : TreeColumn::className(),
                    'grid' => $this,
                ], $column));
            }
            $this->columns[$i] = $column;
        }
    }

    /**
     * 基于column创建label对象.
     *
     * @param string $text string
     *
     * @return object
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return Yii::createObject([
            'class' => $this->dataColumnClass ? : TreeColumn::className(),
            'grid' => $this,
            'attribute' => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'text',
            'label' => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    /**
     * Column未找到调用
     */
    protected function guessColumns()
    {
        $models = $this->dataProvider->getModels();
        $model = reset($models);
        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                $this->columns[] = $name;
            }
        }
    }

    /**
     * 递归生成树形数据
     *
     * @param array  $data     model数据
     * @param string $parentId 根值
     *
     * @return array
     */
    protected function normalizeData(array $data, $parentId = null) {
        $result = [];
        foreach ($data as $element) {
            //获取与parent数据相同的数据
            if (ArrayHelper::getValue($element, $this->parentColumnName) === $parentId) {
                $result[] = $element;
                $children = $this->normalizeData($data, ArrayHelper::getValue($element, $this->keyColumnName));
                if ($children) { //子类存在
                    $result = array_merge($result, $children);
                }
            }
        }
        return $result;
    }
}
