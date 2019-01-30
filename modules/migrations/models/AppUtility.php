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

use Yii;

/**
 * 将数据库字段转换为插入语句
 *
 * @author zhangxu <2217418603@qq.com>
 * @since 2.0.0
 */
class AppUtility
{
    /*
     * 结果字符串
     */
    public  $string = '';

    /*
     * tab跳格
     */
    private $Tab    = "\t";

    /*
     * 换行符
     */
    private $Nw     = '\n';

    /*
     * 存储数组
     */
    private $array  = [];

    private $dbType = '';

    private $schema;

    /**
     * 初始化
     *
     * AppUtility constructor.
     *
     * @param object $array        字段对象
     * @param string $databaseType 数据库类型
     *
     */
    function __construct($array, $databaseType = 'mysql')
    {
        $this->array = self::objectToArray($array);

        switch (strtolower($databaseType)) {
            case 'mssql':
                self::runMsSql();
                break;
            case 'mysql':
                self::runMySql();
                break;
            case 'sqlite':
                self::runSqlite();
                break;
            case 'pgsql':
                self::runPgSql();
                break;
        }
    }

    /**
     * 对象转数组
     *
     * @param object $array_in 数组
     *
     * @return array
     */
    private function objectToArray($array_in)
    {
        $array = array();
        if (is_object($array_in)) {
            return self::objectToArray(get_object_vars($array_in));
        } else {
            if (is_array($array_in)) {
                foreach ($array_in as $key => $value) {
                    if (is_object($value)) {
                        $array[$key] = self::objectToArray($value);
                    } elseif (is_array($value)) {
                        $array[$key] = self::objectToArray($value);
                    } else {
                        $array[$key] = $value;
                    }
                }
            }
        }

        return $array;
    }

    /**
     * 将mssql对象转为语句
     */
    private function runMsSql()
    {
        if (isset($this->array['dbType']))
            $this->string .= $this->Tab . "'{$this->array['name']}' => '" . strtoupper($this->array['dbType']) . "";
        if (isset($this->array['autoIncrement']))
            $this->string .= ($this->array['autoIncrement']) ? ' IDENTITY' : '';
        if (isset($this->array['allowNull']))
            $this->string .= ($this->array['allowNull']) ? ' NULL' : ' NOT NULL';
        if (isset($this->array['defaultValue']))
            $this->string .= (empty($this->array['defaultValue'])) ? '' : " DEFAULT \'{$this->array['defaultValue']}\'";

    }

    /**
     * 将mysql对象转为语句
     */
    private function runMySql()
    {
        if (isset($this->array['dbType'])) {
            if (strpos($this->array['dbType'], 'enum') !== FALSE) {
                $this->array['dbType'] = str_replace('enum', 'ENUM', $this->array['dbType']);
                $this->array['dbType'] = str_replace("'", "\\'", $this->array['dbType']);
                $this->string .= $this->Tab . "'{$this->array['name']}' => '" . $this->array['dbType'] . "";
            } elseif (strpos($this->array['dbType'], 'set') !== FALSE) {
                $this->array['dbType'] = str_replace('set', 'SET', $this->array['dbType']);
                $this->array['dbType'] = str_replace("'", "\\'", $this->array['dbType']);
                $this->string .= $this->Tab . "'{$this->array['name']}' => '" . $this->array['dbType'] . "";
            } else {
                $this->string .= $this->Tab . "'{$this->array['name']}' => '" . strtoupper($this->array['dbType']) . "";
            }
        }
        if (isset($this->array['allowNull']))
            $this->string .= ($this->array['allowNull']) ? ' NULL' : ' NOT NULL';
        if (isset($this->array['autoIncrement']))
            $this->string .= ($this->array['autoIncrement']) ? ' AUTO_INCREMENT' : '';
        if (isset($this->array['defaultValue']))
            if (!is_array($this->array['defaultValue'])) {
                //0 is int
                if(is_int($this->array['defaultValue']) || !empty($this->array['defaultValue']))
                {
                    $this->string .= " DEFAULT \'{$this->array['defaultValue']}\'";
                }
            } else {
                $this->string .= (empty($this->array['defaultValue'])) ? '' : " DEFAULT " . $this->array['defaultValue']['expression'] . " ";
            }
        if (isset($this->array['comment']))
            $this->string .= (empty($this->array['comment'])) ? '' : " COMMENT \'{$this->array['comment']}\'";
    }

    /**
     * 将sqllite对象转为语句
     */
    private function runSqlite()
    {
        if (isset($this->array['dbType']))
            $this->string .= $this->Tab . "'{$this->array['name']}' => '" . strtoupper($this->array['dbType']) . "";
        if (isset($this->array['allowNull']))
            $this->string .= ($this->array['allowNull']) ? ' NULL' : ' NOT NULL';
        if (isset($this->array['autoIncrement']))
            $this->string .= ($this->array['autoIncrement']) ? ' AUTOINCREMENT' : '';
        if (isset($this->array['defaultValue']))
            $this->string .= (empty($this->array['defaultValue'])) ? '' : " DEFAULT \'{$this->array['defaultValue']}\'";
    }

    /**
     * 将pgsql对象转为语句
     */
    private function runPgSql()
    {
        if (isset($this->array['dbType']))
            $this->string .= $this->Tab . "'{$this->array['name']}' => '" . strtoupper($this->array['dbType']) . "";
        if (isset($this->array['autoIncrement']))
            $this->string .= ($this->array['autoIncrement']) ? ' SERIAL' : '';
        if (isset($this->array['allowNull']))
            $this->string .= ($this->array['allowNull']) ? ' NULL' : ' NOT NULL';
        if (isset($this->array['defaultValue']))
            $this->string .= (empty($this->array['defaultValue'])) ? '' : " DEFAULT \'{$this->array['defaultValue']}\'";
    }

}
