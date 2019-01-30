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
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\db\Connection;

/**
 * 迁移模型
 *
 * @author zhangxu <zhangxu@mocaapp.com>
 * @since 2.0.0
 */
class MigrationUtility extends Model
{
    /*
     * 迁移名称
     */
    public $migrationName = '';

    /*
     * 默认迁移路径 其它数据库迁移在后面加上数据库名字
     */
    public $migrationPath = '@console/migrations';

    /*
     * 表结构列表
     */
    public $tableSchemas;

    /*
     * 数据列表
     */
    public $tableDatas;

    /*
     * 数据库列表
     */
    public $database;

    /*
     * 数据库连接句柄
     */
    public $connect;

    /*
     * 表属性选项
     */
    public $tableOption = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     * @return array
     */
    function rules()
    {
        return [
            [[
                'migrationName',
                'migrationPath',
                'tableSchemas',
                'tableDatas',
                'tableOption',
                'database'], 'safe']
        ];
    }

    public static function getConnect($database)
    {
        $dsnArr = explode(';', Yii::$app->db->dsn);
        foreach ($dsnArr as $k => $v) {
            if (strpos($v, 'dbname=') !== false) {
                $dsnArr[$k] = 'dbname=' . $database;
            }
        }
        $connect = new Connection([
            'dsn' => implode(';', $dsnArr),
            'username' => Yii::$app->db->username,
            'password' => Yii::$app->db->password,
            'charset'  => Yii::$app->db->charset
        ]);
        return $connect;
    }

    /**
     * 获取表名
     *
     * @param string $database 数据库名称
     *
     * @return array|string[]
     * @throws \yii\base\NotSupportedException
     */
    public static function getTableNames($database)
    {
        $connect = self::getConnect($database);

        $tables = $connect->getSchema()->getTableNames('', TRUE);
        $tables = array_combine($tables,$tables);
        // 移除migration表
        ArrayHelper::remove($tables, \Yii::$app->db->getSchema()->getRawTableName('{{%migration}}'));
        return $tables;
    }

    /**
     * 获取当前数据库列表
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getDatabases()
    {
        $databases = \Yii::$app->db->createCommand('show databases')->queryAll();
        $data = [];
        foreach ($databases as $database) {
            if (in_array($database['Database'], ['performance_schema', 'information_schema', 'mysql'])) {
                continue;
            }
            $data[$database['Database']] = $database['Database'];
        }
        return $data;
    }

    /**
     * 获取当前连接数据库
     *
     * @return mixed
     * @throws \yii\db\Exception
     */
    public static function getNowDatabase()
    {
        $database = Yii::$app->db->createCommand('select database()')->queryOne();
        return $database['database()'];
    }
}
