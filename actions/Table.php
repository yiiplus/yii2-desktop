<?php

namespace yiiplus\desktop\actions;

use Yii;
use Closure;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\i18n\Formatter;
use yii\data\Pagination;
use yii\data\Sort;
use yii\base\InvalidArgumentException;

/**
 * BootstrapTable 控制器动作封装
 */
class Table extends \yii\base\Action
{
    private $_model;
    private $_sort;
    private $_pagination;
    private $_options = [
        'id' => 'table',
        'data-toolbar' => '#toolbar',
        'data-locale' => 'zh-CN',
        'data-height' => '527', // 736
        'data-cache' => 'false',
        'data-sortable' => 'true',
        'data-pagination' => 'true',
        'data-side-pagination' => 'server',
        'data-show-pagination-switch' => 'true',
        'data-show-columns' => 'true',
        'data-show-multi-sort' => 'true',
        'data-show-refresh' => 'true',
        'data-show-toggle' => 'true',
        'data-show-export' => 'true',
        'data-search' => 'true',
        'data-advanced-search' => 'true',
        'data-id-table' => 'advancedTable',
    ];
    
    /**
     * 模型名称
     */
    public $modelClass;
    /**
     * 标题
     */
    public $title;
    /**
     * 视图名称
     */
    public $viewName = '/layouts/table';
    /**
     * @var array grid column configuration. Each array element represents the configuration
     * for one particular grid column. For example,
     */
    public $toolbar = [];
    /**
     * @var array grid column configuration. Each array element represents the configuration
     * for one particular grid column. For example,
     */
    public $columns = [];
    /**
     * @var array the HTML attributes for the grid table element.
     * @see http://bootstrap-table.wenzhixin.net.cn/zh-cn/documentation/
     */
    public $options = [];
    /**
     * @var Closure an anonymous function that is called once BEFORE rendering each data model.
     * It should have the similar signature as [[rowOptions]]. The return result of the function
     * will be rendered directly.
     */
    public $beforeRow;
    /**
     * @var Closure an anonymous function that is called once AFTER rendering each data model.
     * It should have the similar signature as [[rowOptions]]. The return result of the function
     * will be rendered directly.
     */
    public $afterRow;
    /**
     * @var array|Formatter the formatter used to format model attribute values into displayable texts.
     * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
     * instance. If this property is not set, the "formatter" application component will be used.
     */
    public $formatter;


    /**
     * 动作初始化
     */
    public function init()
    {
        parent::init();
        $this->initColumns();
    }

    /**
     * 初始化列对象
     */
    protected function initColumns()
    {
        if (empty($this->columns)) {
            $this->columns = $this->model->attributes();
        }

        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $column, $matches)) {
                    throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
                }
                $column = [
                    'field'  => $matches[1],
                    'format' => isset($matches[3]) ? $matches[3] : 'text',
                    'title'  => isset($matches[5]) ? $matches[5] : $this->model->getAttributeLabel($matches[1]),
                ];
            }

            if (isset($column['field'])) {
                // 默认标题
                if(!isset($column['title'])) {
                    $column['title'] = $this->model->getAttributeLabel($column['field']);
                }

                // 默认操作
                if(substr($column['field'], 0, 1) == '_') {
                    $column = array_merge([
                        'value' => function($row, $pk, $index) {
                            static $object;
                            if (is_null($object)) {
                                $object = Yii::createObject(['class' => 'yiiplus\desktop\widgets\table\ActionColumn']);
                            }
                            return $object->renderDataCell($row, $pk, $index);
                        },
                        'align' => 'center',
                        'searchable' => false,
                        'sortable' => false
                    ], $column);
                }
            }

            $this->columns[$i] = $column;
        }
    }

    /**
     * BootstrapTable 封装入口
     */
    public function run()
    {
        if (Yii::$app->request->isAjax) {
            return $this->query(); 
        }
        return $this->view();
    }

    /**
     * 渲染表格
     */
    protected function view()
    {
        // 标题
        if(empty($this->title)) {
            $this->title = $this->model->formName();
        }

        // 默认选项
        $this->toolbar['tableId'] = $this->_options['id'];
        $this->_options['data-url'] = Yii::$app->request->getUrl();
        $this->_options['data-method'] = 'get';
        $this->_options['data-columns'] = $this->columns;
        return $this->controller->render($this->viewName, [
            'id' => $this->_options['id'],
            'title'   => $this->title,
            'options' => $this->options,
            'columns' => $this->columns,
            'toolbar' => $this->toolbar,
            'table'   => Html::tag('table', '', $this->_options),
        ]);
    }

    /**
     * 数据查询
     */
    protected function query()
    {
        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }

        // 数据处理
        $rows = $this->_rows();
        $pks = $this->model::primaryKey();
        foreach ($rows as $index => &$row) {
            if (count($pks) === 1) {
                $pk = $row[$pks[0]];
            } else {
                $pk = [];
                foreach ($pks as $v) {
                    $pk[$v] = $row[$v];
                }
            }

            // 前置操作
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $row, $pk, $index);
            }

            // 数据格式化
            foreach ($this->columns as $column) {
                if(empty($column['field'])) {
                    continue;
                }

                $value = ArrayHelper::getValue($row, $column['field']);
                if (isset($column['value']) && $column['value'] !== null) {
                    if (is_string($column['value'])) {
                        $value = ArrayHelper::getValue($row, $column['value']);
                    } elseif ($column['value'] instanceof Closure) {
                        $value = call_user_func($column['value'], $row, $pk, $index);
                    }
                }
                if(isset($column['format'])) {
                    $value = $this->formatter->format($value, $column['format']);
                }
                $row[$column['field']] = $value;
            }

            // 后置操作
            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $row, $pk, $index);
            }
        }

        return json_encode(['total' => $this->pagination->totalCount, 'rows'  => $rows]);
    }

    /**
     * 执行查询并将所有结果作为数组返回
     * @return array 查询结果 如果查询结果为空，则返回空数组。
     */
    private function _rows() {
        $rows = [];
        // 数据查询
        if (method_exists($this->model, 'search')) {
            $query = $this->model->search(Yii::$app->request->queryParams);
        } else {
            $query = $this->model::find();
            if (Yii::$app->request->get('filter')) { // 高级搜索
                $filter = json_decode(Yii::$app->request->get('filter'), true);
                foreach ($this->columns as $column) {
                    if(empty($column['field']) || !isset($filter[$column['field']])) {
                        continue;
                    }
                    if (!isset($column['searchable']) || $column['searchable'] === true) {
                        $query->andFilterWhere(['like', $column['field'], $filter[$column['field']]]);
                    } elseif (isset($column['searchable']) && $column['searchable'] !== false) {
                        $query->andFilterWhere(call_user_func($column['searchable'], $filter[$column['field']]));
                    }
                }
            } elseif (Yii::$app->request->get('search')) { // 快速搜索
                foreach ($this->columns as $column) {
                    if(empty($column['field'])) {
                        continue;
                    }
                    if (!isset($column['searchable']) || $column['searchable'] === true) {
                        $query->orFilterWhere(['like', $column['field'], Yii::$app->request->get('search')]);
                    } elseif (isset($column['searchable']) && $column['searchable'] !== false) {
                        $query->orFilterWhere(call_user_func($column['searchable'], Yii::$app->request->get('search')));
                    }
                }
            }
        }

        // 分页
        if (($pagination = $this->getPagination()) !== false) { 
            $pagination->totalCount = $query->count();
            if ($pagination->totalCount === 0) {
                return $rows;
            }
            $query->limit($pagination->getLimit())->offset($pagination->getOffset());
        }

        // 排序
        if (($sort = $this->getSort()) !== false) { 
            $query->addOrderBy($sort->getOrders());
        }
        
        $rows = $query->asArray()->all();
        return $rows;
    }

    /**
     * Returns the pagination object used by this data provider.
     * Note that you should call [[prepare()]] or [[getModels()]] first to get correct values
     * of [[Pagination::totalCount]] and [[Pagination::pageCount]].
     * @return Pagination|false the pagination object. If this is false, it means the pagination is disabled.
     */
    protected function getPagination()
    {
        if ($this->_pagination === null) {
            $this->setPagination([]);
        }

        return $this->_pagination;
    }

    /**
     * Sets the pagination for this data provider.
     * @param array|Pagination|bool $value the pagination to be used by this data provider.
     * This can be one of the following:
     *
     * - a configuration array for creating the pagination object. The "class" element defaults
     *   to 'yii\data\Pagination'
     * - an instance of [[Pagination]] or its subclass
     * - false, if pagination needs to be disabled.
     *
     * @throws InvalidArgumentException
     */
    protected function setPagination($value)
    {
        if (is_array($value)) {
            $config = [
                'class' => Pagination::className(),
                'defaultPageSize' => Yii::$app->request->get('limit', 10),
            ];
            if(Yii::$app->request->get('offset')) {
                $config['page'] = Yii::$app->request->get('offset') / $config['defaultPageSize'];
            }
            $this->_pagination = Yii::createObject(array_merge($config, $value));
        } elseif ($value instanceof Pagination || $value === false) {
            $this->_pagination = $value;
        } else {
            throw new InvalidArgumentException('Only Pagination instance, configuration array or false is allowed.');
        }
    }

    /**
     * Returns the sorting object used by this data provider.
     * @return Sort|bool the sorting object. If this is false, it means the sorting is disabled.
     */
    protected function getSort()
    {
        if ($this->_sort === null) {
            $this->setSort([]);
        }

        return $this->_sort;
    }

    /**
     * Sets the sort definition for this data provider.
     * @param array|Sort|bool $value the sort definition to be used by this data provider.
     * This can be one of the following:
     *
     * - a configuration array for creating the sort definition object. The "class" element defaults
     *   to 'yii\data\Sort'
     * - an instance of [[Sort]] or its subclass
     * - false, if sorting needs to be disabled.
     *
     * @throws InvalidArgumentException
     */
    protected function setSort($value)
    {
        if (is_array($value)) {
            $config = ['class' => Sort::className()];
            $this->_sort = Yii::createObject(array_merge($config, $value));
        } elseif ($value instanceof Sort || $value === false) {
            $this->_sort = $value;
        } else {
            throw new InvalidArgumentException('Only Sort instance, configuration array or false is allowed.');
        }

        if (($sort = $this->getSort()) !== false) {
            if (empty($sort->attributes)) {
                foreach ($this->model->attributes() as $attribute) {
                    $sort->attributes[$attribute] = [
                        'asc' => [$attribute => SORT_ASC],
                        'desc' => [$attribute => SORT_DESC],
                        'label' => $this->model->getAttributeLabel($attribute),
                    ];
                }
            } else {
                foreach ($sort->attributes as $attribute => $config) {
                    if (!isset($config['label'])) {
                        $sort->attributes[$attribute]['label'] = $model->getAttributeLabel($attribute);
                    }
                }
            }
        }

        $params = Yii::$app->getRequest()->getQueryParams();
        if (isset($params['multiSort'])) {
            $sort->enableMultiSort = true;
            $sorts = [];
            foreach ($params['multiSort'] as $v) {
                $sorts[] = $v['sortOrder'] === 'desc' ? '-'.$v['sortName'] : $v['sortName'];
            }
            $params[$sort->sortParam] = implode($sort->separator, $sorts);
            unset($params['multiSort']);
            Yii::$app->getRequest()->setQueryParams($params);
        } elseif (isset($params['sort'])) {
            $params[$sort->sortParam] = $params['order'] === 'desc' ? '-'.$params['sort'] : $params['sort'];
            unset($params['order']);
            Yii::$app->getRequest()->setQueryParams($params);
        }
    }

    /**
     * 模型懒加载
     */
    protected function getModel()
    {
        if ($this->_model === null) {
            $this->_model = new $this->modelClass();
        }
        return $this->_model;
    }
}